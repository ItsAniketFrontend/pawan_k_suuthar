-- Run this once in phpMyAdmin (cPanel > phpMyAdmin > select your database > SQL tab,
-- paste this, and click Go) against the database you created,
-- e.g. u694812033_landing

CREATE TABLE IF NOT EXISTS leads (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  city VARCHAR(100) NOT NULL,
  apartment_type VARCHAR(50) NOT NULL,
  source VARCHAR(50) NOT NULL DEFAULT 'hero_form',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_fake TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Do NOT insert an admin user here. After uploading the site, visit
-- https://pawan.kumbhinteriors.com/admin/setup.php once in your browser
-- to create the admin login with a password you choose. That script
-- disables itself automatically after the account is created.
