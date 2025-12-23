(function () {
  const FORM_KEY = 'swbs_quote_draft';

  function saveDraft(data) {
    try {
      localStorage.setItem(FORM_KEY, JSON.stringify(data));
    } catch (_) {}
  }

  function loadDraft() {
    try {
      const raw = localStorage.getItem(FORM_KEY);
      if (!raw) return null;
      return JSON.parse(raw);
    } catch (_) {
      return null;
    }
  }

  function clearDraft() {
    try {
      localStorage.removeItem(FORM_KEY);
    } catch (_) {}
  }

  async function postQuote(data) {
    const res = await fetch('/api/quotes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': window.SWBS_CSRF || '',
      },
      body: JSON.stringify(data),
    });
    if (!res.ok) {
      const payload = await res.json().catch(() => ({}));
      throw new Error(payload.error || 'Erreur lors de la création du devis');
    }
    return res.json();
  }

  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('quote-form');
    const msgEl = document.getElementById('quote-message');

    if (!form) return;

    const draft = loadDraft();
    if (draft) {
      if (draft.serviceId) form.serviceId.value = draft.serviceId;
      if (draft.details) form.details.value = draft.details;
      if (draft.budget) form.budget.value = draft.budget;
      if (draft.deadline) form.deadline.value = draft.deadline;
    }

    form.addEventListener('input', () => {
      saveDraft({
        serviceId: form.serviceId.value || null,
        details: form.details.value || '',
        budget: form.budget.value || '',
        deadline: form.deadline.value || '',
      });
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      msgEl.textContent = '';

      const payload = {
        serviceId: form.serviceId.value ? Number(form.serviceId.value) : null,
        details: form.details.value.trim(),
        budget: form.budget.value.trim(),
        deadline: form.deadline.value.trim(),
      };

      try {
        const res = await postQuote(payload);
        clearDraft();
        form.reset();
        msgEl.textContent = 'Devis envoyé avec succès.';
      } catch (err) {
        if (err.message && err.message.includes('401')) {
          saveDraft(payload);
          window.location.href = '/register?next=/devis';
          return;
        }
        msgEl.textContent = err.message || 'Erreur lors de l’envoi.';
      }
    });
  });
})();