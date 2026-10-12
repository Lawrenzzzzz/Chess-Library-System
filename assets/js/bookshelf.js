(function () {
  "use strict";

  var list = document.querySelector("[data-shelf-list]");
  if (!list) {
    return;
  }

  var rows = Array.prototype.slice.call(list.querySelectorAll(".row"));
  var toggle = document.querySelector("[data-shelf-toggle]");
  var hiddenCount = list.querySelectorAll(".shelf-extra").length;

  if (toggle && hiddenCount > 0) {
    toggle.hidden = false;
    toggle.addEventListener("click", function () {
      var expanded = list.classList.toggle("is-expanded");
      toggle.textContent = expanded ? "Show less" : "View all";
    });
  }

  rows.forEach(function (row) {
    if (row.disabled || !row.dataset.file) {
      return;
    }
    row.addEventListener("click", function () {
      if (window.ChessPDF) {
        window.ChessPDF.open(row.dataset.file, row.dataset.title || "Book");
      } else {
        window.open(row.dataset.file, "_blank", "noopener");
      }
    });
  });
})();
