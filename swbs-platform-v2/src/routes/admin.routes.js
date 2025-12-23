const express = require('express');
const Joi = require('joi');

const requireAuth = require('../middlewares/requireAuth');
const requireAdmin = require('../middlewares/requireAdmin');
const validate = require('../middlewares/validate');
const settingsService = require('../services/settingsService');

const router = express.Router();

router.use(requireAuth, requireAdmin);

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

module.exports = router;