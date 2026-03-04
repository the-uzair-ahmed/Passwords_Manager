<?php
require_once __DIR__ . '/../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_validate($_POST['csrf'] ?? '')) { flash_set('danger', 'Invalid CSRF token.'); header("Location: ../home.php"); exit; }

if (!empty($_SESSION['vault_key'])) {
  $k = b64d($_SESSION['vault_key']);
  if ($k) sodium_memzero($k);
}
$_SESSION = [];
session_destroy();

flash_set('success', 'Logged out.');
header("Location: ../index.php");
exit;
