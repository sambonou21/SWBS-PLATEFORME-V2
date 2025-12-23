const express = require('express');
const requireAuth = require('../middlewares/requireAuth');
const quoteService = require('../services/quoteService');
const chatService = require('../services/chatService');
const db = require('../config/db');

const router = express.Router();

// /api/client/quotes
router.get('/quotes', requireAuth, async (req, res, next) => {
  try {
    const quotes = await quoteService.getClientQuotes(req.session.user.id);
    res.json({ quotes });
  } catch (err) {
    next(err);
  }
});

// /api/client/messages
router.get('/messages', requireAuth, async (req, res, next) => {
  try {
    const conversations = await chatService.getUserConversations(
      req.session.user.id
    );
    if (conversations.length === 0) {
      return res.json({ conversations: [], messages: [] });
    }
    const ids = conversations.map((c) => c.id);
    const [messages] = await db
      .promise()
      .query(
        'SELECT * FROM messages WHERE conversationId IN (?) ORDER BY createdAt ASC',
        [ids]
      );
    res.json({ conversations, messages });
  } catch (err) {
    next(err);
  }
});

// /api/client/orders
router.get('/orders', requireAuth, async (req, res, next) => {
  try {
    const [orders] = await db
      .promise()
      .query(
        'SELECT * FROM orders WHERE userId = ? ORDER BY createdAt DESC',
        [req.session.user.id]
      );
    res.json({ orders });
  } catch (err) {
    next(err);
  }
});

module.exports = router;