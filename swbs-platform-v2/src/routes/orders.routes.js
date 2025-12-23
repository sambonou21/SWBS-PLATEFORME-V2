const express = require('express');
const Joi = require('joi');

const requireAuth = require('../middlewares/requireAuth');
const validate = require('../middlewares/validate');
const orderService = require('../services/orderService');
const productService = require('../services/productService');

const router = express.Router();

const orderSchema = Joi.object({
  currency: Joi.string().valid('FCFA', 'NGN', 'USD', 'EUR').required(),
  items: Joi.array()
    .items(
      Joi.object({
        productId: Joi.number().integer().positive().required(),
        qty: Joi.number().integer().min(1).required(),
      })
    )
    .min(1)
    .required(),
});

// Créer une commande (avant paiement FEDEPAY)
router.post('/', requireAuth, validate(orderSchema), async (req, res, next) =&gt; {
  try {
    const products = await productService.listPublicProducts();
    const productMap = new Map(products.map((p) =&gt; [p.id, p]));

    let totalFcfa = 0;
    const items = [];

    for (const item of req.body.items) {
      const product = productMap.get(item.productId);
      if (!product) {
        return res.status(400).json({ error: 'Invalid product in cart' });
      }
      const priceFcfa = Number(product.priceFcfa);
      totalFcfa += priceFcfa * item.qty;
      items.push({
        productId: item.productId,
        qty: item.qty,
        priceFcfa,
      });
    }

    const order = await orderService.createOrder({
      userId: req.session.user.id,
      currency: req.body.currency,
      totalFcfa,
      items,
      paymentProvider: 'fedapay',
      paymentRef: null,
    });

    res.status(201).json({ order });
  } catch (err) {
    next(err);
  }
});

module.exports = router;