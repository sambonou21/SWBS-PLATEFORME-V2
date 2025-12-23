(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const button = document.createElement('button');
    button.textContent = 'Chat';
    button.style.position = 'fixed';
    button.style.right = '16px';
    button.style.bottom = '16px';
    button.style.zIndex = '50';
    button.className = 'btn-primary';

    button.addEventListener('click', () => {
      const url = '/chat?embed=1';
      const w = 420;
      const h = 560;
      const left = window.innerWidth - w - 24;
      const top = window.innerHeight - h - 24;
      window.open(
        url,
        'swbs_chat',
        `width=${w},height=${h},left=${left},top=${top},resizable=yes,scrollbars=yes`
      );
    });

    document.body.appendChild(button);
  });
})();