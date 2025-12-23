require('dotenv').config();
const fs = require('fs');
const path = require('path');
const db = require('../config/db');

async function migrate() {
  const schemaPath = path.join(__dirname, 'schema.sql');
  const sql = fs.readFileSync(schemaPath, 'utf8');

  const connection = db.promise();
  try {
    console.log('Running migrations...');
    await connection.query(sql);
    console.log('Migrations completed successfully.');
    process.exit(0);
  } catch (err) {
    console.error('Migration failed:', err);
    process.exit(1);
  }
}

migrate();