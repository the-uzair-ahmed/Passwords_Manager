<?php
// flash.php
function flash_set(string $type, string $message): void {
  $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array {
  if (!isset($_SESSION['_flash'])) return null;
  $f = $_SESSION['_flash'];
  unset($_SESSION['_flash']);
  return $f;
}
