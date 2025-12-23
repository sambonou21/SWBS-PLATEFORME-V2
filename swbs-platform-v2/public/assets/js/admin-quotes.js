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

  async function loadQuotes() {
    const tbody = document.querySelector('#admin-quotes-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';
    try {
      const data = await fetchJson('/api/quotes/admin');
      (data.quotes || []).forEach((q) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          &lt;td&gt;${q.id}&lt;/td&gt;
          &lt;td&gt;${q.userName || q.userId}&lt;/td&gt;
          &lt;td&gt;
            &lt;select data-id="${q.id}" class="quote-status"&gt;
              &lt;option value="recu"${q.status === 'recu' ? ' selected' : ''}&gt;Reçu&lt;/option&gt;
              &lt;option value="en_cours"${q.status === 'en_cours' ? ' selected' : ''}&gt;En cours&lt;/option&gt;
              &lt;option value="valide"${q.status === 'valide' ? ' selected' : ''}&gt;Validé&lt;/option&gt;
              &lt;option value="refuse"${q.status === 'refuse' ? ' selected' : ''}&gt;Refusé&lt;/option&gt;
            &lt;/select&gt;
          &lt;/td&gt;
          &lt;td&gt;${q.createdAt}&lt;/td&gt;
          &lt;td&gt;&lt;button data-payload='${JSON.stringify(q.payload)}' class="btn-secondary btn-sm"&gt;Voir&lt;/button&gt;&lt;/td&gt;
        `;
        tbody.appendChild(tr);
      });

      tbody.addEventListener('change', async (e) =&gt; {
        if (e.target.classList.contains('quote-status')) {
          const id = e.target.getAttribute('data-id');
          const status = e.target.value;
          try {
            await patchJson(`/api/quotes/admin/${id}/status`, { status });
          } catch (err) {
            alert('Erreur mise à jour statut');
          }
        }
      });
    } catch (err) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.textContent = 'Erreur de chargement';
      tr.appendChild(td);
      tbody.appendChild(tr);
    }
  }

  document.addEventListener('DOMContentLoaded', () =&gt; {
    if (document.body.getAttribute('data-page') !== 'admin-quotes') return;
    loadQuotes();
  });
})();