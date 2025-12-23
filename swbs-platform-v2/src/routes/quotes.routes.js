const express = require('express');
const Joi = require('joi');

const requireAuth = require('../middlewares/requireAuth');
const requireAdmin = require('../middlewares/requireAdmin');
const validate = require('../middlewares/validate');
const quoteService = require('../services/quoteService');

const router = express.Router();

const quoteSchema = Joi.object({
  serviceId: Joi.number().integer().positive().allow(null),
  details: Joi.string().min(5).max(5000).required(),
  budget: Joi.string().max(255).allow('', null),
  deadline: Joi.string().max(255).allow('', null),
  extra: Joi.any(),
});

// Client: créer un devis
router.post('/', requireAuth, validate(quoteSchema), async (req, res, next) =&gt; {
  try {
    const payload = {
      details: req.body.details,
      budget: req.body.budget || null,
      deadline: req.body.deadline || null,
      extra: req.body.extra || null,
    };
    const quote = await quoteService.createQuote({
      userId: req.session.user.id,
      serviceId: req.body.serviceId,
      payload,
    });

    if (req.app.get('io')) {
      req.app
        .get('io')
        .to('admins')
        .emit('quote:new', { id: quote.id, userId: quote.userId });
    }

    res.status(201).json({ quote });
  } catch (err) {
    next(err);
  }
});

// Client: liste de ses devis
router.get('/client', requireAuth, async (req, res, next) =&gt; {
  try {
    const quotes = await quoteService.getClientQuotes(req.session.user.id);
    res.json({ quotes });
  } catch (err) {
    next(err);
  }
});

// Admin: liste de tous les devis
router.get('/admin', requireAuth, requireAdmin, async (req, res, next) =&gt; {
  try {
    const quotes = await quoteService.getAdminQuotes();
    res.json({ quotes });
  } catch (err) {
    next(err);
  }
});

// Admin: changer statut
router.patch(
  '/admin/:id/status',
  requireAuth,
  requireAdmin,
  validate(
    Joi.object({
      status: Joi.string()
        .valid('recu', 'en_cours', 'valide', 'refuse')
        .required(),
    })
  ),
  async (req, res, next) =&gt; {
    try {
      const updated = await quoteService.updateQuoteStatus(
        req.params.id,
        req.body.status
      );
      res.json({ quote: updated });
    } catch (err) {
      next(err);
    }
  }
);

module.exports = router;