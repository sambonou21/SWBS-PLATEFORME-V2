const settingsService = require('./settingsService');
const { getAiConfig } = require('../config/ai');

// Placeholder simple IA proxy; implementation peut être étendue selon le provider.
async function generateAiReply({ conversation, messages }) {
  const aiConfig = getAiConfig();
  if (!aiConfig.provider || !aiConfig.apiKey) {
    return null;
  }

  const settings = await settingsService.getSettings();
  let instructions = '';
  if (settings.aiKeys) {
    try {
      const cfg = JSON.parse(settings.aiKeys);
      instructions = cfg.instructions || '';
    } catch (err) {
      // ignore
    }
  }

  const lastMessage = messages[messages.length - 1];
  const content = lastMessage ? lastMessage.content : '';

  // Règle : si aucune instruction explicite, IA ne parle que de SWBS
  const scope = instructions
    ? instructions
    : "Tu es l'assistant SWBS. Tu ne parles que de SWBS, de ses services, de son portfolio et de sa boutique.";

  return `${scope} | Dernier message client: ${content.slice(0, 200)}`;
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