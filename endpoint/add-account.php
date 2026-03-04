<?php
require_once __DIR__ . '/../conn/conn.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf'] ?? '')) { flash_set('danger', 'Invalid CSRF token.'); header("Location: ../home.php"); exit; }

$user_id = current_user_id();
$key = session_vault_key();
if (!$key) {
  flash_set('danger', 'Vault is locked. Please logout and login again.');
  header("Location: ../home.php");
  exit;
}

$accountName = trim((string)($_POST['account_name'] ?? ''));
$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$link = trim((string)($_POST['link'] ?? ''));
$description = trim((string)($_POST['description'] ?? ''));

if ($accountName === '' || $username === '' || $password === '') {
  flash_set('danger', 'Account name, username and password are required.');
  header("Location: ../home.php");
  exit;
}

if ($link !== '' && !preg_match('#^https?://#i', $link)) {
  // Normalize if user forgot scheme
  $link = 'https://' . $link;
}

try {
  $enc = encrypt_secretbox($password, $key);
  $stmt = $conn->prepare("INSERT INTO tbl_accounts
    (tbl_user_id, account_name, username, password_nonce, password_cipher, link, description, created_at)
    VALUES (:uid, :an, :un, :pn, :pc, :lk, :ds, NOW())");
  $stmt->execute([
    'uid' => $user_id,
    'an'  => $accountName,
    'un'  => $username,
    'pn'  => $enc['nonce_b64'],
    'pc'  => $enc['cipher_b64'],
    'lk'  => $link,
    'ds'  => $description,
  ]);

  flash_set('success', 'Account saved.');
} catch (Exception $e) {
  flash_set('danger', 'Failed to save account.');
}

header("Location: ../home.php");
exit;
