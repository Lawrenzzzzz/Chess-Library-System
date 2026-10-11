<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Community library</title>
  <meta name="description" content="Discover and share chess books with The Chess Room.">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <div class="page-heading">
      <div>
        <h1>Community library</h1>
        <p>Discover and share chess books.</p>
      </div>
      <button class="btn primary" id="open-share">Share a book</button>
    </div>
    <div class="library-grid" id="library">
      <!-- Static placeholder items -->
      <div class="library-item">
        <div class="cover-placeholder"></div>
        <h3>My System</h3>
        <p>Aron Nimzowitsch</p>
        <div class="book-info"><small>Shared by <span>Guest Player</span></small></div>
      </div>
      <div class="library-item">
        <div class="cover-placeholder"></div>
        <h3>Bobby Fischer Teaches Chess</h3>
        <p>Bobby Fischer</p>
        <div class="book-info"><small>Shared by <span>Guest Player</span></small></div>
      </div>
    </div>
  </div>

  <div class="modal-backdrop" id="share-modal" hidden>
    <div class="modal">
      <div class="modal-heading">
        <h2>Share a book</h2>
        <button class="icon-btn" id="close-share" aria-label="Close">✕</button>
      </div>
      <div class="form-panel" style="border:0;padding:0">
        <label>Title<input class="field" id="b-title"></label>
        <label>Author<input class="field" id="b-author"></label>
        <div class="form-actions">
          <button class="btn primary" id="b-add">Add to library</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>