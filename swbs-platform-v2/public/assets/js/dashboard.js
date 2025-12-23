(function () {
  async function fetchJson(url) {
    const res = await fetch(url);
    if (!res.ok) throw new Error('Erreur de chargement');
    return res.json();
  }

  function renderQuotes(container, quotes) {
    if (!quotes.length) {
      container.textContent = 'Aucun devis pour le moment.';
      return;
    }
    const ul = document.createElement('ul');
    quotes.forEach((q) => {
      const li = document.createElement('li');
      li.textContent = `#${q.id} - ${q.status} - ${q.createdAt}`;
      ul.appendChild(li);
    });
    container.appendChild(ul);
  }

  function renderMessages(container, conversations, messages) {
    if (!conversations.length) {
      container.textContent = 'Aucun message pour le moment.';
      return;
    }
    const conv = conversations[0];
    const convMessages = messages.filter((m) => m.conversationId === conv.id);
    convMessages.forEach((m) => {
      const p = document.createElement('p');
      p.textContent = `[${m.senderType}] ${m.content}`;
      container.appendChild(p);
    });
  }

  function renderOrders(container, orders) {
    if (!orders.length) {
      container.textContent = 'Aucune commande pour le moment.';
      return;
    }
    const ul = document.createElement('ul');
    orders.forEach((o) => {
      const li = document.createElement('li');
      li.textContent = `Commande #${o.id} - ${o.totalFcfa} FCFA - ${o.currency} - ${o.status}`;
      ul.appendChild(li);
    });
    container.appendChild(ul);
  }

  document.addEventListener('DOMContentLoaded', async () => {
    const quotesEl = document.getElementById('client-quotes');
    const messagesEl = document.getElementById('client-messages');
    const ordersEl = document.getElementById('client-orders');
    if (!quotesEl || !messagesEl || !ordersEl) return;

    try {
      const [quotesData, msgData, ordersData] = await Promise.all([
        fetchJson('/api/client/quotes'),
        fetchJson('/api/client/messages'),
        fetchJson('/api/client/orders'),
      ]);
      renderQuotes(quotesEl, quotesData.quotes || []);
      renderMessages(messagesEl, msgData.conversations || [], msgData.messages || []);
      renderOrders(ordersEl, ordersData.orders || []);
    } catch (err) {
      quotesEl.textContent = 'Erreur lors du chargement.';
      messagesEl.textContent = 'Erreur lors du chargement.';
      ordersEl.textContent = 'Erreur lors du chargement.';
    }
  });
})();