<?php
require_once __DIR__ . '/conn/conn.php';
require_login();

$user_id = current_user_id();

// Fetch user name
$stmt = $conn->prepare("SELECT name, username FROM tbl_user WHERE tbl_user_id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$user_name = $user ? $user['name'] : 'User';

// Fetch accounts
$stmt = $conn->prepare("SELECT tbl_account_id, account_name, username, link, description, created_at
                        FROM tbl_accounts
                        WHERE tbl_user_id = :id
                        ORDER BY tbl_account_id DESC");
$stmt->execute(['id' => $user_id]);
$accounts = $stmt->fetchAll();

$last_activity = null;
if (!empty($accounts) && !empty($accounts[0]['created_at'])) {
  $last_activity = $accounts[0]['created_at'];
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/modal.php';
?>

<div class="container py-4">

  <div class="pm-hero rounded-4 p-4 p-md-5 mb-4">
    <div class="row g-4 align-items-center">
      <div class="col-lg-7">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="pm-chip"><i class="fa-solid fa-shield-halved"></i> Encrypted Vault</span>
          <span class="pm-chip"><i class="fa-solid fa-lock"></i> CSRF Protected</span>
          <span class="pm-chip"><i class="fa-solid fa-bolt"></i> Fast UI</span>
        </div>

        <div class="display-6 fw-semibold mb-1"><?php echo htmlspecialchars($config['app_name']); ?></div>
        <div class="text-muted fs-6">
          Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong> • Keep your vault safe.
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
          <span class="pm-chip">
            <i class="fa-solid fa-vault"></i>
            <?php echo count($accounts); ?> saved account<?php echo count($accounts) === 1 ? '' : 's'; ?>
          </span>
          <span class="pm-chip">
            <i class="fa-regular fa-clock"></i>
            Last add: <?php echo $last_activity ? htmlspecialchars(date('M d, Y', strtotime($last_activity))) : '—'; ?>
          </span>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="d-flex flex-wrap justify-content-lg-end gap-2">
          <button class="btn btn-outline-primary px-3" data-bs-toggle="modal" data-bs-target="#genModal">
            <i class="fa-solid fa-wand-magic-sparkles me-2"></i>Generator
          </button>
          <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="fa-solid fa-plus me-2"></i>Add Account
          </button>
          <form action="./endpoint/logout.php" method="POST" class="d-inline">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <button class="btn btn-outline-danger px-3">
              <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout
            </button>
          </form>
        </div>

        <div class="mt-3">
          <div class="alert alert-light border mb-0" role="alert">
            <div class="fw-semibold mb-1"><i class="fa-solid fa-circle-info me-2"></i>Security tip</div>
            <div class="small text-muted">
              Use a strong master password, and don’t keep the vault open on shared PCs.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="pm-surface bg-white">
    <div class="px-3 px-md-4 py-3 border-bottom bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="fw-semibold"><i class="fa-solid fa-vault me-2"></i>Saved Accounts</div>
      <div class="d-flex align-items-center gap-2">
        <input id="pmSearch" class="form-control form-control-sm" style="min-width: 240px;" placeholder="Search...">
        <span class="badge text-bg-light"><?php echo count($accounts); ?> items</span>
      </div>
    </div>

    <div class="px-3 px-md-4 pb-3">
    <div class="table-responsive">
      <table class="table table-hover mb-0 pm-table">
        <thead class="table-light">
          <tr>
            <th>Account</th>
            <th>Username</th>
            <th>Password</th>
            <th>Link</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$accounts): ?>
            <tr><td colspan="5" class="text-center pm-empty">
              <div class="mb-2"><i class="fa-solid fa-box-open fa-2x text-muted"></i></div>
              <div class="fw-semibold">No accounts yet</div>
              <div class="text-muted small">Click <span class="pm-kbd">Add Account</span> to save your first credential.</div>
            </td></tr>
          <?php endif; ?>

          <?php foreach ($accounts as $a): ?>
            <tr data-row>
              <td>
                <div class="fw-semibold"><?php echo htmlspecialchars($a['account_name']); ?></div>
                <?php if (!empty($a['description'])): ?>
                  <div class="text-muted small"><?php echo htmlspecialchars($a['description']); ?></div>
                <?php endif; ?>
              </td>
              <td class="pm-mask"><?php echo htmlspecialchars($a['username']); ?></td>
              <td class="pm-mask" data-pw-target="<?php echo (int)$a['tbl_account_id']; ?>">••••••••</td>
              <td>
                <?php if (!empty($a['link'])): ?>
                  <a class="link-primary" target="_blank" rel="noopener" href="<?php echo htmlspecialchars($a['link']); ?>">
                    Open <i class="fa-solid fa-arrow-up-right-from-square ms-1 small"></i>
                  </a>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <div class="btn-group pm-actions">
                  <button class="btn btn-sm btn-outline-secondary" data-action="reveal" data-id="<?php echo (int)$a['tbl_account_id']; ?>" data-csrf="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-secondary" data-action="copy" data-id="<?php echo (int)$a['tbl_account_id']; ?>" data-csrf="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <i class="fa-solid fa-copy"></i>
                  </button>
                  <form method="POST" action="./endpoint/delete-account.php" onsubmit="return confirm('Delete this account?');">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <input type="hidden" name="account_id" value="<?php echo (int)$a['tbl_account_id']; ?>">
                    <button class="btn btn-sm btn-outline-danger" style="border-radius: 0px 10px 10px 0px;">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    </div>
  </div>

  <div class="text-muted small mt-3">
    Tip: For extra safety, don’t keep the vault open on shared PCs. Use logout when done.
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
