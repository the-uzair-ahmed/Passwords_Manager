<?php
require_once __DIR__ . '/conn/conn.php';
if (!empty($_SESSION['user_id'])) {
  header("Location: ./home.php");
  exit;
}
include __DIR__ . '/partials/header.php';
?>
<script>document.body.classList.add('pm-auth');</script>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="pm-card p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <div class="h4 mb-0 pm-brand"><?php echo htmlspecialchars($config['app_name']); ?></div>
              <div class="pm-muted small">Secure vault • Modern UI • AES-grade protection (libsodium)</div>
            </div>
            <span class="badge rounded-pill pm-badge-soft"><i class="fa-solid fa-shield-halved me-2"></i>v2</span>
          </div>

          <ul class="nav nav-pills gap-2 mb-4" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabLogin" type="button">Login</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabRegister" type="button">Create Account</button>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabLogin">
              <form action="./endpoint/login.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input required type="text" class="form-control" name="username" autocomplete="username">
                </div>
                <div class="mb-3">
                  <label class="form-label">Master Password</label>
                  <input required type="password" class="form-control" name="password" autocomplete="current-password">
                  <div class="form-text pm-muted">This password also protects your vault encryption key.</div>
                </div>
                <button class="btn btn-primary w-100">
                  <i class="fa-solid fa-right-to-bracket me-2"></i>Login
                </button>
              </form>
            </div>

            <div class="tab-pane fade" id="tabRegister">
              <form action="./endpoint/add-user.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input required type="text" class="form-control" name="name" autocomplete="name">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input required type="text" class="form-control" name="username" autocomplete="username">
                  </div>
                  <div class="col-12">
                    <label class="form-label">Master Password</label>
                    <input required type="password" class="form-control" name="password" autocomplete="new-password" minlength="8">
                    <div class="form-text pm-muted">Minimum 8 characters. Use a strong, unique master password.</div>
                  </div>
                </div>
                <button class="btn btn-success w-100 mt-3">
                  <i class="fa-solid fa-user-plus me-2"></i>Create Account
                </button>
              </form>
            </div>
          </div>

          <hr class="border-white border-opacity-10 my-4">
          <div class="pm-muted small">
            Security upgrades: <span class="pm-kbd">password_hash</span>, <span class="pm-kbd">CSRF</span>, <span class="pm-kbd">session hardening</span>, <span class="pm-kbd">libsodium vault encryption</span>.
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include __DIR__ . '/partials/footer.php'; ?>
