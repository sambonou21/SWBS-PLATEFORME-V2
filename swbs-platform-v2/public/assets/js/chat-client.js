(function () {
  let socket;
  let conversationId = null;

  function appendMessage(sender, content) {
    const list = document.getElementById('chat-messages');
    if (!list) return;
    const div = document.createElement('div');
    div.textContent = sender + ': ' + content;
    list.appendChild(div);
    list.scrollTop = list.scrollHeight;
  }

  async function ensureConversation() {
    if (conversationId) return conversationId;
    const res = await fetch('/api/chat/start', { method: 'POST' });
    const data = await res.json();
    conversationId = data.conversation.id;
    return conversationId;
  }

  document.addEventListener('DOMContentLoaded', async () => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    if (!form || !input) return;

    await ensureConversation();

    socket = window.io();
    socket.emit('join', { conversationId, role: 'user' });

    socket.on('chat:message', (msg) => {
      if (msg.conversationId !== conversationId) return;
      appendMessage(msg.senderType, msg.content);
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const content = input.value.trim();
      if (!content) return;

      const currentConvId = await ensureConversation();

      socket.emit(
        'chat:message',
        { conversationId: currentConvId, senderType: 'user', content },
        (ack) => {
          if (!ack || !ack.ok) {
            appendMessage('system', 'Erreur lors de l’envoi du message.');
          } else {
            appendMessage('vous', content);
            input.value = '';
          }
        }
      );
    });
  });
})();