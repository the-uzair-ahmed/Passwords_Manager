<?php
require_once __DIR__ . '/../conn/conn.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf'] ?? '')) { flash_set('danger', 'Invalid CSRF token.'); header("Location: ../home.php"); exit; }

$user_id = current_user_id();
$account_id = (int)($_POST['account_id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM tbl_accounts WHERE tbl_account_id = :aid AND tbl_user_id = :uid");
$stmt->execute(['aid' => $account_id, 'uid' => $user_id]);

flash_set('success', 'Account deleted.');
header("Location: ../home.php");
exit;
