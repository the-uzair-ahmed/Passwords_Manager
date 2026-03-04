<?php require_once __DIR__ . '/../conn/conn.php'; ?>
<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="./endpoint/add-account.php" class="needs-validation" novalidate>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-plus me-2"></i>Add Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Account Name</label>
              <input required name="account_name" class="form-control" placeholder="e.g., Gmail">
            </div>
            <div class="col-md-6">
              <label class="form-label">Login Username / Email</label>
              <input required name="username" class="form-control" placeholder="email@example.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input required name="password" class="form-control" placeholder="••••••••" autocomplete="new-password">
            </div>
            <div class="col-md-6">
              <label class="form-label">Link</label>
              <input name="link" class="form-control" placeholder="https://...">
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Notes (optional)"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Password Generator Modal -->
<div class="modal fade" id="genModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Password Generator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">Length</label>
        <input id="pmGenLen" type="number" class="form-control" value="16" min="8" max="64">
        <label class="form-label mt-3">Generated</label>
        <input id="pmGenOut" class="form-control pm-mask" readonly>
        <div class="small text-muted mt-2">Tip: Use <span class="pm-kbd">Copy</span> after reveal for safer workflow.</div>
      </div>
      <div class="modal-footer">
        <button id="pmGenBtn" class="btn btn-primary"><i class="fa-solid fa-bolt me-2"></i>Generate</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="./endpoint/update-account.php" class="needs-validation" novalidate>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <input type="hidden" name="account_id" id="pm_edit_id" value="">

        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Account Name</label>
              <input required name="account_name" id="pm_edit_account_name" class="form-control" placeholder="e.g., Gmail">
            </div>

            <div class="col-md-6">
              <label class="form-label">Login Username / Email</label>
              <input required name="username" id="pm_edit_username" class="form-control" placeholder="email@example.com">
            </div>

            <div class="col-md-6">
              <label class="form-label">New Password (optional)</label>
              <input name="password" id="pm_edit_password" class="form-control" placeholder="Leave blank to keep old" autocomplete="new-password">
              <div class="form-text">Blank chhor do to purana password same rahega.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Link</label>
              <input name="link" id="pm_edit_link" class="form-control" placeholder="https://...">
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="pm_edit_description" class="form-control" rows="3" placeholder="Notes (optional)"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Bootstrap validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
