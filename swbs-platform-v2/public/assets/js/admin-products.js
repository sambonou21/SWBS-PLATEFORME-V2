(function () {
  async function fetchJson(url) {
    const res = await fetch(url);
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      throw new Error(data.error || 'Erreur');
    }
    return data;
  }

  async function refreshProducts() {
    const tableBody = document.querySelector('#admin-products-table tbody');
    if (!tableBody) return;
    tableBody.innerHTML = '';
    try {
      const data = await fetchJson('/api/products/admin/list');
      (data.products || []).forEach((p) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${p.id}</td><td>${p.title}</td><td>${p.priceFcfa}</td><td>${p.stock}</td><td>${p.status}</td>`;
        tableBody.appendChild(tr);
      });
    } catch (err) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.textContent = 'Erreur de chargement';
      tr.appendChild(td);
      tableBody.appendChild(tr);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (document.body.getAttribute('data-page') !== 'admin-products') return;

    const form = document.getElementById('admin-product-form');
    const msgEl = document.getElementById('admin-product-message');

    if (form) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        msgEl.textContent = '';
        const fd = new FormData(form);
        try {
          const res = await fetch('/api/products/admin', {
            method: 'POST',
            body: fd,
          });
          const data = await res.json().catch(() => ({}));
          if (!res.ok) {
            throw new Error(data.error || 'Erreur lors de la création');
          }
          msgEl.textContent = 'Produit créé.';
          form.reset();
          refreshProducts();
        } catch (err) {
          msgEl.textContent = err.message;
        }
      });
    }

    refreshProducts();
  });
})();