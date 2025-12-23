const { sendMail } = require('../config/mailer');

function buildVerificationEmailHtml({ name, token }) {
  const domain = process.env.DOMAIN || 'http://localhost:' + (process.env.PORT || 3000);
  const verifyUrl = `${domain}/verify-email?token=${encodeURIComponent(token)}`;

  return `
  &lt;!doctype html&gt;
  &lt;html lang="fr"&gt;
  &lt;head&gt;
    &lt;meta charset="utf-8" /&gt;
    &lt;title&gt;Vérification de votre email - SWBS&lt;/title&gt;
    &lt;style&gt;
      body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background:#0b1020; color:#fff; padding:40px; }
      .card { max-width:520px; margin:0 auto; background:#151a30; border-radius:16px; padding:32px; border:1px solid #1e66ff33; }
      .logo { font-weight:700; font-size:20px; color:#1E66FF; margin-bottom:24px; }
      .btn { display:inline-block; margin-top:24px; padding:12px 24px; border-radius:999px; background:#1E66FF; color:#fff; text-decoration:none; font-weight:600; }
      .btn:hover { background:#365dff; }
      .muted { font-size:13px; color:#bcc4e5; margin-top:24px; line-height:1.5; }
    &lt;/style&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;div class="card"&gt;
      &lt;div class="logo"&gt;SWBS PLATFORM&lt;/div&gt;
      &lt;h1&gt;Confirmez votre adresse email&lt;/h1&gt;
      &lt;p&gt;Bonjour ${name || ''},&lt;/p&gt;
      &lt;p&gt;Merci de votre inscription sur SWBS. Pour activer votre compte, cliquez sur le bouton ci-dessous.&lt;/p&gt;
      &lt;p&gt;
        &lt;a href="${verifyUrl}" class="btn"&gt;Vérifier mon email&lt;/a&gt;
      &lt;/p&gt;
      &lt;p class="muted"&gt;
        Ce lien est valable 24 heures. Si vous n'êtes pas à l'origine de cette action, vous pouvez ignorer cet email.
      &lt;/p&gt;
    &lt;/div&gt;
  &lt;/body&gt;
  &lt;/html&gt;
  `;
}

async function sendVerificationEmail(user) {
  const html = buildVerificationEmailHtml({
    name: user.name,
    token: user.verifyToken,
  });

  await sendMail({
    to: user.email,
    subject: 'Vérification de votre email - SWBS',
    html,
  });
}

module.exports = {
  sendVerificationEmail,
};