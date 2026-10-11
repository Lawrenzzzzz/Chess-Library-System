(function () {
  var spines = document.querySelectorAll(".spine");
  var r = function (id) {
    return document.getElementById(id);
  };
  function pick(el) {
    spines.forEach(function (s) {
      s.setAttribute("aria-pressed", s === el ? "true" : "false");
    });
    var c = r("r-cover");
    c.style.background = el.style.background;
    c.textContent = el.dataset.title;
    r("r-title").textContent = el.dataset.title;
    r("r-by").textContent = el.dataset.author;
    r("r-level").textContent = el.dataset.level;
    r("r-topic").textContent = el.dataset.topic;
  }
  spines.forEach(function (s) {
    s.addEventListener("click", function () {
      pick(s);
    });
  });
  if (spines.length) pick(spines[0]);
})();
