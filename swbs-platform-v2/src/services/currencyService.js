const settingsService = require('./settingsService');

const SUPPORTED = ['FCFA', 'NGN', 'USD', 'EUR'];

async function getRates() {
  const settings = await settingsService.getSettings();
  let rates = { FCFA: 1, NGN: 1, USD: 1, EUR: 1 };
  if (settings.currencyRates) {
    try {
      const parsed = JSON.parse(settings.currencyRates);
      rates = { ...rates, ...parsed };
    } catch (err) {
      // ignore parse errors, keep defaults
    }
  }
  return rates;
}

async function convertFromFcfa(amountFcfa, toCurrency) {
  const rates = await getRates();
  const cur = SUPPORTED.includes(toCurrency) ? toCurrency : 'FCFA';
  const rate = rates[cur] || 1;
  return amountFcfa * rate;
}

module.exports = {
  SUPPORTED,
  getRates,
  convertFromFcfa,
};