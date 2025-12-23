const express = require('express');
const Joi = require('joi');
const bcrypt = require('bcrypt');
const { nanoid } = require('nanoid');

const db = require('../config/db');
const validate = require('../middlewares/validate');
const { sendVerificationEmail } = require('../services/userService');

const router = express.Router();

const registerSchema = Joi.object({
  name: Joi.string().min(2).max(100).required(),
  email: Joi.string().email().required(),
  phone: Joi.string().min(6).max(30).allow('', null),
  password: Joi.string().min(8).max(128).required(),
});

const loginSchema = Joi.object({
  email: Joi.string().email().required(),
  password: Joi.string().min(8).max(128).required(),
});

router.post('/register', validate(registerSchema), async (req, res, next) => {
  const { name, email, phone, password } = req.body;

  try {
    const [rows] = await db
      .promise()
      .query('SELECT id FROM users WHERE email = ?', [email]);
    if (rows.length > 0) {
      return res.status(400).json({ error: 'Email already registered' });
    }

    const passwordHash = await bcrypt.hash(password, 10);
    const verifyToken = nanoid(40);
    const verifyExpires = new Date(Date.now() + 24 * 60 * 60 * 1000);

    const [result] = await db
      .promise()
      .query(
        'INSERT INTO users (name, email, phone, passwordHash, role, emailVerified, verifyToken, verifyExpires, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
        [name, email, phone, passwordHash, 'user', 0, verifyToken, verifyExpires]
      );

    const userId = result.insertId;

    await sendVerificationEmail({
      id: userId,
      name,
      email,
      verifyToken,
    });

    return res.status(201).json({
      message: 'Account created. Please check your email to verify your account.',
    });
  } catch (err) {
    return next(err);
  }
});

router.post('/login', validate(loginSchema), async (req, res, next) => {
  const { email, password } = req.body;

  try {
    const [rows] = await db
      .promise()
      .query('SELECT * FROM users WHERE email = ?', [email]);

    if (rows.length === 0) {
      return res.status(400).json({ error: 'Invalid credentials' });
    }

    const user = rows[0];

    const match = await bcrypt.compare(password, user.passwordHash);
    if (!match) {
      return res.status(400).json({ error: 'Invalid credentials' });
    }

    if (!user.emailVerified) {
      return res.status(403).json({
        error: 'Email not verified',
        code: 'EMAIL_NOT_VERIFIED',
      });
    }

    req.session.user = {
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role,
    };

    return res.json({
      message: 'Logged in',
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role,
      },
    });
  } catch (err) {
    return next(err);
  }
});

router.post('/logout', (req, res, next) => {
  req.session.destroy((err) => {
    if (err) return next(err);
    res.clearCookie(process.env.SESSION_NAME || 'swbs_sid');
    res.json({ message: 'Logged out' });
  });
});

router.get('/verify-email', async (req, res, next) => {
  const { token } = req.query;
  if (!token) {
    return res.status(400).json({ error: 'Missing token' });
  }

  try {
    const [rows] = await db
      .promise()
      .query(
        'SELECT * FROM users WHERE verifyToken = ? AND verifyExpires > NOW()',
        [token]
      );

    if (rows.length === 0) {
      return res.status(400).json({ error: 'Invalid or expired token' });
    }

    const user = rows[0];

    await db
      .promise()
      .query(
        'UPDATE users SET emailVerified = 1, verifyToken = NULL, verifyExpires = NULL WHERE id = ?',
        [user.id]
      );

    return res.json({ message: 'Email verified successfully' });
  } catch (err) {
    return next(err);
  }
});

module.exports = router;