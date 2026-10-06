-- TFA4 migration for a database that already has the TFA3 tables.
-- Adds the password column and sets a hashed password for EVERY existing user,
-- WITHOUT deleting any records.
-- Run it in phpMyAdmin: select the database -> SQL tab -> paste -> Go.
--
-- The hash below was generated with PHP:  password_hash('password123', PASSWORD_DEFAULT)
-- so every existing user can log in with the password:  password123

ALTER TABLE users ADD COLUMN password VARCHAR(255) NULL AFTER full_name;

UPDATE users SET password = '$2y$10$C.YXi3kOWdCBxQNrrA7.0OyJTk0.A.XkM0DrwQEKw6g13zn9mck36' WHERE password IS NULL OR password = '';

ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
