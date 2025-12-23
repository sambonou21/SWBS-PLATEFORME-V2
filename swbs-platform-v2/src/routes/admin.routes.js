const express = require('express');
const Joi = require('joi');
const slugify = require('slugify');

const requireAuth = require('../middlewares/requireAuth');
const requireAdmin = require('../middlewares/requireAdmin');
const validate = require('../middlewares/validate');
const settingsService = require('../services/settingsService');
const serviceService = require('../services/serviceService');
const portfolioService = require('../services/portfolioService');
const db = require('../config/db');
const { createUploader } = require('../middlewares/upload');

const router = express.Router();

const servicesUploader = createUploader('services');
const portfolioUploader = createUploader('portfolio');

router.use(requireAuth, requireAdmin);

// ----------------------
// SETTINGS
// ----------------------

// GET /api/admin/settings
router.get('/settings', async (req, res, next) =&gt; {
  try {
    const settings = await settingsService.getSettings();
    res.json({ settings });
  } catch (err) {
    next(err);
  }
});

// PATCH /api/admin/settings
router.patch(
  '/settings',
  validate(
    Joi.object({
      presenceAdmin: Joi.boolean().optional(),
      currencyRates: Joi.object().pattern(Joi.string(), Joi.number()).optional(),
      fedapayKeys: Joi.object().optional(),
      aiKeys: Joi.object().optional(),
      languageDefault: Joi.string().valid('fr', 'en').optional(),
      chatConfig: Joi.object().optional(),
    })
  ),
  async (req, res, next) =&gt; {
    try {
      const patch = {};
      if (req.body.presenceAdmin !== undefined) {
        patch.presenceAdmin = req.body.presenceAdmin ? 1 : 0;
      }
      if (req.body.currencyRates) {
        patch.currencyRates = JSON.stringify(req.body.currencyRates);
      }
      if (req.body.fedapayKeys) {
        patch.fedapayKeys = JSON.stringify(req.body.fedapayKeys);
      }
      if (req.body.aiKeys) {
        patch.aiKeys = JSON.stringify(req.body.aiKeys);
      }
      if (req.body.languageDefault) {
        patch.languageDefault = req.body.languageDefault;
      }
      if (req.body.chatConfig) {
        patch.chatConfig = JSON.stringify(req.body.chatConfig);
      }

      const settings = await settingsService.updateSettings(patch);
      res.json({ settings });
    } catch (err) {
      next(err);
    }
  }
);

// ----------------------
// SERVICES (CRUD + upload)
// ----------------------

const serviceSchema = Joi.object({
  title: Joi.string().min(2).max(191).required(),
  description: Joi.string().min(5).required(),
  price: Joi.number().positive().allow(null),
});

router.get('/services', async (req, res, next) =&gt; {
  try {
    const services = await serviceService.listServices();
    res.json({ services });
  } catch (err) {
    next(err);
  }
});

router.post(
  '/services',
  servicesUploader.uploadSingle('image'),
  validate(serviceSchema),
  async (req, res, next) =&gt; {
    try {
      const slug =
        slugify(req.body.title, { lower: true, strict: true }) +
        '-' +
        Date.now().toString(36);

      const service = await serviceService.createService({
        title: req.body.title,
        slug,
        description: req.body.description,
        price: req.body.price || null,
        imagePath: req.file ? req.file.relativePath : null,
      });
      res.status(201).json({ service });
    } catch (err) {
      next(err);
    }
  }
);

router.put(
  '/services/:id',
  servicesUploader.uploadSingle('image'),
  async (req, res, next) =&gt; {
    try {
      const patch = {};
      if (req.body.title) patch.title = req.body.title;
      if (req.body.description) patch.description = req.body.description;
      if (req.body.price !== undefined) patch.price = req.body.price;
      if (req.file) patch.imagePath = req.file.relativePath;
      const service = await serviceService.updateService(req.params.id, patch);
      if (!service) return res.status(404).json({ error: 'Service not found' });
      res.json({ service });
    } catch (err) {
      next(err);
    }
  }
);

router.delete('/services/:id', async (req, res, next) =&gt; {
  try {
    await serviceService.deleteService(req.params.id);
    res.json({ ok: true });
  } catch (err) {
    next(err);
  }
});

// ----------------------
// PORTFOLIO (CRUD + upload)
// ----------------------

const portfolioSchema = Joi.object({
  title: Joi.string().min(2).max(191).required(),
  description: Joi.string().min(5).required(),
  category: Joi.string().max(191).allow('', null),
});

router.get('/portfolio', async (req, res, next) =&gt; {
  try {
    const items = await portfolioService.listPortfolio();
    res.json({ items });
  } catch (err) {
    next(err);
  }
});

router.post(
  '/portfolio',
  portfolioUploader.uploadSingle('image'),
  validate(portfolioSchema),
  async (req, res, next) =&gt; {
    try {
      const slug =
        slugify(req.body.title, { lower: true, strict: true }) +
        '-' +
        Date.now().toString(36);

      const item = await portfolioService.createPortfolio({
        title: req.body.title,
        slug,
        description: req.body.description,
        category: req.body.category || null,
        imagePath: req.file ? req.file.relativePath : null,
      });
      res.status(201).json({ item });
    } catch (err) {
      next(err);
    }
  }
);

router.put(
  '/portfolio/:id',
  portfolioUploader.uploadSingle('image'),
  async (req, res, next) =&gt; {
    try {
      const patch = {};
      if (req.body.title) patch.title = req.body.title;
      if (req.body.description) patch.description = req.body.description;
      if (req.body.category !== undefined) patch.category = req.body.category;
      if (req.file) patch.imagePath = req.file.relativePath;
      const item = await portfolioService.updatePortfolio(
        req.params.id,
        patch
      );
      if (!item) return res.status(404).json({ error: 'Item not found' });
      res.json({ item });
    } catch (err) {
      next(err);
    }
  }
);

router.delete('/portfolio/:id', async (req, res, next) =&gt; {
  try {
    await portfolioService.deletePortfolio(req.params.id);
    res.json({ ok: true });
  } catch (err) {
    next(err);
  }
});

// ----------------------
// CLIENTS (liste basique)
// ----------------------

router.get('/clients', async (req, res, next) =&gt; {
  try {
    const [users] = await db
      .promise()
      .query('SELECT id, name, email, phone, role, createdAt FROM users ORDER BY createdAt DESC');
    res.json({ users });
  } catch (err) {
    next(err);
  }
});

// ----------------------
// ORDERS (admin listing)
// ----------------------

router.get('/orders', async (req, res, next) =&gt; {
  try {
    const [orders] = await db
      .promise()
      .query(
        'SELECT o.*, u.email AS userEmail FROM orders o JOIN users u ON o.userId = u.id ORDER BY o.createdAt DESC'
      );
    res.json({ orders });
  } catch (err) {
    next(err);
  }
});

module.exports = router;