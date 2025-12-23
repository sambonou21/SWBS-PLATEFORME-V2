function requireAuth(req, res, next) {
  if (!req.session || !req.session.user) {
    if (req.accepts('json')) {
      return res.status(401).json({ error: 'Authentication required' });
    }
    return res.redirect('/login');
  }
  next();
}

module.exports = requireAuth;