(function () {
  async function fetchJson(url) {
    const res = await fetch(url);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.error || 'Erreur');
    return data;
  }

  async function loadOrders() {
    const tbody = document.querySelector('#admin-orders-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';
    try {
      const data = await fetchJson('/api/admin/orders');
      (data.orders || []).forEach((o) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          &lt;td&gt;${o.id}&lt;/td&gt;
          &lt;td&gt;${o.userEmail || o.userId}&lt;/td&gt;
          &lt;td&gt;${o.totalFcfa}&lt;/td&gt;
          &lt;td&gt;${o.currency}&lt;/td&gt;
          &lt;td&gt;${o.status}&lt;/td&gt;
          &lt;td&gt;${o.paymentRef || ''}&lt;/td&gt;
        `;
        tbody.appendChild(tr);
      });
    } catch (err) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 6;
      td.textContent = 'Erreur de chargement';
      tr.appendChild(td);
      tbody.appendChild(tr);
    }
  }

  document.addEventListener('DOMContentLoaded', () =&gt; {
    if (document.body.getAttribute('data-page') !== 'admin-orders') return;
    loadOrders();
  });
})();