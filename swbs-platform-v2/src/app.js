const path = require('path');
const express = require('express');
const session = require('express-session');
const MySQLStore = require('express-mysql-session')(session);
const cookieParser = require('cookie-parser');
const morgan = require('morgan');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const csrf = require('csurf');

const db = require('./config/db');
const sessionStoreOptions = require('./config/sessionStore');
const { configureSecurity } = require('./config/security');
const i18nConfig = require('./config/i18n');

const publicRoutes = require('./routes/public.routes');
const authRoutes = require('./routes/auth.routes');
const { csrfMiddleware } = require('./middlewares/csrf');
const { errorHandler } = require('./middlewares/errorHandler');

const app = express();

const sessionStore = new MySQLStore(sessionStoreOptions, db.promise());

// View / static config
const publicDir = path.join(__dirname, '..', 'public');
app.use(express.static(publicDir));

app.use(morgan('dev'));
app.use(helmet());
configureSecurity(app);

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

app.use(
  session({
    key: process.env.SESSION_NAME || 'swbs_sid',
    secret: process.env.SESSION_SECRET || 'change_this_session_secret',
    store: sessionStore,
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      secure: process.env.SESSION_COOKIE_SECURE === 'true',
      maxAge:
        (parseInt(process.env.SESSION_COOKIE_MAX_AGE_DAYS, 10) || 7) *
        24 *
        60 *
        60 *
        1000,
    },
  })
);

// i18n initialization (language from cookie / query)
app.use(i18nConfig.languageDetector);

// Rate limiting (global)
const limiter = rateLimit({
  windowMs: parseInt(process.env.RATE_LIMIT_WINDOW_MS || '60000', 10),
  max: parseInt(process.env.RATE_LIMIT_MAX || '100', 10),
});
app.use(limiter);

// CSRF
const csrfProtection = csrf({ cookie: true });
app.use(csrfProtection);
app.use(csrfMiddleware);

// Routes
app.use('/', publicRoutes);
app.use('/api/auth', authRoutes);

// Healthcheck
app.get('/health', (req, res) => {
  res.json({ status: 'ok' });
});

// 404
app.use((req, res, next) => {
  if (req.accepts('json')) {
    return res.status(404).json({ error: 'Not found' });
  }
  return res.status(404).sendFile(path.join(publicDir, 'index.html'));
});

// Error handler
app.use(errorHandler);

module.exports = app;