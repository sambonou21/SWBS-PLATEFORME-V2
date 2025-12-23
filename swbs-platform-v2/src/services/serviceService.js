const db = require('../config/db');

async function listServices() {
  const [rows] = await db
    .promise()
    .query('SELECT * FROM services ORDER BY createdAt DESC');
  return rows;
}

async function createService({ title, slug, description, price, imagePath }) {
  const [result] = await db
    .promise()
    .query(
      'INSERT INTO services (title, slug, description, price, imagePath, createdAt) VALUES (?, ?, ?, ?, ?, NOW())',
      [title, slug, description, price || null, imagePath || null]
    );
  const [rows] = await db
    .promise()
    .query('SELECT * FROM services WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function updateService(id, patch) {
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
      .query('SELECT * FROM services WHERE id = ?', [id]);
    return rows[0] || null;
  }
  values.push(id);
  await db
    .promise()
    .query(`UPDATE services SET ${fields.join(', ')} WHERE id = ?`, values);
  const [rows] = await db
    .promise()
    .query('SELECT * FROM services WHERE id = ?', [id]);
  return rows[0] || null;
}

async function deleteService(id) {
  await db.promise().query('DELETE FROM services WHERE id = ?', [id]);
}

module.exports = {
  listServices,
  createService,
  updateService,
  deleteService,
};