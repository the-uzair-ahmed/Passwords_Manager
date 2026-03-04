<?php
// config.php - local config
// TIP: for production, move secrets out of repo and use environment vars.

return [
  'db' => [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'name' => getenv('DB_NAME') ?: 'passwords_manager',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
  ],
  // If you deploy behind HTTPS, set COOKIE_SECURE=true in env
  'cookie_secure' => (getenv('COOKIE_SECURE') === 'true'),
  'app_name' => 'Passwords Manager',
];
