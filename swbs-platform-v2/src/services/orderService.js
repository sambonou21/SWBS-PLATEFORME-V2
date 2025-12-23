const db = require('../config/db');

async function createOrder({ userId, currency, totalFcfa, items, paymentProvider, paymentRef }) {
  const connection = await db.promise().getConnection();
  try {
    await connection.beginTransaction();

    const [orderResult] = await connection.query(
      'INSERT INTO orders (userId, totalFcfa, currency, status, paymentProvider, paymentRef, createdAt) VALUES (?, ?, ?, ?, ?, ?, NOW())',
      [userId, totalFcfa, currency, 'pending', paymentProvider || null, paymentRef || null]
    );
    const orderId = orderResult.insertId;

    for (const item of items) {
      await connection.query(
        'INSERT INTO order_items (orderId, productId, qty, priceFcfa) VALUES (?, ?, ?, ?)',
        [orderId, item.productId, item.qty, item.priceFcfa]
      );
    }

    await connection.commit();

    const [rows] = await connection.query('SELECT * FROM orders WHERE id = ?', [
      orderId,
    ]);
    return rows[0];
  } catch (err) {
    await connection.rollback();
    throw err;
  } finally {
    connection.release();
  }
}

async function updateOrderStatus(orderId, status, paymentRef) {
  await db
    .promise()
    .query('UPDATE orders SET status = ?, paymentRef = ? WHERE id = ?', [
      status,
      paymentRef || null,
      orderId,
    ]);
  const [rows] = await db
    .promise()
    .query('SELECT * FROM orders WHERE id = ?', [orderId]);
  return rows[0] || null;
}

module.exports = {
  createOrder,
  updateOrderStatus,
};