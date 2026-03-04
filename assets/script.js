
/* Simple UI helpers + secure reveal/copy (AJAX) */
(function () {
  const toastHost = document.getElementById("pmToastHost");
  function toast(msg, type="info") {
    if (!toastHost) return alert(msg);
    const el = document.createElement("div");
    el.className = `alert alert-${type} shadow-sm mb-2`;
    el.textContent = msg;
    toastHost.appendChild(el);
    setTimeout(() => el.remove(), 3200);
  }
  window.pmToast = toast;

  async function postJSON(url, data) {
    const resp = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "same-origin",
      body: JSON.stringify(data)
    });
    const ct = resp.headers.get("content-type") || "";
    if (!ct.includes("application/json")) {
      throw new Error("Unexpected response");
    }
    const out = await resp.json();
    if (!resp.ok || out.ok === false) {
      throw new Error(out.message || "Request failed");
    }
    return out;
  }

  // Reveal password
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-action='reveal']");
    if (!btn) return;
    e.preventDefault();
    const accountId = btn.getAttribute("data-id");
    const csrf = btn.getAttribute("data-csrf");
    btn.disabled = true;
    try {
      const out = await postJSON("./endpoint/reveal-account.php", { account_id: accountId, csrf });
      const target = document.querySelector(`[data-pw-target='${accountId}']`);
      if (target) target.textContent = out.password;
      toast("Password revealed", "success");
    } catch (err) {
      toast(err.message, "danger");
    } finally {
      btn.disabled = false;
    }
  });

  // Copy password (reveals via API, then copies)
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-action='copy']");
    if (!btn) return;
    e.preventDefault();
    const accountId = btn.getAttribute("data-id");
    const csrf = btn.getAttribute("data-csrf");
    btn.disabled = true;
    try {
      const out = await postJSON("./endpoint/reveal-account.php", { account_id: accountId, csrf });
      await navigator.clipboard.writeText(out.password);
      toast("Copied to clipboard", "success");
    } catch (err) {
      toast(err.message, "danger");
    } finally {
      btn.disabled = false;
    }
  });

  // Password generator
  const genBtn = document.getElementById("pmGenBtn");
  if (genBtn) {
    genBtn.addEventListener("click", () => {
      const length = parseInt(document.getElementById("pmGenLen").value || "16", 10);
      const alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{};:,.?";
      let out = "";
      const arr = new Uint32Array(length);
      crypto.getRandomValues(arr);
      for (let i = 0; i < length; i++) out += alphabet[arr[i] % alphabet.length];
      const inp = document.getElementById("pmGenOut");
      inp.value = out;
      inp.focus();
      inp.select();
      pmToast("Generated a strong password", "success");
    });
  }

  // Search filter
  const search = document.getElementById("pmSearch");
  if (search) {
    search.addEventListener("input", () => {
      const q = search.value.toLowerCase().trim();
      document.querySelectorAll("[data-row]").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
      });
    });
  }

  // Edit modal wiring
  document.addEventListener("click", function (e) {
    const btn = e.target.closest('[data-action="edit"]');
    if (!btn) return;

    const id = btn.getAttribute("data-id") || "";
    document.getElementById("pm_edit_id").value = id;

    document.getElementById("pm_edit_account_name").value = btn.getAttribute("data-account_name") || "";
    document.getElementById("pm_edit_username").value = btn.getAttribute("data-username") || "";
    document.getElementById("pm_edit_link").value = btn.getAttribute("data-link") || "";
    document.getElementById("pm_edit_description").value = btn.getAttribute("data-description") || "";

    // password field always blank for security
    document.getElementById("pm_edit_password").value = "";

    const modalEl = document.getElementById("editAccountModal");
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });
})();
