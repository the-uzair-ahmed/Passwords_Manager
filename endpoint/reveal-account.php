<?php
require_once __DIR__ . '/../conn/conn.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
$csrf = $data['csrf'] ?? '';

header("Content-Type: application/json; charset=utf-8");

if (!csrf_validate($csrf)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => 'Invalid CSRF token']);
  exit;
}

$user_id = current_user_id();
$account_id = (int)($data['account_id'] ?? 0);

$key = session_vault_key();
if (!$key) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'message' => 'Vault locked']);
  exit;
}

$stmt = $conn->prepare("SELECT password_nonce, password_cipher FROM tbl_accounts WHERE tbl_account_id = :aid AND tbl_user_id = :uid");
$stmt->execute(['aid' => $account_id, 'uid' => $user_id]);
$row = $stmt->fetch();

if (!$row) {
  http_response_code(404);
  echo json_encode(['ok' => false, 'message' => 'Not found']);
  exit;
}

try {
  $plain = decrypt_secretbox($row['password_nonce'], $row['password_cipher'], $key);
  echo json_encode(['ok' => true, 'password' => $plain]);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => 'Unable to decrypt']);
}
exit;
