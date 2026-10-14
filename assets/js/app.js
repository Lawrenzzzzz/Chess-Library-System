(() => {
  'use strict';
  const body = document.body;
  const API = body.dataset.api;
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

  async function post(payload) {
    try {
      const res = await fetch(API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
        body: JSON.stringify(payload),
      });
      const data = await res.json().catch(() => ({}));
      return { ok: res.ok, data };
    } catch (e) {
      return { ok: false, data: { error: 'Network error. Please try again.' } };
    }
  }

  const errBox = document.getElementById('form-error');
  const showError = (msg) => { if (errBox) { errBox.textContent = msg; errBox.hidden = !msg; } };
  const setBusy = (btn, busy) => { btn.classList.toggle('is-busy', busy); btn.disabled = busy; };

  /* ---------------- Sign in / sign up / forgot ---------------- */
  const guest = document.getElementById('auth-guest');
  if (guest) {
    const form = document.getElementById('member-form');
    const notice = document.getElementById('form-notice');
    const submitBtn = document.getElementById('submit-btn');
    const label = submitBtn.querySelector('.btn-label');
    const title = document.getElementById('panel-title');
    const intro = document.getElementById('panel-intro');
    const noteText = document.getElementById('note-text');
    const noteSwitch = document.getElementById('note-switch');
    const pw = document.getElementById('password');
    const nameInput = document.getElementById('name');
    const modal = document.getElementById('auth-modal');
    const toggle = document.getElementById('pw-toggle');

    const copy = {
      login:  { title: 'Welcome back.', intro: 'Your next chapter in chess starts here.', btn: 'Sign in to the library', note: 'New to the library?', switchTo: 'signup', switchText: 'Join our community' },
      signup: { title: 'Join the library.', intro: 'Create your member account to begin.', btn: 'Create my account', note: 'Already a member?', switchTo: 'login', switchText: 'Sign in' },
      forgot: { title: 'Reset password.', intro: 'Enter your email and we will send you a reset link.', btn: 'Send reset link', note: '', switchTo: 'login', switchText: '' },
    };

    const actionInput = document.getElementById('auth-action');

    // Show / hide password
    function setPasswordVisible(visible) {
      pw.type = visible ? 'text' : 'password';
      if (!toggle) return;
      const showIcon = toggle.querySelector('.pw-show');
      const hideIcon = toggle.querySelector('.pw-hide');
      if (showIcon) showIcon.hidden = visible;
      if (hideIcon) hideIcon.hidden = !visible;
      toggle.title = visible ? 'Hide password' : 'Show password';
      toggle.setAttribute('aria-label', toggle.title);
      toggle.setAttribute('aria-pressed', visible ? 'true' : 'false');
    }

    function setMode(mode, clearMessages = true) {
      guest.dataset.mode = mode;
      guest.querySelectorAll('[data-modes]').forEach((el) => {
        el.hidden = !el.dataset.modes.split(' ').includes(mode);
      });
      guest.querySelectorAll('[data-mode-btn]').forEach((b) => {
        if (b.classList.contains('auth-tab')) b.classList.toggle('active', b.dataset.modeBtn === mode);
      });
      const c = copy[mode];
      title.textContent = c.title;
      intro.textContent = c.intro;
      label.textContent = c.btn;
      if (noteText) noteText.textContent = c.note;
      if (noteSwitch) {
        noteSwitch.textContent = c.switchText;
        noteSwitch.dataset.target = c.switchTo;
        noteSwitch.hidden = !c.switchText;
      }
      pw.required = mode !== 'forgot';
      nameInput.required = mode === 'signup';
      pw.autocomplete = mode === 'signup' ? 'new-password' : 'current-password';
      pw.placeholder = mode === 'signup' ? 'At least 8 characters' : 'Enter your password';
      setPasswordVisible(false);
      if (actionInput) actionInput.value = mode;
      if (clearMessages) {
        if (notice) notice.hidden = true;
        showError('');
      }
    }

    guest.querySelectorAll('[data-mode-btn]').forEach((b) =>
      b.addEventListener('click', () => setMode(b.dataset.modeBtn)));
    noteSwitch?.addEventListener('click', () => setMode(noteSwitch.dataset.target || 'signup'));
    setMode(guest.dataset.mode || 'login', false);

    // Show / hide password
    if (toggle) {
      toggle.addEventListener('click', () => setPasswordVisible(pw.type === 'password'));
    }

    form.addEventListener('submit', async (ev) => {
      const mode = guest.dataset.mode;

      // No JSON API configured on this page: let the browser POST the form natively.
      if (!API) return;

      ev.preventDefault();
      showError(''); if (notice) notice.hidden = true;
      const payload = { action: mode, email: form.email.value };
      if (mode !== 'forgot') payload.password = pw.value;
      if (mode === 'signup') payload.name = nameInput.value;

      setBusy(submitBtn, true);
      const { ok, data } = await post(payload);
      setBusy(submitBtn, false);

      if (!ok) return showError(data.error || 'Something went wrong. Please try again.');
      if (mode === 'login' || data.loggedIn) return location.reload();
      if (data.notice) { notice.textContent = data.notice; notice.hidden = false; }
    });

    /* ---------------- Auth popup ---------------- */
    let lastFocused = null;

    function openAuth(mode) {
      if (!modal) return;
      if (mode && copy[mode]) setMode(mode);
      lastFocused = document.activeElement;
      modal.hidden = false;
      body.classList.add('auth-lock');
      requestAnimationFrame(() => form.email.focus({ preventScroll: true }));
    }

    function closeAuth() {
      if (!modal || modal.hidden) return;
      modal.hidden = true;
      body.classList.remove('auth-lock');
      setPasswordVisible(false);
      if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
    }

    document.querySelectorAll('[data-open-auth]').forEach((btn) =>
      btn.addEventListener('click', () => openAuth(btn.dataset.mode)));
    modal?.querySelectorAll('[data-auth-close]').forEach((el) =>
      el.addEventListener('click', closeAuth));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeAuth();
    });

    // The server rendered the popup open (error / notice) - lock scroll and focus it
    if (modal && !modal.hidden) {
      body.classList.add('auth-lock');
      requestAnimationFrame(() => form.email.focus({ preventScroll: true }));
    }
  } else {
    document.getElementById('focus-login')?.addEventListener('click', () =>
      document.getElementById('member-access')?.scrollIntoView({ behavior: 'smooth', block: 'center' }));
  }

  /* ---------------- Sign out ---------------- */
  document.getElementById('signout')?.addEventListener('click', async () => {
    await post({ action: 'logout' });
    location.href = body.dataset.home || '/';
  });

  /* ---------------- Reset password page ---------------- */
  const resetForm = document.getElementById('reset-form');
  if (resetForm) {
    const btn = document.getElementById('reset-btn');
    const err = document.getElementById('reset-error');
    resetForm.addEventListener('submit', async (ev) => {
      // No JSON API configured: let the browser POST the form natively.
      if (!API) return;
      ev.preventDefault();
      err.hidden = true;
      setBusy(btn, true);
      const { ok, data } = await post({
        action: 'reset',
        token: resetForm.token.value,
        password: resetForm.password.value,
        confirm: resetForm.confirm.value,
      });
      setBusy(btn, false);
      if (!ok) { err.textContent = data.error || 'Something went wrong.'; err.hidden = false; return; }
      resetForm.hidden = true;
      document.getElementById('reset-done').hidden = false;
    });
  }
})();
