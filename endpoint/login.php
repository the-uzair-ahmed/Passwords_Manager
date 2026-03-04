<?php
require_once __DIR__ . '/../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit;
}

if (!csrf_validate($_POST['csrf'] ?? '')) {
  flash_set('danger', 'Invalid CSRF token.');
  header("Location: ../index.php");
  exit;
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
  flash_set('danger', 'Username and password are required.');
  header("Location: ../index.php");
  exit;
}

// Basic rate limit (per session)
$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
if ($_SESSION['login_attempts'] > 8) {
  flash_set('danger', 'Too many attempts. Please wait and try again.');
  header("Location: ../index.php");
  exit;
}

$stmt = $conn->prepare("SELECT tbl_user_id, password_hash, kdf_salt FROM tbl_user WHERE username = :u LIMIT 1");
$stmt->execute(['u' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
  $_SESSION['login_attempts']++;
  flash_set('danger', 'Invalid credentials.');
  header("Location: ../index.php");
  exit;
}

// Success: session hardening + derive vault key
session_regenerate_id(true);
$_SESSION['login_attempts'] = 0;
$_SESSION['user_id'] = (int)$user['tbl_user_id'];

try {
  $key = derive_key_from_password($password, $user['kdf_salt']);
  $_SESSION['vault_key'] = base64_encode($key);
  sodium_memzero($key);
} catch (Exception $e) {
  // If key derivation fails, block login (vault would be unusable)
  unset($_SESSION['user_id']);
  flash_set('danger', 'Security error: cannot unlock vault.');
  header("Location: ../index.php");
  exit;
}

flash_set('success', 'Login successful.');
header("Location: ../home.php");
exit;
