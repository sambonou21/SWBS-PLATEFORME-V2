(function () {
  const STORAGE_KEY = 'swbs_lang';
  const defaultLang = 'fr';

  function getCurrentLang() {
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (stored === 'fr' || stored === 'en') return stored;
    } catch (_) {}
    return defaultLang;
  }

  function setCurrentLang(lang) {
    try {
      localStorage.setItem(STORAGE_KEY, lang);
    } catch (_) {}
  }

  async function loadDictionary(lang) {
    const res = await fetch(`/i18n/${lang}.json`, { cache: 'no-cache' });
    if (!res.ok) throw new Error('Unable to load i18n file');
    return res.json();
  }

  async function applyTranslations(lang) {
    try {
      const dict = await loadDictionary(lang);
      document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        const value = key.split('.').reduce((acc, part) => (acc ? acc[part] : undefined), dict);
        if (value) {
          el.textContent = value;
        }
      });
    } catch (e) {
      console.error(e);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const lang = getCurrentLang();
    const select = document.getElementById('lang-switcher');
    if (select) {
      select.value = lang;
      select.addEventListener('change', (e) => {
        const nextLang = e.target.value;
        setCurrentLang(nextLang);
        applyTranslations(nextLang);
      });
    }
    applyTranslations(lang);
  });
})();