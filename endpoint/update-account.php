<?php
require_once __DIR__ . '/../conn/conn.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf'] ?? '')) {
  flash_set('danger', 'Invalid CSRF token.');
  header("Location: ../home.php");
  exit;
}

$user_id = current_user_id();
$key = session_vault_key();
if (!$key) {
  flash_set('danger', 'Vault is locked. Please logout and login again.');
  header("Location: ../home.php");
  exit;
}

$account_id  = (int)($_POST['account_id'] ?? 0);
$accountName = trim((string)($_POST['account_name'] ?? ''));
$username    = trim((string)($_POST['username'] ?? ''));
$newPassword = (string)($_POST['password'] ?? '');
$link        = trim((string)($_POST['link'] ?? ''));
$description = trim((string)($_POST['description'] ?? ''));

if ($account_id <= 0 || $accountName === '' || $username === '') {
  flash_set('danger', 'Account name and username are required.');
  header("Location: ../home.php");
  exit;
}

if ($link !== '' && !preg_match('#^https?://#i', $link)) {
  $link = 'https://' . $link;
}

// Ownership check
$stmt = $conn->prepare("SELECT tbl_account_id FROM tbl_accounts WHERE tbl_account_id = :aid AND tbl_user_id = :uid");
$stmt->execute(['aid' => $account_id, 'uid' => $user_id]);
if (!$stmt->fetch()) {
  flash_set('danger', 'Access denied.');
  header("Location: ../home.php");
  exit;
}

try {
  if ($newPassword !== '') {
    // update including password (re-encrypt)
    $enc = encrypt_secretbox($newPassword, $key);

    $stmt = $conn->prepare("UPDATE tbl_accounts
      SET account_name = :an,
          username = :un,
          password_nonce = :pn,
          password_cipher = :pc,
          link = :lk,
          description = :ds
      WHERE tbl_account_id = :aid AND tbl_user_id = :uid");

    $stmt->execute([
      'an'  => $accountName,
      'un'  => $username,
      'pn'  => $enc['nonce_b64'],
      'pc'  => $enc['cipher_b64'],
      'lk'  => $link,
      'ds'  => $description,
      'aid' => $account_id,
      'uid' => $user_id,
    ]);
  } else {
    // update without touching password fields
    $stmt = $conn->prepare("UPDATE tbl_accounts
      SET account_name = :an,
          username = :un,
          link = :lk,
          description = :ds
      WHERE tbl_account_id = :aid AND tbl_user_id = :uid");

    $stmt->execute([
      'an'  => $accountName,
      'un'  => $username,
      'lk'  => $link,
      'ds'  => $description,
      'aid' => $account_id,
      'uid' => $user_id,
    ]);
  }

  flash_set('success', 'Account updated.');
} catch (Exception $e) {
  flash_set('danger', 'Failed to update account.');
}

header("Location: ../home.php");
exit;