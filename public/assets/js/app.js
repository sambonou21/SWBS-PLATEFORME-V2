document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger-button');
    const nav = document.getElementById('main-nav');

    if (burger && nav) {
        burger.addEventListener('click', () => {
            burger.classList.toggle('is-open');
            nav.classList.toggle('is-open');
        });
    }

    // Thème jour/nuit
    const THEME_KEY = 'swbs_theme';
    const body = document.body;
    const themeToggle = document.getElementById('swbs-theme-toggle');
    const themeLabel = themeToggle ? themeToggle.querySelector('[data-theme-label]') : null;

    function applyTheme(theme) {
        if (theme === 'light') {
            body.classList.remove('swbs-theme-dark');
            body.classList.add('swbs-theme-light');
            body.dataset.theme = 'light';
            if (themeLabel) themeLabel.textContent = 'Mode clair';
        } else {
            body.classList.remove('swbs-theme-light');
            body.classList.add('swbs-theme-dark');
            body.dataset.theme = 'dark';
            if (themeLabel) themeLabel.textContent = 'Mode sombre';
        }
    }

    const savedTheme = localStorage.getItem(THEME_KEY);
    if (savedTheme === 'light' || savedTheme === 'dark') {
        applyTheme(savedTheme);
    } else {
        applyTheme('dark');
    }

    // Bouton langue flottant
    const langToggle = document.getElementById('swbs-lang-toggle');
    const langInput = document.getElementById('swbs-lang-input');
    const langForm = document.getElementById('swbs-lang-form');

    if (langToggle && langInput && langForm) {
        langToggle.addEventListener('click', () => {
            const current = (langInput.value || 'fr').toLowerCase();
            const next = current === 'fr' ? 'en' : 'fr';
            langInput.value = next;
            langForm.submit();
        });
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = body.dataset.theme === 'light' ? 'light' : 'dark';
            const next = current === 'light' ? 'dark' : 'light';
            localStorage.setItem(THEME_KEY, next);
            applyTheme(next);
        });
    }

    // Panier en localStorage
    const CART_KEY = 'swbs_cart';
    const cartButton = document.getElementById('swbs-cart-button');
    const cartCountEl = document.getElementById('swbs-cart-count');
    const cartOverlay = document.getElementById('swbs-cart-overlay');
    const cartPanel = document.getElementById('swbs-cart-panel');
    const cartItemsEl = document.getElementById('swbs-cart-items');
    const cartCloseBtn = document.getElementById('swbs-cart-close');

    function getCart() {
        try {
            const raw = localStorage.getItem(CART_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    function renderCart() {
        if (!cartItemsEl || !cartCountEl) return;
        const cart = getCart();
        const count = cart.reduce((sum, item) => sum + (item.qty || 1), 0);
        cartCountEl.textContent = count.toString();

        cartItemsEl.innerHTML = '';
        if (cart.length === 0) {
            const p = document.createElement('p');
            p.textContent = 'Votre panier est vide.';
            cartItemsEl.appendChild(p);
            return;
        }

        cart.forEach(item => {
            const row = document.createElement('div');
            row.classList.add('swbs-cart-item');

            const left = document.createElement('div');
            left.classList.add('swbs-cart-item-name');
            left.textContent = item.name;

            const right = document.createElement('div');
            right.classList.add('swbs-cart-item-meta');
            right.textContent = `${item.qty || 1} × ${item.price} ${item.currency}`;

            row.appendChild(left);
            row.appendChild(right);
            cartItemsEl.appendChild(row);
        });
    }

    function addToCart(item) {
        const cart = getCart();
        const existing = cart.find(c => c.id === item.id && c.currency === item.currency);
        if (existing) {
            existing.qty = (existing.qty || 1) + 1;
        } else {
            cart.push({ ...item, qty: 1 });
        }
        saveCart(cart);
        renderCart();
    }

    function openCart() {
        if (!cartPanel || !cartOverlay) return;
        renderCart();
        cartPanel.hidden = false;
        cartOverlay.hidden = false;
    }

    function closeCart() {
        if (!cartPanel || !cartOverlay) return;
        cartPanel.hidden = true;
        cartOverlay.hidden = true;
    }

    if (cartButton) {
        cartButton.addEventListener('click', openCart);
    }
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCart);
    }
    if (cartCloseBtn) {
        cartCloseBtn.addEventListener('click', closeCart);
    }

    document.querySelectorAll('.js-add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: parseFloat(btn.dataset.price),
                currency: btn.dataset.currency,
            };
            addToCart(item);
        });
    });

    // Initial render
    renderCart();

    // Chat widget (fallback HTTP)
    const widget = document.getElementById('swbs-chat-widget');
    if (widget) {
        const toggleBtn = document.getElementById('swbs-chat-toggle');
        const closeBtn = document.getElementById('swbs-chat-close');
        const windowEl = document.getElementById('swbs-chat-window');
        const messagesEl = document.getElementById('swbs-chat-messages');
        const formEl = document.getElementById('swbs-chat-form');
        const inputEl = document.getElementById('swbs-chat-input');
        const conversationInput = document.getElementById('swbs-chat-conversation-id');
        const startUrl = widget.dataset.startUrl;
        const sendUrl = widget.dataset.sendUrl;
        const fetchBaseUrl = widget.dataset.fetchBaseUrl || null;

        let lastMessageId = null;
        let pollIntervalId = null;

        function openChat() {
            windowEl.classList.add('is-open');
            toggleBtn.style.display = 'none';
            if (!conversationInput.value) {
                startConversation();
            } else if (fetchBaseUrl && !pollIntervalId) {
                startPolling();
            }
        }

        function closeChat() {
            windowEl.classList.remove('is-open');
            toggleBtn.style.display = 'inline-flex';
        }

        toggleBtn.addEventListener('click', openChat);
        closeBtn.addEventListener('click', closeChat);

        function appendMessage(content, type) {
            const div = document.createElement('div');
            div.classList.add('swbs-chat-message');
            if (type === 'user' || type === 'guest') {
                div.classList.add('swbs-chat-message-user');
            } else if (type === 'ai') {
                div.classList.add('swbs-chat-message-ai');
            } else {
                div.classList.add('swbs-chat-message-admin');
            }
            div.textContent = content;
            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function syncMessages(messages) {
            messages.forEach(msg => {
                if (!lastMessageId || msg.id > lastMessageId) {
                    const type = msg.sender_type || 'admin';
                    appendMessage(msg.content, type);
                    lastMessageId = msg.id;
                }
            });
        }

        function startConversation() {
            const params = new URLSearchParams();

            const nameInput = document.getElementById('swbs-chat-name');
            const emailInput = document.getElementById('swbs-chat-email');
            const phoneInput = document.getElementById('swbs-chat-phone');

            if (nameInput && nameInput.value.trim()) {
                params.append('name', nameInput.value.trim());
            }
            if (emailInput && emailInput.value.trim()) {
                params.append('email', emailInput.value.trim());
            }
            if (phoneInput && phoneInput.value.trim()) {
                params.append('phone', phoneInput.value.trim());
            }

            fetch(startUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: params
            })
                .then(r => r.json())
                .then(data => {
                    conversationInput.value = data.conversation_id;
                    if (fetchBaseUrl && !pollIntervalId) {
                        startPolling();
                    }
                })
                .catch(() => {
                    // ignore
                });
        }

        function pollOnce() {
            const conversationId = conversationInput.value;
            if (!conversationId || !fetchBaseUrl) {
                return;
            }

            fetch(fetchBaseUrl.replace('__ID__', conversationId), {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(r => r.json())
                .then(data => {
                    if (Array.isArray(data.messages)) {
                        syncMessages(data.messages);
                    }
                })
                .catch(() => {
                    // ignore polling errors
                });
        }

        function startPolling() {
            pollOnce();
            pollIntervalId = window.setInterval(pollOnce, 4000);
        }

        // Envoi du message avec le formulaire
        formEl.addEventListener('submit', e => {
            e.preventDefault();
            const message = inputEl.value.trim();
            if (!message) {
                return;
            }

            appendMessage(message, 'user');
            inputEl.value = '';

            const conversationId = conversationInput.value;
            if (!conversationId) {
                startConversation();
            }

            const params = new URLSearchParams();
            params.append('conversation_id', conversationInput.value);
            params.append('message', message);

            fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: params
            })
                .then(r => r.json())
                .then(data => {
                    if (data.message) {
                        const type = data.message.sender_type || 'admin';
                        if (type !== 'user' && type !== 'guest') {
                            appendMessage(data.message.content, type);
                        }
                        if (!lastMessageId || data.message.id > lastMessageId) {
                            lastMessageId = data.message.id;
                        }
                    }
                })
                .catch(() => {
                    // ignore
                });
        });

        // Envoi avec la touche Entrée (sans Shift)
        inputEl.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                formEl.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        });
    }
});