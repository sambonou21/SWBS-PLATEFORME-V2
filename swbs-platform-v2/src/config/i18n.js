function getDefaultLanguage() {
  return 'fr';
}

function languageDetector(req, res, next) {
  let lang = req.cookies.lang || getDefaultLanguage();
  if (req.query.lang &amp;&amp; ['fr', 'en'].includes(req.query.lang)) {
    lang = req.query.lang;
    res.cookie('lang', lang, { httpOnly: false, maxAge: 365 * 24 * 60 * 60 * 1000 });
  }
  req.lang = lang;
  res.locals.lang = lang;
  next();
}

module.exports = {
  getDefaultLanguage,
  languageDetector,
};