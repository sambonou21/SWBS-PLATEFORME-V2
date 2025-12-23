const db = require('../config/db');

async function listPublicProducts() {
  const [rows] = await db
    .promise()
    .query(
      "SELECT p.*, c.name AS categoryName, c.slug AS categorySlug FROM products p LEFT JOIN categories c ON p.categoryId = c.id WHERE p.status = 'published' ORDER BY p.id DESC"
    );
  return rows;
}

async function getProductBySlug(slug) {
  const [rows] = await db
    .promise()
    .query(
      "SELECT p.*, c.name AS categoryName, c.slug AS categorySlug FROM products p LEFT JOIN categories c ON p.categoryId = c.id WHERE p.slug = ? AND p.status = 'published' LIMIT 1",
      [slug]
    );
  return rows[0] || null;
}

async function listAdminProducts() {
  const [rows] = await db
    .promise()
    .query(
      'SELECT p.*, c.name AS categoryName FROM products p LEFT JOIN categories c ON p.categoryId = c.id ORDER BY p.id DESC'
    );
  return rows;
}

async function getCategories() {
  const [rows] = await db
    .promise()
    .query('SELECT * FROM categories ORDER BY name ASC');
  return rows;
}

async function createCategory({ name, slug }) {
  const [result] = await db
    .promise()
    .query('INSERT INTO categories (name, slug) VALUES (?, ?)', [name, slug]);
  const [rows] = await db
    .promise()
    .query('SELECT * FROM categories WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function createProduct(data) {
  const {
    categoryId,
    title,
    slug,
    description,
    priceFcfa,
    stock,
    imagePath,
    status,
  } = data;

  const [result] = await db
    .promise()
    .query(
      'INSERT INTO products (categoryId, title, slug, description, priceFcfa, stock, imagePath, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
      [
        categoryId || null,
        title,
        slug,
        description,
        priceFcfa,
        stock,
        imagePath || null,
        status || 'draft',
      ]
    );

  const [rows] = await db
    .promise()
    .query('SELECT * FROM products WHERE id = ?', [result.insertId]);
  return rows[0];
}

async function updateProduct(id, data) {
  const fields = [];
  const values = [];

  Object.keys(data).forEach((key) => {
    if (data[key] !== undefined) {
      fields.push(`${key} = ?`);
      values.push(data[key]);
    }
  });

  if (!fields.length) {
    const [rows] = await db
      .promise()
      .query('SELECT * FROM products WHERE id = ?', [id]);
    return rows[0] || null;
  }

  values.push(id);
  await db
    .promise()
    .query(`UPDATE products SET ${fields.join(', ')} WHERE id = ?`, values);

  const [rows] = await db
    .promise()
    .query('SELECT * FROM products WHERE id = ?', [id]);
  return rows[0] || null;
}

async function deleteProduct(id) {
  await db.promise().query('DELETE FROM products WHERE id = ?', [id]);
}

module.exports = {
  listPublicProducts,
  getProductBySlug,
  listAdminProducts,
  getCategories,
  createCategory,
  createProduct,
  updateProduct,
  deleteProduct,
};