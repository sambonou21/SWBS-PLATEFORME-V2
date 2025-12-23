function getAiConfig() {
  return {
    provider: process.env.AI_PROVIDER || null,
    apiKey: process.env.AI_API_KEY || null,
  };
}

module.exports = {
  getAiConfig,
};