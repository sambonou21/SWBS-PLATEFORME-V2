function errorHandler(err, req, res, next) {
  console.error(err);

  const status = err.status || 500;
  const message = err.message || 'Internal server error';

  if (req.accepts('json')) {
    return res.status(status).json({ error: message });
  }

  return res.status(status).send(message);
}

module.exports = {
  errorHandler,
};