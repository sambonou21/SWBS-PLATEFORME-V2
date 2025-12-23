const settingsService = require('./settingsService');
const { getAiConfig } = require('../config/ai');

// Placeholder simple IA proxy; implementation can be extended per provider.
async function generateAiReply({ conversation, messages }) {
  const aiConfig = getAiConfig();
  if (!aiConfig.provider || !aiConfig.apiKey) {
    return null;
  }

  // Simple deterministic answer placeholder. In production, call actual AI API here.
  // The rules (ne parler que de SWBS si pas d'instructions, etc.) seront gérées ici,
  // avec la configuration AI stockée dans settings.
  const lastMessage = messages[messages.length - 1];
  const content = lastMessage ? lastMessage.content : '';
  return (
    "Merci pour votre message. Notre assistant SWBS analyse votre demande : " +
    content.slice(0, 200)
  );
}

async function shouldUseAi() {
  const settings = await settingsService.getSettings();
  if (!settings.aiKeys) return false;
  try {
    const cfg = JSON.parse(settings.aiKeys);
    return cfg.enabled === true && settings.presenceAdmin === 0;
  } catch (err) {
    return false;
  }
}

module.exports = {
  generateAiReply,
  shouldUseAi,
};