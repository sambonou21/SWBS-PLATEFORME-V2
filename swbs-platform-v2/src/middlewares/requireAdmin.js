function requireAdmin(req, res, next) {
  if (!req.session || !req.session.user || req.session.user.role !== 'admin') {
    if (req.accepts('json')) {
      return res.status(403).json({ error: 'Admin access required' });
    }
    return res.redirect('/login');
  }
  next();
}

module.exports = requireAdmin;