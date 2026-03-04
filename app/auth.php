<?php
// auth.php
function require_login(): void {
  if (empty($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit;
  }
}

function current_user_id(): ?int {
  return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}
