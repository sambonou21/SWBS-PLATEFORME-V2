const db = require('../config/db');

async function createQuote({ userId, serviceId, payload }) {
  const [result] = await db
    .promise()
    .query(
      'INSERT INTO quotes (userId, serviceId, payload, status, createdAt) VALUES (?, ?, ?, ?, NOW())',
      [userId, serviceId || null, JSON.stringify(payload || {}), 'recu']
    );
  const [rows] = await db
    .promise()
    .query('SELECT * FROM quotes WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function getClientQuotes(userId) {
  const [rows] = await db
    .promise()
    .query(
      'SELECT q.*, s.title AS serviceTitle FROM quotes q LEFT JOIN services s ON q.serviceId = s.id WHERE q.userId = ? ORDER BY q.createdAt DESC',
      [userId]
    );
  return rows;
}

async function getAdminQuotes() {
  const [rows] = await db
    .promise()
    .query(
      'SELECT q.*, u.name AS userName, u.email AS userEmail FROM quotes q JOIN users u ON q.userId = u.id ORDER BY q.createdAt DESC'
    );
  return rows;
}

async function updateQuoteStatus(id, status) {
  const allowed = ['recu', 'en_cours', 'valide', 'refuse'];
  if (!allowed.includes(status)) {
    const err = new Error('Invalid status');
    err.status = 400;
    throw err;
  }
  await db
    .promise()
    .query('UPDATE quotes SET status = ? WHERE id = ?', [status, id]);
  const [rows] = await db
    .promise()
    .query('SELECT * FROM quotes WHERE id = ?', [id]);
  return rows[0];
}

module.exports = {
  createQuote,
  getClientQuotes,
  getAdminQuotes,
  updateQuoteStatus,
};