const express = require('express');
const Joi = require('joi');

const validate = require('../middlewares/validate');
const orderService = require('../services/orderService');

const router = express.Router();

// Endpoint webhook pour FEDEPAY (simplifié, sécurisation à renforcer selon doc officielle)
const webhookSchema = Joi.object({
  orderId: Joi.number().integer().positive().required(),
  status: Joi.string().valid('paid', 'cancelled').required(),
  paymentRef: Joi.string().max(191).required(),
  signature: Joi.string().optional(), // TODO: vérifier signature selon FEDEPAY
});

router.post(
  '/fedapay',
  validate(webhookSchema),
  async (req, res, next) =&gt; {
    try {
      const mapStatus = req.body.status === 'paid' ? 'paid' : 'cancelled';
      await orderService.updateOrderStatus(
        req.body.orderId,
        mapStatus,
        req.body.paymentRef
      );
      res.json({ ok: true });
    } catch (err) {
      next(err);
    }
  }
);

module.exports = router;