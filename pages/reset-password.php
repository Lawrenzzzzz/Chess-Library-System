<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset your password — Chess Library</title>
  <meta name="description" content="Securely set a new password for your Chess Library member account.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;450;500;550;600;650;700&family=Libre+Caslon+Display&display=swap">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
  <main class="reset-page">
    <div class="member-panel">
      <span class="panel-symbol">♞</span>
      <h1 class="reset-heading">A fresh start.</h1>
      
      <form class="member-fields" id="reset-form">
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
      </form>
    </div>
  </main>
  <script src="assets/js/app.js" defer></script>
</body>
</html>