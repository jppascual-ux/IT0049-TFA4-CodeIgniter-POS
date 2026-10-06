-- TFA3 migration for a database that already has the TFA2 tables.
-- Adds the avatar column WITHOUT deleting existing records.
-- Run it in phpMyAdmin: select the database -> SQL tab -> paste -> Go.

ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL DEFAULT NULL AFTER created_at;
