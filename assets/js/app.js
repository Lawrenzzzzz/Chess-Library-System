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

    const copy = {
      login:  { title: 'Welcome back.', intro: 'Your next chapter in chess starts here.', btn: 'Sign in to the library', note: 'New to the library?', switchTo: 'signup', switchText: 'Join our community' },
      signup: { title: 'Join the library.', intro: 'Create your member account to begin.', btn: 'Create my account', note: 'Already a member?', switchTo: 'login', switchText: 'Sign in' },
      forgot: { title: 'Reset password.', intro: 'Enter your email and we will send you a reset link.', btn: 'Send reset link', note: '', switchTo: 'login', switchText: '' },
    };

    function setMode(mode) {
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
      noteText.textContent = c.note;
      noteSwitch.textContent = c.switchText;
      noteSwitch.dataset.target = c.switchTo;
      pw.required = mode !== 'forgot';
      nameInput.required = mode === 'signup';
      pw.autocomplete = mode === 'signup' ? 'new-password' : 'current-password';
      pw.placeholder = mode === 'signup' ? 'At least 8 characters' : 'Enter your password';
      notice.hidden = true;
      showError('');
    }

    guest.querySelectorAll('[data-mode-btn]').forEach((b) =>
      b.addEventListener('click', () => setMode(b.dataset.modeBtn)));
    noteSwitch.addEventListener('click', () => setMode(noteSwitch.dataset.target || 'signup'));
    setMode('login');

    // Show / hide password
    const toggle = document.getElementById('pw-toggle');
    toggle.addEventListener('click', () => {
      const show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      toggle.querySelector('.pw-show').hidden = show;
      toggle.querySelector('.pw-hide').hidden = !show;
      toggle.title = toggle.ariaLabel = show ? 'Hide password' : 'Show password';
    });

    form.addEventListener('submit', async (ev) => {
      ev.preventDefault();
      showError(''); notice.hidden = true;
      const mode = guest.dataset.mode;
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

    // Header button -> jump to panel
    document.getElementById('focus-login')?.addEventListener('click', () => {
      document.getElementById('member-access')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      form.email.focus({ preventScroll: true });
    });
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
