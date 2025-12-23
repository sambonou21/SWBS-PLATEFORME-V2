const nodemailer = require('nodemailer');

const port = process.env.SMTP_PORT ? parseInt(process.env.SMTP_PORT, 10) : 587;
// Sur o2switch, SMTP_SECURE=true pour le port 465
const secure =
  String(process.env.SMTP_SECURE || '').toLowerCase() === 'true' ||
  port === 465;

const transporter = nodemailer.createTransport({
  host: process.env.SMTP_HOST,
  port,
  secure,
  auth: {
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASS,
  },
});

async function sendMail({ to, subject, html }) {
  if (!to) {
    throw new Error('Missing "to" for email');
  }
  const from = process.env.MAIL_FROM || process.env.SMTP_USER;
  return transporter.sendMail({ from, to, subject, html });
}

module.exports = {
  transporter,
  sendMail,
};