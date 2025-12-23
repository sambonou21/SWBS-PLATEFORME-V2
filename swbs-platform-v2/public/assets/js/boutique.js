(function () {
  const CART_KEY = 'swbs_cart';

  function loadCart() {
    try {
      const raw = localStorage.getItem(CART_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (_) {
      return [];
    }
  }

  function saveCart(cart) {
    try {
      localStorage.setItem(CART_KEY, JSON.stringify(cart));
    } catch (_) {}
  }

  function addToCart(productId, qty) {
    const cart = loadCart();
    const existing = cart.find((i) =&gt; i.productId === productId);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ productId, qty });
    }
    saveCart(cart);
  }

  async function fetchJson(url) {
    const res = await fetch(url);
    if (!res.ok) throw new Error('Erreur');
    return res.json();
  }

  function renderProductList(products) {
    const container = document.getElementById('shop-products');
    if (!container) return;
    container.innerHTML = '';
    if (!products.length) {
      container.textContent = 'Aucun produit pour le moment.';
      return;
    }
    const grid = document.createElement('div');
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = 'repeat(auto-fill,minmax(220px,1fr))';
    grid.style.gap = '1rem';
    products.forEach((p) =&gt; {
      const card = document.createElement('div');
      card.className = 'card';
      const title = document.createElement('h3');
      title.textContent = p.title;
      const price = document.createElement('p');
      price.textContent = p.priceFcfa + ' FCFA';
      const btnView = document.createElement('a');
      btnView.href = '/product.html?slug=' + encodeURIComponent(p.slug);
      btnView.textContent = 'Voir';
      btnView.className = 'btn-secondary';
      const btnAdd = document.createElement('button');
      btnAdd.textContent = 'Ajouter au panier';
      btnAdd.className = 'btn-primary';
      btnAdd.style.marginLeft = '0.5rem';
      btnAdd.addEventListener('click', () =&gt; {
        addToCart(p.id, 1);
        alert('Ajouté au panier');
      });
      card.appendChild(title);
      card.appendChild(price);
      card.appendChild(btnView);
      card.appendChild(btnAdd);
      grid.appendChild(card);
    });
    container.appendChild(grid);
  }

  function renderProductDetail(product) {
    const container = document.getElementById('product-details');
    if (!container) return;
    container.innerHTML = '';
    const title = document.createElement('h1');
    title.textContent = product.title;
    const desc = document.createElement('p');
    desc.textContent = product.description;
    const price = document.createElement('p');
    price.textContent = product.priceFcfa + ' FCFA';
    const btnAdd = document.createElement('button');
    btnAdd.textContent = 'Ajouter au panier';
    btnAdd.className = 'btn-primary';
    btnAdd.addEventListener('click', () =&gt; {
      addToCart(product.id, 1);
      alert('Ajouté au panier');
    });
    container.appendChild(title);
    container.appendChild(desc);
    container.appendChild(price);
    container.appendChild(btnAdd);
  }

  async function initListPage() {
    const page = document.body.getAttribute('data-page');
    if (page !== 'boutique') return;
    try {
      const data = await fetchJson('/api/products');
      renderProductList(data.products || []);
    } catch (err) {
      const container = document.getElementById('shop-products');
      if (container) container.textContent = 'Erreur de chargement.';
    }
  }

  async function initDetailPage() {
    const page = document.body.getAttribute('data-page');
    if (page !== 'product') return;
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('slug');
    if (!slug) return;
    try {
      const data = await fetchJson('/api/products/' + encodeURIComponent(slug));
      renderProductDetail(data.product);
    } catch (err) {
      const container = document.getElementById('product-details');
      if (container) container.textContent = 'Produit introuvable.';
    }
  }

  document.addEventListener('DOMContentLoaded', () =&gt; {
    initListPage();
    initDetailPage();
  });
})();