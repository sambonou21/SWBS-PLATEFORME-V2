const mysql = require('mysql2');

// Supporte à la fois les variables DB_* et MYSQL_* pour compatibilité
const host = process.env.DB_HOST || process.env.MYSQL_HOST || 'localhost';
const portRaw = process.env.DB_PORT || process.env.MYSQL_PORT || '3306';
const user = process.env.DB_USER || process.env.MYSQL_USER;
const password = process.env.DB_PASS || process.env.MYSQL_PASSWORD;
const database = process.env.DB_NAME || process.env.MYSQL_DATABASE;

const pool = mysql.createPool({
  host,
  port: parseInt(portRaw, 10),
  user,
  password,
  database,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  multipleStatements: true, // nécessaire pour exécuter schema.sql en une seule requête
});

module.exports = pool;