const FEDEPAY_BASE_SANDBOX = 'https://sandbox-api.fedapay.com';
const FEDEPAY_BASE_LIVE = 'https://api.fedapay.com';

function getFedapayConfig() {
  const mode = (process.env.FEDEPAY_MODE || 'sandbox').toLowerCase();
  return {
    publicKey: process.env.FEDEPAY_PUBLIC,
    secretKey: process.env.FEDEPAY_SECRET,
    mode,
    baseUrl: mode === 'live' ? FEDEPAY_BASE_LIVE : FEDEPAY_BASE_SANDBOX,
  };
}

module.exports = {
  getFedapayConfig,
};