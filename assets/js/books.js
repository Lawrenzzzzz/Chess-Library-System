(function () {
  "use strict";

  var body = document.body;
  var role = body.dataset.role || "guest";
  var category = body.dataset.category || "";
  var api = body.dataset.booksApi || "";
  var isAdmin = role === "admin";

  var spines = Array.prototype.slice.call(document.querySelectorAll(".spine"));
  var current = null;

  var reader = null;
  if (spines.length) {
    reader = document.createElement("section");
    reader.className = "reader";
    reader.hidden = true;
    reader.innerHTML =
      '<div class="cover"></div>' +
      "<div>" +
      '<h3 data-r-title></h3><p class="by" data-r-by></p>' +
      "<dl><dt>Level</dt><dd data-r-level></dd><dt>Topic</dt><dd data-r-topic></dd></dl>" +
      '<div class="reader-actions">' +
      '<button type="button" class="read-btn" data-read hidden>Read book</button>' +
      (isAdmin
        ? '<button type="button" class="ab-edit" data-edit>Edit book</button>' +
          '<button type="button" class="ab-delete" data-delete>Delete</button>'
        : "") +
      "</div>" +
      "</div>";

    var content = document.querySelector(".content");
    if (content) {
      content.appendChild(reader);
    }
  }

  function select(el) {
    spines.forEach(function (s) {
      s.setAttribute("aria-pressed", s === el ? "true" : "false");
    });
    current = el;
    if (!reader) {
      return;
    }
    reader.hidden = false;
    var cover = reader.querySelector(".cover");
    cover.style.background = el.style.background;
    cover.textContent = el.dataset.title || "";
    reader.querySelector("[data-r-title]").textContent = el.dataset.title || "";
    reader.querySelector("[data-r-by]").textContent = el.dataset.author || "";
    reader.querySelector("[data-r-level]").textContent = el.dataset.level || "\u2014";
    reader.querySelector("[data-r-topic]").textContent = el.dataset.topic || "\u2014";

    var readBtn = reader.querySelector("[data-read]");
    if (el.dataset.file) {
      readBtn.hidden = false;
      readBtn.dataset.url = el.dataset.file;
      readBtn.dataset.title = el.dataset.title || "Book";
    } else {
      readBtn.hidden = true;
      readBtn.dataset.url = "";
    }
  }

  spines.forEach(function (s) {
    s.addEventListener("click", function () {
      select(s);
    });
  });

  if (spines.length) {
    select(spines[0]);
  }

  /* ---------------- PDF reader (all users) ---------------- */
  var viewer = document.createElement("div");
  viewer.className = "pdf-modal";
  viewer.hidden = true;
  viewer.innerHTML = [
    '<div class="pdf-backdrop" data-pdf-close></div>',
    '<div class="pdf-dialog" role="dialog" aria-modal="true" aria-label="Book reader">',
    '  <div class="pdf-head">',
    '    <span class="pdf-title" data-pdf-title></span>',
    '    <div class="pdf-tools">',
    '      <a class="pdf-open" data-pdf-open target="_blank" rel="noopener">Open in new tab</a>',
    '      <button type="button" class="pdf-close" data-pdf-close>Close</button>',
    '    </div>',
    '  </div>',
    '  <iframe class="pdf-frame" data-pdf-frame title="PDF viewer"></iframe>',
    "</div>"
  ].join("");
  document.body.appendChild(viewer);

  var frame = viewer.querySelector("[data-pdf-frame]");
  var frameTitle = viewer.querySelector("[data-pdf-title]");
  var openLink = viewer.querySelector("[data-pdf-open]");

  function openViewer(url, title) {
    frameTitle.textContent = title || "Book";
    openLink.href = url;
    frame.src = url;
    viewer.hidden = false;
    document.body.classList.add("ab-lock");
  }

  function closeViewer() {
    viewer.hidden = true;
    frame.src = "about:blank";
    document.body.classList.remove("ab-lock");
  }

  if (reader) {
    reader.querySelector("[data-read]").addEventListener("click", function () {
      var btn = this;
      if (btn.dataset.url) {
        openViewer(btn.dataset.url, btn.dataset.title);
      }
    });
  }

  Array.prototype.forEach.call(viewer.querySelectorAll("[data-pdf-close]"), function (el) {
    el.addEventListener("click", closeViewer);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !viewer.hidden) {
      closeViewer();
    }
  });

  if (!isAdmin) {
    return;
  }

  /* ---------------- Admin: modal + CRUD ---------------- */
  var modal = document.createElement("div");
  modal.className = "ab-modal";
  modal.hidden = true;
  modal.innerHTML = [
    '<div class="ab-backdrop" data-ab-close></div>',
    '<div class="ab-dialog" role="dialog" aria-modal="true" aria-labelledby="ab-modal-title">',
    '  <h2 id="ab-modal-title" data-ab-title>Add a book</h2>',
    '  <p class="ab-sub" data-ab-sub>Add a title to this collection.</p>',
    '  <form class="ab-form" novalidate>',
    '    <label class="ab-field"><span>Title</span><input type="text" name="title" placeholder="e.g. My System" autocomplete="off"></label>',
    '    <label class="ab-field"><span>Author</span><input type="text" name="author" placeholder="e.g. Aron Nimzowitsch" autocomplete="off"></label>',
    '    <label class="ab-field"><span>Section</span><input type="text" name="section" list="ab-sections" placeholder="e.g. Strategy" autocomplete="off"></label>',
    '    <label class="ab-field"><span>Level <em>(optional)</em></span><input type="text" name="level" placeholder="e.g. Intermediate" autocomplete="off"></label>',
    '    <label class="ab-field"><span>Topic <em>(optional)</em></span><input type="text" name="topic" placeholder="e.g. King attack" autocomplete="off"></label>',
    '    <label class="ab-field"><span>PDF file <em data-file-hint>(required)</em></span><input type="file" name="file" accept="application/pdf,.pdf"></label>',
    '    <p class="ab-error" hidden></p>',
    '    <div class="ab-actions">',
    '      <button type="button" class="ab-cancel" data-ab-close>Cancel</button>',
    '      <button type="submit" class="ab-submit" data-ab-submit>Add to shelf</button>',
    '    </div>',
    '  </form>',
    "</div>"
  ].join("");

  var datalist = document.createElement("datalist");
  datalist.id = "ab-sections";
  var seen = {};
  spines.forEach(function (s) {
    var sec = s.dataset.section || "";
    if (sec && !seen[sec]) {
      seen[sec] = true;
      var option = document.createElement("option");
      option.value = sec;
      datalist.appendChild(option);
    }
  });
  modal.appendChild(datalist);
  document.body.appendChild(modal);

  var form = modal.querySelector(".ab-form");
  var titleEl = modal.querySelector("[data-ab-title]");
  var subEl = modal.querySelector("[data-ab-sub]");
  var submitEl = modal.querySelector("[data-ab-submit]");
  var errorEl = modal.querySelector(".ab-error");
  var fileHint = modal.querySelector("[data-file-hint]");
  var mode = "create";

  function setError(msg) {
    errorEl.textContent = msg || "";
    errorEl.hidden = !msg;
  }

  function openModal(nextMode, spine) {
    mode = nextMode;
    setError("");
    form.reset();

    if (nextMode === "edit" && spine) {
      titleEl.textContent = "Edit book";
      subEl.textContent = "Update the details for this book.";
      submitEl.textContent = "Save changes";
      form.title.value = spine.dataset.title || "";
      form.author.value = spine.dataset.author || "";
      form.section.value = spine.dataset.section || "";
      form.level.value = spine.dataset.level || "";
      form.topic.value = spine.dataset.topic || "";
      modal.dataset.id = spine.dataset.id || "";
      form.file.required = false;
      fileHint.textContent = spine.dataset.fileName
        ? "(current: " + spine.dataset.fileName + " \u2014 choose a new file to replace)"
        : "(optional)";
    } else {
      titleEl.textContent = "Add a book";
      subEl.textContent = "Add a title to this collection.";
      submitEl.textContent = "Add to shelf";
      modal.dataset.id = "";
      form.section.value = spines.length ? (spines[0].dataset.section || "") : "";
      form.file.required = true;
      fileHint.textContent = "(required)";
    }

    modal.hidden = false;
    document.body.classList.add("ab-lock");
    form.title.focus();
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove("ab-lock");
  }

  function post(formData) {
    return fetch(api, { method: "POST", body: formData }).then(function (res) {
      return res.json().catch(function () {
        return {};
      });
    });
  }

  Array.prototype.forEach.call(document.querySelectorAll("[data-add-book]"), function (btn) {
    btn.addEventListener("click", function () {
      openModal("create", null);
    });
  });

  Array.prototype.forEach.call(modal.querySelectorAll("[data-ab-close]"), function (el) {
    el.addEventListener("click", closeModal);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modal.hidden) {
      closeModal();
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var title = form.title.value.trim();
    if (!title) {
      setError("Please enter a title.");
      return;
    }

    var file = form.file.files[0];
    if (mode === "create" && !file) {
      setError("Please choose a PDF file to upload.");
      return;
    }

    var data = new FormData();
    data.append("action", mode);
    data.append("id", parseInt(modal.dataset.id || "0", 10));
    data.append("category", category);
    data.append("title", title);
    data.append("author", form.author.value.trim());
    data.append("section", form.section.value.trim());
    data.append("level", form.level.value.trim());
    data.append("topic", form.topic.value.trim());
    if (file) {
      data.append("file", file);
    }

    post(data)
      .then(function (data) {
        if (data && data.error) {
          setError(data.error);
          return;
        }
        location.reload();
      })
      .catch(function () {
        setError("Network error. Please try again.");
      });
  });

  if (reader) {
    reader.querySelector("[data-edit]").addEventListener("click", function () {
      if (current) {
        openModal("edit", current);
      }
    });

    reader.querySelector("[data-delete]").addEventListener("click", function () {
      if (!current) {
        return;
      }
      if (!window.confirm('Delete "' + (current.dataset.title || "this book") + '"?')) {
        return;
      }
      var data = new FormData();
      data.append("action", "delete");
      data.append("id", parseInt(current.dataset.id || "0", 10));
      post(data).then(function (res) {
        if (res && res.error) {
          window.alert(res.error);
          return;
        }
        location.reload();
      });
    });
  }
})();
