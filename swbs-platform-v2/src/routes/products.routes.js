const express = require('express');
const Joi = require('joi');
const slugify = require('slugify');

const requireAuth = require('../middlewares/requireAuth');
const requireAdmin = require('../middlewares/requireAdmin');
const validate = require('../middlewares/validate');
const { createUploader } = require('../middlewares/upload');
const productService = require('../services/productService');

const router = express.Router();

const uploader = createUploader('products');

const productSchema = Joi.object({
  categoryId: Joi.number().integer().positive().allow(null),
  title: Joi.string().min(2).max(191).required(),
  description: Joi.string().min(5).required(),
  priceFcfa: Joi.number().positive().required(),
  stock: Joi.number().integer().min(0).required(),
  status: Joi.string().valid('draft', 'published', 'archived').required(),
});

router.get('/', async (req, res, next) =&gt; {
  try {
    const products = await productService.listPublicProducts();
    res.json({ products });
  } catch (err) {
    next(err);
  }
});

router.get('/:slug', async (req, res, next) =&gt; {
  try {
    const product = await productService.getProductBySlug(req.params.slug);
    if (!product) {
      return res.status(404).json({ error: 'Product not found' });
    }
    res.json({ product });
  } catch (err) {
    next(err);
  }
});

// Admin endpoints

router.get('/admin/list', requireAuth, requireAdmin, async (req, res, next) =&gt; {
  try {
    const products = await productService.listAdminProducts();
    const categories = await productService.getCategories();
    res.json({ products, categories });
  } catch (err) {
    next(err);
  }
});

router.post(
  '/admin',
  requireAuth,
  requireAdmin,
  uploader.uploadSingle('image'),
  validate(productSchema),
  async (req, res, next) =&gt; {
    try {
      const slug =
        slugify(req.body.title, { lower: true, strict: true }) +
        '-' +
        Date.now().toString(36);

      const product = await productService.createProduct({
        categoryId: req.body.categoryId || null,
        title: req.body.title,
        slug,
        description: req.body.description,
        priceFcfa: req.body.priceFcfa,
        stock: req.body.stock,
        imagePath: req.file ? req.file.relativePath : null,
        status: req.body.status,
      });
      res.status(201).json({ product });
    } catch (err) {
      next(err);
    }
  }
);

router.put(
  '/admin/:id',
  requireAuth,
  requireAdmin,
  uploader.uploadSingle('image'),
  async (req, res, next) =&gt; {
    try {
      const patch = {};
      if (req.body.title) patch.title = req.body.title;
      if (req.body.description) patch.description = req.body.description;
      if (req.body.priceFcfa) patch.priceFcfa = Number(req.body.priceFcfa);
      if (req.body.stock !== undefined) patch.stock = Number(req.body.stock);
      if (req.body.status) patch.status = req.body.status;
      if (req.body.categoryId) patch.categoryId = Number(req.body.categoryId);
      if (req.file) patch.imagePath = req.file.relativePath;

      const updated = await productService.updateProduct(req.params.id, patch);
      if (!updated) {
        return res.status(404).json({ error: 'Product not found' });
      }
      res.json({ product: updated });
    } catch (err) {
      next(err);
    }
  }
);

router.delete(
  '/admin/:id',
  requireAuth,
  requireAdmin,
  async (req, res, next) =&gt; {
    try {
      await productService.deleteProduct(req.params.id);
      res.json({ ok: true });
    } catch (err) {
      next(err);
    }
  }
);

router.post(
  '/admin/categories',
  requireAuth,
  requireAdmin,
  validate(
    Joi.object({
      name: Joi.string().min(2).max(191).required(),
    })
  ),
  async (req, res, next) =&gt; {
    try {
      const slug = slugify(req.body.name, { lower: true, strict: true });
      const category = await productService.createCategory({
        name: req.body.name,
        slug,
      });
      res.status(201).json({ category });
    } catch (err) {
      next(err);
    }
  }
);

module.exports = router;