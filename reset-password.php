<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$token = (string)($_GET['token'] ?? '');
$valid = $token !== '' && find_token($token, 'reset') !== null;

page_head(
    'Reset your password — Chess Library',
    'Securely set a new password for your Chess Library member account.'
);
?>
<main class="reset-page">
  <div class="member-panel">
    <span class="panel-symbol">♞</span>
    <h1 class="reset-heading">A fresh start.</h1>

    <div id="reset-done" hidden>
      <p class="form-notice">Your password has been updated.</p>
      <a class="btn btn-default form-button" href="<?= e(url('/')) ?>">Return to Chess Library</a>
    </div>

    <?php if (!$valid): ?>
      <p style="margin-top:24px">This reset link is missing or has expired.</p>
      <a class="btn btn-link" href="<?= e(url('/')) ?>">Return to sign in and request a new link</a>
    <?php else: ?>
      <form class="member-fields" id="reset-form">
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <p class="panel-intro">Choose a new password for your account.</p>
        <label for="new-password">
          New password
          <div class="input-wrap">
            <input id="new-password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>
          </div>
        </label>
        <label for="confirm">
          Confirm password
          <div class="input-wrap">
            <input id="confirm" name="confirm" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>
          </div>
        </label>
        <button class="btn btn-default form-button" type="submit" id="reset-btn">Save new password</button>
        <p role="alert" class="form-error" id="reset-error" hidden></p>
      </form>
    <?php endif; ?>
  </div>
</main>
<?php page_foot(); ?>
