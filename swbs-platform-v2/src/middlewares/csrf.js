function csrfMiddleware(req, res, next) {
  res.locals.csrfToken = req.csrfToken ? req.csrfToken() : null;
  next();
}

module.exports = {
  csrfMiddleware,
};