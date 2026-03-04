<?php
// crypto.php - libsodium secretbox encryption for vault items.
// Key is derived from the user's master password using sodium_crypto_pwhash and stored (base64) in session.

function b64e(string $bin): string { return base64_encode($bin); }
function b64d(string $b64): string { return base64_decode($b64, true) ?: ''; }

function derive_key_from_password(string $password, string $salt_b64): string {
  $salt = b64d($salt_b64);
  if (strlen($salt) !== SODIUM_CRYPTO_PWHASH_SALTBYTES) {
    throw new Exception("Invalid KDF salt");
  }
  $key = sodium_crypto_pwhash(
    SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
    $password,
    $salt,
    SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
    SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
    SODIUM_CRYPTO_PWHASH_ALG_DEFAULT
  );
  return $key; // binary
}

function encrypt_secretbox(string $plaintext, string $key_bin): array {
  $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
  $cipher = sodium_crypto_secretbox($plaintext, $nonce, $key_bin);
  return ['nonce_b64' => b64e($nonce), 'cipher_b64' => b64e($cipher)];
}

function decrypt_secretbox(string $nonce_b64, string $cipher_b64, string $key_bin): string {
  $nonce = b64d($nonce_b64);
  $cipher = b64d($cipher_b64);
  $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key_bin);
  if ($plain === false) {
    throw new Exception("Decryption failed");
  }
  return $plain;
}

function session_vault_key(): ?string {
  if (empty($_SESSION['vault_key'])) return null;
  $k = b64d($_SESSION['vault_key']);
  if (strlen($k) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) return null;
  return $k;
}
