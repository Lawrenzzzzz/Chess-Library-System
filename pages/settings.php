<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile settings</title>
  <meta name="description" content="Manage your chess community profile and demo session.">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <div class="page-heading">
      <div>
        <h1>Profile settings</h1>
        <p>Saved only in this browser.</p>
      </div>
    </div>
    <div class="form-panel">
      <label>Display name<input class="field" id="s-name" placeholder="Guest Player"></label>
      <label>Bio<textarea class="field" id="s-bio" rows="4" placeholder="Tell us about your chess journey"></textarea></label>
      <div class="form-actions">
        <button class="btn primary" id="s-save">Save</button>
        <button class="btn" id="s-reset">Reset demo session</button>
        <span class="form-feedback" id="s-msg"></span>
      </div>
    </div>
  </div>
</body>
</html>