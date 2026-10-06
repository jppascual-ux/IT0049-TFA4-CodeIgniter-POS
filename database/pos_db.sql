-- IT0049 TFA4 - POS Database Export (includes TFA2 + TFA3 + TFA4 changes)
-- Database: pos_db
-- Import this file in phpMyAdmin (Import tab). It creates the database, both tables and sample data.
--
-- TFA3: users.avatar stores ONLY the image filename.
-- TFA4: users.password stores a password_hash() (bcrypt) value, never plain text.
--       Every sample user's password is:  password123
--       (hashes generated with PHP: password_hash('password123', PASSWORD_DEFAULT))

CREATE DATABASE IF NOT EXISTS `pos_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pos_db`;

-- --------------------------------------------------------
-- Table: customers
-- --------------------------------------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME NOT NULL
);

INSERT INTO customers (full_name, email, phone, created_at) VALUES
('Maria Santos',   'maria.santos@email.com', '09171234567', '2026-09-01 09:15:00'),
('Jose Reyes',     'jose.reyes@email.com',   '09182345678', '2026-09-02 10:30:00'),
('Ana Cruz',       'ana.cruz@email.com',     '09193456789', '2026-09-03 11:45:00'),
('Carlo Dizon',    'carlo.dizon@email.com',  '09204567890', '2026-09-04 13:00:00'),
('Liza Garcia',    'liza.garcia@email.com',  NULL,          '2026-09-05 14:20:00'),
('Ramon Bautista', 'ramon.b@email.com',      '09215678901', '2026-09-06 15:10:00');

-- --------------------------------------------------------
-- Table: users  (TFA3: + avatar, TFA4: + password)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  avatar VARCHAR(255) NULL DEFAULT NULL
);

INSERT INTO users (username, full_name, password, created_at, avatar) VALUES
('admin',     'System Administrator', '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:00:00', 'sample-admin.png'),
('cashier1',  'Bea Lopez',            '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:05:00', 'sample-cashier1.png'),
('cashier2',  'Mark Villanueva',      '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:10:00', NULL),
('manager',   'Grace Tan',            '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:15:00', NULL),
('inventory', 'Paulo Mendoza',        '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:20:00', NULL),
('support',   'Nina Ramos',           '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36', '2026-09-01 08:25:00', NULL);
