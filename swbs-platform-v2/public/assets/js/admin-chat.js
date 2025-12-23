(function () {
  let socket;
  let currentConversationId = null;

  function appendMessage(msg) {
    const box = document.getElementById('admin-chat-messages');
    if (!box) return;
    const p = document.createElement('p');
    p.textContent = `[${msg.senderType}] ${msg.content}`;
    box.appendChild(p);
    box.scrollTop = box.scrollHeight;
  }

  async function loadConversations() {
    const res = await fetch('/api/chat/admin/conversations');
    const data = await res.json();
    const list = document.getElementById('admin-conversations');
    if (!list) return;
    list.innerHTML = '';
    (data.conversations || []).forEach((c) => {
      const li = document.createElement('li');
      li.textContent =
        'Conv #' + c.id + ' - ' + (c.leadEmail || c.leadName || c.userId || '');
      li.style.cursor = 'pointer';
      li.addEventListener('click', () => {
        selectConversation(c.id);
      });
      list.appendChild(li);
    });
  }

  async function selectConversation(id) {
    currentConversationId = id;
    const box = document.getElementById('admin-chat-messages');
    if (box) {
      box.innerHTML = '';
    }
    const res = await fetch(`/api/chat/${id}/messages`);
    const data = await res.json();
    (data.messages || []).forEach(appendMessage);
    if (socket) {
      socket.emit('join', { conversationId: id, role: 'admin' });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (document.body.getAttribute('data-page') !== 'admin-chat') return;

    socket = window.io();
    socket.emit('join', { role: 'admin' });

    socket.on('chat:conversation:update', () => {
      loadConversations();
    });

    socket.on('chat:message', (msg) => {
      if (msg.conversationId === currentConversationId) {
        appendMessage(msg);
      }
    });

    loadConversations();

    const form = document.getElementById('admin-chat-form');
    const input = document.getElementById('admin-chat-input');
    if (form && input) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!currentConversationId) return;
        const content = input.value.trim();
        if (!content) return;
        socket.emit(
          'chat:message',
          {
            conversationId: currentConversationId,
            senderType: 'admin',
            content,
          },
          (ack) => {
            if (ack && ack.ok) {
              appendMessage({ senderType: 'admin', content });
              input.value = '';
            }
          }
        );
      });
    }
  });
})();