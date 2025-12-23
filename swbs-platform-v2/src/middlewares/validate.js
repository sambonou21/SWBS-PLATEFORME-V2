function validate(schema) {
  return (req, res, next) =&gt; {
    const options = { abortEarly: false, allowUnknown: true, stripUnknown: true };
    const { error, value } = schema.validate(req.body, options);
    if (error) {
      return res.status(400).json({
        error: 'Validation error',
        details: error.details.map((d) =&gt; d.message),
      });
    }
    req.body = value;
    next();
  };
}

module.exports = validate;