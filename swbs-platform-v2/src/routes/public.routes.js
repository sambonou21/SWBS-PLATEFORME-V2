const path = require('path');
const express = require('express');

const router = express.Router();

const publicDir = path.join(__dirname, '..', '..', 'public');

function redirectHtml(req, res, next) {
  if (req.path.endsWith('.html')) {
    const clean = req.path.replace(/\.html$/, '');
    return res.redirect(301, clean === '/index' ? '/' : clean);
  }
  return next();
}

router.use(redirectHtml);

router.get('/', (req, res) => {
  res.sendFile(path.join(publicDir, 'index.html'));
});

router.get('/services', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'services.html'));
});

router.get('/portfolio', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'portfolio.html'));
});

router.get('/contact', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'contact.html'));
});

router.get('/devis', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'devis.html'));
});

router.get('/boutique', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'boutique.html'));
});

router.get('/login', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'login.html'));
});

router.get('/register', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'register.html'));
});

router.get('/verify-email', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'verify-email.html'));
});

router.get('/dashboard', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'dashboard.html'));
});

router.get('/chat', (req, res) => {
  res.sendFile(path.join(publicDir, 'pages', 'chat.html'));
});

router.get('/admin/:page', (req, res) => {
  const allowed = [
    'dashboard',
    'quotes',
    'chat',
    'products',
    'orders',
    'services',
    'portfolio',
    'clients',
    'settings',
  ];
  const { page } = req.params;
  if (!allowed.includes(page)) {
    return res.status(404).send('Not found');
  }
  return res.sendFile(path.join(publicDir, 'pages', 'admin', `${page}.html`));
});

module.exports = router;