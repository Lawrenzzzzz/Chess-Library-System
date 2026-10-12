(function () {
  "use strict";

  var triggers = document.querySelectorAll("[data-add-book]");
  if (!triggers.length) return;

  var STORAGE_PREFIX = "chessRoom.books.";
  var COLORS = ["#7a2e2e", "#2f5d46", "#27415e", "#6b4a1f", "#4a3220", "#1f5c46"];
  var SIZES = [
    { w: 52, h: 184 },
    { w: 56, h: 212 },
    { w: 60, h: 170 },
    { w: 57, h: 226 },
    { w: 44, h: 184 },
    { w: 63, h: 177 }
  ];

  function pageKey() {
    var path = window.location.pathname;
    var name = path.substring(path.lastIndexOf("/") + 1);
    return name || "index";
  }

  function readStore() {
    try {
      var raw = window.localStorage.getItem(STORAGE_PREFIX + pageKey());
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      return [];
    }
  }

  function writeStore(books) {
    try {
      window.localStorage.setItem(STORAGE_PREFIX + pageKey(), JSON.stringify(books));
    } catch (e) {
      /* storage unavailable, ignore */
    }
  }

  function firstShelf() {
    return document.querySelector(".books");
  }

  function makeSpine(book) {
    var btn = document.createElement("button");
    btn.type = "button";
    btn.className = "spine ab-new";
    btn.setAttribute("aria-pressed", "false");

    var size = book.size || SIZES[Math.floor(Math.random() * SIZES.length)];
    book.size = size;
    btn.style.background = book.color || COLORS[Math.floor(Math.random() * COLORS.length)];
    book.color = btn.style.background;
    btn.style.width = size.w + "px";
    btn.style.height = size.h + "px";

    btn.dataset.title = book.title;
    btn.dataset.author = "Added by you";
    btn.dataset.level = "Personal";
    btn.dataset.topic = book.file || "Personal collection";
    btn.setAttribute("aria-label", book.title + ", added by you");

    var span = document.createElement("span");
    span.textContent = book.title;
    btn.appendChild(span);

    btn.addEventListener("click", function () {
      document.querySelectorAll(".spine").forEach(function (s) {
        s.setAttribute("aria-pressed", s === btn ? "true" : "false");
      });
    });

    return btn;
  }

  function addBook(book, persist) {
    var shelf = firstShelf();
    if (!shelf) return;
    shelf.appendChild(makeSpine(book));
    if (persist !== false) {
      var books = readStore();
      books.push({
        title: book.title,
        file: book.file,
        color: book.color,
        size: book.size
      });
      writeStore(books);
    }
  }

  readStore().forEach(function (book) {
    addBook(book, false);
  });

  var modal = document.createElement("div");
  modal.className = "ab-modal";
  modal.hidden = true;
  modal.innerHTML = [
    '<div class="ab-backdrop" data-ab-close></div>',
    '<div class="ab-dialog" role="dialog" aria-modal="true" aria-labelledby="ab-modal-title">',
    '  <h2 id="ab-modal-title">Add a book</h2>',
    '  <p class="ab-sub">Give your book a title and attach its file.</p>',
    '  <form class="ab-form" novalidate>',
    '    <label class="ab-field"><span>Title</span>',
    '      <input type="text" name="title" placeholder="e.g. My System" autocomplete="off">',
    '    </label>',
    '    <label class="ab-field"><span>Book file</span>',
    '      <input type="file" name="file" accept=".pdf,.epub,.djvu,.pgn,.txt,.doc,.docx">',
    '    </label>',
    '    <p class="ab-error" hidden>Please enter a title and choose a file.</p>',
    '    <div class="ab-actions">',
    '      <button type="button" class="ab-cancel" data-ab-close>Cancel</button>',
    '      <button type="submit" class="ab-submit">Add to shelf</button>',
    '    </div>',
    '  </form>',
    "</div>"
  ].join("");
  document.body.appendChild(modal);

  var form = modal.querySelector(".ab-form");
  var titleInput = modal.querySelector('input[name="title"]');
  var fileInput = modal.querySelector('input[name="file"]');
  var errorEl = modal.querySelector(".ab-error");
  var lastFocused = null;

  function clearError() {
    errorEl.hidden = true;
    titleInput.removeAttribute("aria-invalid");
    fileInput.removeAttribute("aria-invalid");
  }

  function openModal() {
    lastFocused = document.activeElement;
    modal.hidden = false;
    document.body.classList.add("ab-lock");
    titleInput.focus();
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove("ab-lock");
    form.reset();
    clearError();
    if (lastFocused && lastFocused.focus) lastFocused.focus();
  }

  function showError() {
    errorEl.hidden = false;
    if (!titleInput.value.trim()) titleInput.setAttribute("aria-invalid", "true");
    if (!fileInput.files.length) fileInput.setAttribute("aria-invalid", "true");
  }

  triggers.forEach(function (trigger) {
    trigger.addEventListener("click", openModal);
  });

  modal.querySelectorAll("[data-ab-close]").forEach(function (el) {
    el.addEventListener("click", closeModal);
  });

  titleInput.addEventListener("input", clearError);
  fileInput.addEventListener("change", clearError);

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modal.hidden) closeModal();
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    clearError();
    var title = titleInput.value.trim();
    var file = fileInput.files[0];
    if (!title || !file) {
      showError();
      return;
    }
    addBook({ title: title, file: file.name });
    closeModal();
  });
})();
