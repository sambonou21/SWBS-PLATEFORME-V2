const db = require('../config/db');

async function getSettings() {
  const [rows] = await db.promise().query('SELECT * FROM settings ORDER BY id ASC LIMIT 1');
  if (rows.length === 0) {
    await db
      .promise()
      .query(
        'INSERT INTO settings (presenceAdmin, currencyRates, fedapayKeys, aiKeys, languageDefault, chatConfig, updatedAt) VALUES (0, NULL, NULL, NULL, ?, NULL, NOW())',
        ['fr']
      );
    return getSettings();
  }
  return rows[0];
}

async function updateSettings(patch) {
  const current = await getSettings();
  const merged = {
    ...current,
    ...patch,
  };

  await db
    .promise()
    .query(
      'UPDATE settings SET presenceAdmin = ?, currencyRates = ?, fedapayKeys = ?, aiKeys = ?, languageDefault = ?, chatConfig = ?, updatedAt = NOW() WHERE id = ?',
      [
        merged.presenceAdmin ? 1 : 0,
        merged.currencyRates || null,
        merged.fedapayKeys || null,
        merged.aiKeys || null,
        merged.languageDefault || 'fr',
        merged.chatConfig || null,
        current.id,
      ]
    );

  return getSettings();
}

module.exports = {
  getSettings,
  updateSettings,
};