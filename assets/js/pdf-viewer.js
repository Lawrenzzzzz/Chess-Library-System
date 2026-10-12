window.ChessPDF = (function () {
  "use strict";

  var viewer = null;
  var frame = null;
  var titleEl = null;
  var openEl = null;
  var built = false;

  function close() {
    if (!viewer) {
      return;
    }
    viewer.hidden = true;
    frame.src = "about:blank";
    document.body.classList.remove("ab-lock");
  }

  function build() {
    if (built) {
      return;
    }

    var el = document.createElement("div");
    viewer = el;
    el.className = "pdf-modal";
    el.hidden = true;
    el.innerHTML = [
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
      '</div>'
    ].join("");
    document.body.appendChild(viewer);

    frame = viewer.querySelector("[data-pdf-frame]");
    titleEl = viewer.querySelector("[data-pdf-title]");
    openEl = viewer.querySelector("[data-pdf-open]");

    var closers = viewer.querySelectorAll("[data-pdf-close]");
    Array.prototype.forEach.call(closers, function (el) {
      el.addEventListener("click", close);
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !viewer.hidden) {
        close();
      }
    });
  }

  function open(url, title) {
    if (!url) {
      return;
    }
    build();
    titleEl.textContent = title || "Book";
    openEl.href = url;
    frame.src = url;
    viewer.hidden = false;
    document.body.classList.add("ab-lock");
  }

  return { open: open, close: close };
})();
