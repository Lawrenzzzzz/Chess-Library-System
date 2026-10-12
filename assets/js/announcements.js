(function () {
  "use strict";

  var body = document.body;
  var role = body.dataset.role || "guest";
  var api = body.dataset.announcementsApi || "";

  if (role !== "admin" || !api) {
    return;
  }

  var modal = document.createElement("div");
  modal.className = "ab-modal";
  modal.hidden = true;
  modal.innerHTML = [
    '<div class="ab-backdrop" data-ab-close></div>',
    '<div class="ab-dialog" role="dialog" aria-modal="true" aria-labelledby="an-modal-title">',
    '  <h2 id="an-modal-title" data-an-title>New announcement</h2>',
    '  <p class="ab-sub" data-an-sub>Post an update for the community.</p>',
    '  <form class="ab-form" novalidate>',
    '    <label class="ab-field"><span>Title</span><input type="text" name="title" placeholder="e.g. New tournament announced" autocomplete="off"></label>',
    '    <label class="ab-field"><span>Message</span><textarea name="body" rows="5" placeholder="Write your announcement..."></textarea></label>',
    '    <label class="ab-field"><span>Tag <em>(optional)</em></span><input type="text" name="tag" list="an-tags" placeholder="e.g. Community update" autocomplete="off"></label>',
    '    <label class="ab-check"><input type="checkbox" name="pinned"> <span>Pin to top</span></label>',
    '    <p class="ab-error" hidden></p>',
    '    <div class="ab-actions">',
    '      <button type="button" class="ab-cancel" data-ab-close>Cancel</button>',
    '      <button type="submit" class="ab-submit" data-an-submit>Post</button>',
    '    </div>',
    '  </form>',
    "</div>"
  ].join("");

  var datalist = document.createElement("datalist");
  datalist.id = "an-tags";
  var seen = {};
  Array.prototype.forEach.call(document.querySelectorAll(".post .tag"), function (el) {
    var value = el.textContent.trim();
    if (value && !seen[value]) {
      seen[value] = true;
      var option = document.createElement("option");
      option.value = value;
      datalist.appendChild(option);
    }
  });
  modal.appendChild(datalist);
  document.body.appendChild(modal);

  var form = modal.querySelector(".ab-form");
  var titleEl = modal.querySelector("[data-an-title]");
  var subEl = modal.querySelector("[data-an-sub]");
  var submitEl = modal.querySelector("[data-an-submit]");
  var errorEl = modal.querySelector(".ab-error");
  var mode = "create";

  function setError(msg) {
    errorEl.textContent = msg || "";
    errorEl.hidden = !msg;
  }

  function openModal(nextMode, data) {
    mode = nextMode;
    setError("");
    form.reset();

    if (nextMode === "edit" && data) {
      titleEl.textContent = "Edit announcement";
      subEl.textContent = "Update this message.";
      submitEl.textContent = "Save changes";
      form.title.value = data.title || "";
      form.body.value = data.body || "";
      form.tag.value = data.tag || "";
      form.pinned.checked = data.pinned === "1";
      modal.dataset.id = data.id || "";
    } else {
      titleEl.textContent = "New announcement";
      subEl.textContent = "Post an update for the community.";
      submitEl.textContent = "Post";
      modal.dataset.id = "";
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

  Array.prototype.forEach.call(document.querySelectorAll("[data-add-announcement]"), function (btn) {
    btn.addEventListener("click", function () {
      openModal("create", null);
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll("[data-announce-edit]"), function (btn) {
    btn.addEventListener("click", function () {
      openModal("edit", btn.dataset);
    });
  });

  Array.prototype.forEach.call(document.querySelectorAll("[data-announce-delete]"), function (btn) {
    btn.addEventListener("click", function () {
      if (!window.confirm("Delete this announcement?")) {
        return;
      }
      var data = new FormData();
      data.append("action", "delete");
      data.append("id", parseInt(btn.dataset.id || "0", 10));
      post(data).then(function (res) {
        if (res && res.error) {
          window.alert(res.error);
          return;
        }
        location.reload();
      });
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
    var message = form.body.value.trim();
    if (!title) {
      setError("Please enter a title.");
      return;
    }
    if (!message) {
      setError("Please write a message.");
      return;
    }

    var data = new FormData();
    data.append("action", mode);
    data.append("id", parseInt(modal.dataset.id || "0", 10));
    data.append("title", title);
    data.append("body", message);
    data.append("tag", form.tag.value.trim());
    data.append("pinned", form.pinned.checked ? "1" : "0");

    post(data)
      .then(function (res) {
        if (res && res.error) {
          setError(res.error);
          return;
        }
        location.reload();
      })
      .catch(function () {
        setError("Network error. Please try again.");
      });
  });
})();
