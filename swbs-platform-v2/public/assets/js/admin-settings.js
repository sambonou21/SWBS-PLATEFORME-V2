(function () {
  async function fetchJson(url) {
    const res = await fetch(url);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.error || 'Erreur');
    return data;
  }

  async function patchJson(url, body) {
    const res = await fetch(url, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.error || 'Erreur');
    return data;
  }

  document.addEventListener('DOMContentLoaded', async () => {
    if (document.body.getAttribute('data-page') !== 'admin-settings') return;

    const form = document.getElementById('admin-settings-form');
    const msgEl = document.getElementById('admin-settings-message');
    if (!form || !msgEl) return;

    try {
      const data = await fetchJson('/api/admin/settings');
      const s = data.settings || {};
      form.presenceAdmin.checked = !!s.presenceAdmin;

      let rates = { FCFA: 1, NGN: 1, USD: 1, EUR: 1 };
      if (s.currencyRates) {
        try {
          rates = Object.assign(rates, JSON.parse(s.currencyRates));
        } catch (_) {}
      }
      form.rate_FCFA.value = rates.FCFA;
      form.rate_NGN.value = rates.NGN;
      form.rate_USD.value = rates.USD;
      form.rate_EUR.value = rates.EUR;

      let fedapay = {};
      if (s.fedapayKeys) {
        try {
          fedapay = JSON.parse(s.fedapayKeys);
        } catch (_) {}
      }
      form.fedapay_public.value = fedapay.publicKey || '';
      form.fedapay_secret.value = fedapay.secretKey || '';
      form.fedapay_mode.value = fedapay.mode || 'sandbox';

      let ai = {};
      if (s.aiKeys) {
        try {
          ai = JSON.parse(s.aiKeys);
        } catch (_) {}
      }
      form.ai_enabled.checked = !!ai.enabled;
      form.ai_instructions.value = ai.instructions || '';
    } catch (err) {
      msgEl.textContent = 'Erreur de chargement des paramètres.';
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      msgEl.textContent = '';
      const currencyRates = {
        FCFA: Number(form.rate_FCFA.value || 1),
        NGN: Number(form.rate_NGN.value || 1),
        USD: Number(form.rate_USD.value || 1),
        EUR: Number(form.rate_EUR.value || 1),
      };
      const fedapayKeys = {
        publicKey: form.fedapay_public.value || '',
        secretKey: form.fedapay_secret.value || '',
        mode: form.fedapay_mode.value || 'sandbox',
      };
      const aiKeys = {
        enabled: form.ai_enabled.checked,
        instructions: form.ai_instructions.value || '',
      };

      const payload = {
        presenceAdmin: form.presenceAdmin.checked,
        currencyRates,
        fedapayKeys,
        aiKeys,
      };

      try {
        await patchJson('/api/admin/settings', payload);
        msgEl.textContent = 'Paramètres enregistrés.';
      } catch (err) {
        msgEl.textContent = err.message;
      }
    });
  });
})();