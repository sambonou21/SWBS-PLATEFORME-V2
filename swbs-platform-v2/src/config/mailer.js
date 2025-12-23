const nodemailer = require('nodemailer');

const transporter = nodemailer.createTransport({
  host: process.env.SMTP_HOST,
  port: process.env.SMTP_PORT ? parseInt(process.env.SMTP_PORT, 10) : 587,
  secure: false,
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