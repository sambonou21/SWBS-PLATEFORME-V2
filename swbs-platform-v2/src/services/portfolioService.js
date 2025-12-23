const db = require('../config/db');

async function listPortfolio() {
  const [rows] = await db
    .promise()
    .query('SELECT * FROM portfolio ORDER BY createdAt DESC');
  return rows;
}

async function createPortfolio({ title, slug, description, category, imagePath }) {
  const [result] = await db
    .promise()
    .query(
      'INSERT INTO portfolio (title, slug, description, category, imagePath, createdAt) VALUES (?, ?, ?, ?, ?, NOW())',
      [title, slug, description, category || null, imagePath || null]
    );
  const [rows] = await db
    .promise()
    .query('SELECT * FROM portfolio WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function updatePortfolio(id, patch) {
  const fields = [];
  const values = [];
  Object.keys(patch).forEach((key) => {
    if (patch[key] !== undefined) {
      fields.push(`${key} = ?`);
      values.push(patch[key]);
    }
  });
  if (!fields.length) {
    const [rows] = await db
      .promise()
      .query('SELECT * FROM portfolio WHERE id = ?', [id]);
    return rows[0] || null;
  }
  values.push(id);
  await db
    .promise()
    .query(`UPDATE portfolio SET ${fields.join(', ')} WHERE id = ?`, values);
  const [rows] = await db
    .promise()
    .query('SELECT * FROM portfolio WHERE id = ?', [id]);
  return rows[0] || null;
}

async function deletePortfolio(id) {
  await db.promise().query('DELETE FROM portfolio WHERE id = ?', [id]);
}

module.exports = {
  listPortfolio,
  createPortfolio,
  updatePortfolio,
  deletePortfolio,
};