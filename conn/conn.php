<?php
// Backwards compatible include for older files (if any)
require_once __DIR__ . '/../app/security.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/csrf.php';
require_once __DIR__ . '/../app/flash.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/crypto.php';

$config = require __DIR__ . '/../app/config.php';

// SITEURL auto-detect (works for local + prod)
if (!defined("SITEURL")) {
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
  define('SITEURL', $scheme . '://' . $host . $base);
}
