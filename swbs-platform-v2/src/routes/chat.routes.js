const express = require('express');
const Joi = require('joi');

const requireAuth = require('../middlewares/requireAuth');
const requireAdmin = require('../middlewares/requireAdmin');
const validate = require('../middlewares/validate');
const chatService = require('../services/chatService');

const router = express.Router();

const leadSchema = Joi.object({
  name: Joi.string().min(2).max(100).required(),
  email: Joi.string().email().required(),
  phone: Joi.string().max(50).allow('', null),
});

const messageSchema = Joi.object({
  conversationId: Joi.number().integer().positive().required(),
  content: Joi.string().min(1).max(5000).required(),
});

// Créer conversation pour lead anonyme
router.post('/lead', validate(leadSchema), async (req, res, next) => {
  try {
    const conv = await chatService.createConversation({
      userId: null,
      leadName: req.body.name,
      leadEmail: req.body.email,
      leadPhone: req.body.phone || null,
    });
    res.status(201).json({ conversation: conv });
  } catch (err) {
    next(err);
  }
});

// Créer conversation pour user connecté si besoin
router.post('/start', requireAuth, async (req, res, next) => {
  try {
    const conversations = await chatService.getUserConversations(
      req.session.user.id
    );
    if (conversations.length > 0) {
      return res.json({ conversation: conversations[0] });
    }
    const conv = await chatService.createConversation({
      userId: req.session.user.id,
    });
    res.status(201).json({ conversation: conv });
  } catch (err) {
    next(err);
  }
});

// Poster un message (HTTP fallback, à côté de Socket.IO)
router.post('/message', validate(messageSchema), async (req, res, next) => {
  try {
    const msg = await chatService.createMessage({
      conversationId: req.body.conversationId,
      senderType: req.session.user ? 'user' : 'user',
      content: req.body.content,
    });
    res.status(201).json({ message: msg });
  } catch (err) {
    next(err);
  }
});

// Historique messages d'une conversation
router.get(
  '/:conversationId/messages',
  async (req, res, next) => {
    try {
      const messages = await chatService.getConversationMessages(
        req.params.conversationId
      );
      res.json({ messages });
    } catch (err) {
      next(err);
    }
  }
);

// Admin: liste conversations
router.get('/admin/conversations', requireAuth, requireAdmin, async (req, res, next) => {
  try {
    const conversations = await chatService.getAllConversations();
    res.json({ conversations });
  } catch (err) {
    next(err);
  }
});

module.exports = router;