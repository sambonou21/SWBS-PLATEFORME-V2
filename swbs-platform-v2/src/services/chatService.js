const db = require('../config/db');

let ioInstance = null;

function initSocketIo(io) {
  ioInstance = io;

  io.on('connection', (socket) =&gt; {
    socket.on('join', ({ conversationId, role }) =&gt; {
      if (conversationId) {
        socket.join(`conv:${conversationId}`);
      }
      if (role === 'admin') {
        socket.join('admins');
      }
    });

    socket.on('chat:message', async ({ conversationId, senderType, content }, ack) =&gt; {
      try {
        const msg = await createMessage({ conversationId, senderType, content });
        io.to(`conv:${conversationId}`).emit('chat:message', msg);
        io.to('admins').emit('chat:conversation:update', { conversationId });
        if (ack) ack({ ok: true, messageId: msg.id });
      } catch (err) {
        if (ack) ack({ ok: false, error: 'Unable to send message' });
      }
    });
  });
}

async function createConversation({ userId, leadName, leadEmail, leadPhone }) {
  const [result] = await db
    .promise()
    .query(
      'INSERT INTO conversations (userId, leadName, leadEmail, leadPhone, createdAt) VALUES (?, ?, ?, ?, NOW())',
      [userId || null, leadName || null, leadEmail || null, leadPhone || null]
    );
  const [rows] = await db
    .promise()
    .query('SELECT * FROM conversations WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function createMessage({ conversationId, senderType, content }) {
  const [result] = await db
    .promise()
    .query(
      'INSERT INTO messages (conversationId, senderType, content, createdAt) VALUES (?, ?, ?, NOW())',
      [conversationId, senderType, content]
    );
  const [rows] = await db
    .promise()
    .query('SELECT * FROM messages WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function getConversationMessages(conversationId) {
  const [rows] = await db
    .promise()
    .query(
      'SELECT * FROM messages WHERE conversationId = ? ORDER BY createdAt ASC',
      [conversationId]
    );
  return rows;
}

async function getUserConversations(userId) {
  const [rows] = await db
    .promise()
    .query(
      'SELECT * FROM conversations WHERE userId = ? ORDER BY createdAt DESC',
      [userId]
    );
  return rows;
}

async function getAllConversations() {
  const [rows] = await db
    .promise()
    .query('SELECT * FROM conversations ORDER BY createdAt DESC');
  return rows;
}

module.exports = {
  initSocketIo,
  createConversation,
  createMessage,
  getConversationMessages,
  getUserConversations,
  getAllConversations,
};