document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger-button');
    const nav = document.getElementById('main-nav');

    if (burger && nav) {
        burger.addEventListener('click', () => {
            burger.classList.toggle('is-open');
            nav.classList.toggle('is-open');
        });
    }

    // Panier en localStorage
    const CART_KEY = 'swbs_cart';

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

    function addToCart(item) {
        const cart = getCart();
        cart.push(item);
        saveCart(cart);
    }

    document.querySelectorAll('.js-add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: parseFloat(btn.dataset.price),
                currency: btn.dataset.currency,
                qty: 1,
            };
            addToCart(item);
            alert('Produit ajouté au panier.');
        });
    });

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
            fetch(startUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new URLSearchParams()
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