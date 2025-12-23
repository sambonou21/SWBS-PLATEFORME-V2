(function () {
  const STORAGE_KEY = 'swbs_currency';
  const defaultCurrency = 'FCFA';

  function getCurrency() {
    try {
      const v = localStorage.getItem(STORAGE_KEY);
      if (v) return v;
    } catch (_) {}
    return defaultCurrency;
  }

  function setCurrency(cur) {
    try {
      localStorage.setItem(STORAGE_KEY, cur);
    } catch (_) {}
  }

  document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('currency-switcher');
    const cur = getCurrency();
    if (select) {
      select.value = cur;
      select.addEventListener('change', (e) => {
        setCurrency(e.target.value);
        window.dispatchEvent(new CustomEvent('swbs:currency-change', { detail: e.target.value }));
      });
    }
  });
})();