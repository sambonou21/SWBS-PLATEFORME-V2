-- SWBS-PLATEFORME-V2 - Schéma SQL principal

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  phone VARCHAR(50) NULL,
  passwordHash VARCHAR(191) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  emailVerified TINYINT(1) NOT NULL DEFAULT 0,
  verifyToken VARCHAR(191) NULL,
  verifyExpires DATETIME NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_users_email (email),
  INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  presenceAdmin TINYINT(1) NOT NULL DEFAULT 0,
  currencyRates JSON NULL,
  fedapayKeys JSON NULL,
  aiKeys JSON NULL,
  languageDefault VARCHAR(5) NOT NULL DEFAULT 'fr',
  chatConfig JSON NULL,
  updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  price DECIMAL(12,2) NULL,
  imagePath VARCHAR(255) NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_services_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS portfolio (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  category VARCHAR(191) NULL,
  imagePath VARCHAR(255) NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_portfolio_slug (slug),
  INDEX idx_portfolio_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quotes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  userId INT UNSIGNED NOT NULL,
  serviceId INT UNSIGNED NULL,
  payload JSON NOT NULL,
  status ENUM('recu','en_cours','valide','refuse') NOT NULL DEFAULT 'recu',
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_quotes_userId (userId),
  INDEX idx_quotes_status (status),
  CONSTRAINT fk_quotes_user FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_quotes_service FOREIGN KEY (serviceId) REFERENCES services(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS conversations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  userId INT UNSIGNED NULL,
  leadName VARCHAR(191) NULL,
  leadEmail VARCHAR(191) NULL,
  leadPhone VARCHAR(50) NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_conversations_userId (userId),
  INDEX idx_conversations_leadEmail (leadEmail),
  CONSTRAINT fk_conversations_user FOREIGN KEY (userId) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversationId INT UNSIGNED NOT NULL,
  senderType ENUM('user','admin','ai') NOT NULL,
  content TEXT NOT NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_messages_conversationId (conversationId),
  CONSTRAINT fk_messages_conversation FOREIGN KEY (conversationId) REFERENCES conversations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  INDEX idx_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoryId INT UNSIGNED NULL,
  title VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  priceFcfa DECIMAL(12,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  imagePath VARCHAR(255) NULL,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  INDEX idx_products_slug (slug),
  INDEX idx_products_categoryId (categoryId),
  CONSTRAINT fk_products_category FOREIGN KEY (categoryId) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  userId INT UNSIGNED NOT NULL,
  totalFcfa DECIMAL(12,2) NOT NULL,
  currency VARCHAR(10) NOT NULL,
  status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  paymentProvider VARCHAR(50) NULL,
  paymentRef VARCHAR(191) NULL,
  createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_orders_userId (userId),
  INDEX idx_orders_status (status),
  CONSTRAINT fk_orders_user FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orderId INT UNSIGNED NOT NULL,
  productId INT UNSIGNED NOT NULL,
  qty INT NOT NULL,
  priceFcfa DECIMAL(12,2) NOT NULL,
  INDEX idx_order_items_orderId (orderId),
  INDEX idx_order_items_productId (productId),
  CONSTRAINT fk_order_items_order FOREIGN KEY (orderId) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (productId) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;