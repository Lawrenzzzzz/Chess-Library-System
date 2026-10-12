<?php
$error = (string) ($_GET['error'] ?? '');
$done  = isset($_GET['done']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset your password — Chess Library</title>
  <meta name="description" content="Manually set a new password for your Chess Library member account.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;450;500;550;600;650;700&family=Libre+Caslon+Display&display=swap">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
</head>
<body>
  <main class="reset-page">
    <div class="member-panel">
      <?php if ($done): ?>
        <span class="panel-symbol">♞</span>
        <h1 class="reset-heading">All set.</h1>
        <p class="panel-intro">Your password has been updated. You can now sign in with your new password.</p>
        <a class="btn btn-default form-button back-signin" href="index.php">Back to sign in</a>
      <?php else: ?>
        <span class="panel-symbol">♞</span>
        <h1 class="reset-heading">Reset password.</h1>
        <form class="member-fields" id="reset-form" action="../backend/resetPassword.php" method="POST">
          <p class="panel-intro">Enter your account email and choose a new password.</p>
          <p class="form-error" id="reset-error"<?= $error === '' ? ' hidden' : '' ?>><?= htmlspecialchars($error) ?></p>

          <label for="reset-email">
            Email address
            <div class="input-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
              <input id="reset-email" name="email" type="email" autocomplete="email" maxlength="255" placeholder="you@example.com" required>
            </div>
          </label>

          <label for="new-password">
            New password
            <div class="input-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
              <input id="new-password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>
            </div>
          </label>

          <label for="confirm">
            Confirm password
            <div class="input-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
              <input id="confirm" name="confirm" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>
            </div>
          </label>

          <button class="btn btn-default form-button" type="submit" id="reset-btn">Update password</button>
        </form>
      <?php endif; ?>
    </div>
  </main>
  <script src="../assets/js/app.js" defer></script>
</body>
</html>
