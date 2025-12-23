function configureSecurity(app) {
  // Additional security-related configuration hooks can be added here
  // CORS, trusted proxies, etc.
  app.set('trust proxy', 1);
}

module.exports = {
  configureSecurity,
};