-- Upgraded schema (v2)
-- Created: 2026-03-04

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `kdf_salt` varchar(255) NOT NULL,
  `created_at` datetime NULL,
  PRIMARY KEY (`tbl_user_id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_accounts` (
  `tbl_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `tbl_user_id` int(11) NOT NULL,
  `account_name` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password_nonce` varchar(255) NOT NULL,
  `password_cipher` text NOT NULL,
  `link` text NULL,
  `description` text NULL,
  `created_at` datetime NULL,
  PRIMARY KEY (`tbl_account_id`),
  KEY `idx_user` (`tbl_user_id`),
  CONSTRAINT `fk_accounts_user` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`tbl_user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
