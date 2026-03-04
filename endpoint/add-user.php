<?php
require_once __DIR__ . '/../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf'] ?? '')) { flash_set('danger', 'Invalid CSRF token.'); header("Location: ../index.php"); exit; }

$name = trim((string)($_POST['name'] ?? ''));
$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($name === '' || $username === '' || $password === '') {
  flash_set('danger', 'All fields are required.');
  header("Location: ../index.php");
  exit;
}
if (strlen($password) < 8) {
  flash_set('danger', 'Master password must be at least 8 characters.');
  header("Location: ../index.php");
  exit;
}

// Unique username
$stmt = $conn->prepare("SELECT 1 FROM tbl_user WHERE username = :u LIMIT 1");
$stmt->execute(['u' => $username]);
if ($stmt->fetch()) {
  flash_set('danger', 'Username already exists.');
  header("Location: ../index.php");
  exit;
}

// Create user with modern hashing + KDF salt
$hash = password_hash($password, PASSWORD_DEFAULT);
$salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);
$salt_b64 = base64_encode($salt);

$stmt = $conn->prepare("INSERT INTO tbl_user (name, username, password_hash, kdf_salt, created_at) VALUES (:n, :u, :h, :s, NOW())");
$stmt->execute(['n' => $name, 'u' => $username, 'h' => $hash, 's' => $salt_b64]);

flash_set('success', 'Account created. Please login.');
header("Location: ../index.php");
exit;
