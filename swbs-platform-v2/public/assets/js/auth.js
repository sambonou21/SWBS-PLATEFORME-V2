(function () {
  async function postJSON(url, body) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      throw new Error(data.error || 'Erreur');
    }
    return data;
  }

  document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (loginForm) {
      const msg = document.getElementById('login-message');
      loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.textContent = '';
        try {
          const data = await postJSON('/api/auth/login', {
            email: loginForm.email.value,
            password: loginForm.password.value,
          });
          const params = new URLSearchParams(window.location.search);
          const next = params.get('next') || '/dashboard';
          window.location.href = next;
        } catch (err) {
          msg.textContent = err.message;
        }
      });
    }

    if (registerForm) {
      const msg = document.getElementById('register-message');
      registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.textContent = '';
        try {
          const data = await postJSON('/api/auth/register', {
            name: registerForm.name.value,
            email: registerForm.email.value,
            phone: registerForm.phone.value,
            password: registerForm.password.value,
          });
          msg.textContent = data.message || 'Compte créé. Vérifiez votre email.';
          const params = new URLSearchParams(window.location.search);
          const next = params.get('next');
          if (next) {
            // après vérification email, l'utilisateur reviendra se connecter
          }
        } catch (err) {
          msg.textContent = err.message;
        }
      });
    }
  });
})();