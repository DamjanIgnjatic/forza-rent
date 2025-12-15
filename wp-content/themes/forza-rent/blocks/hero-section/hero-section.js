// DOM content loaded event listener
document.addEventListener("DOMContentLoaded", function () {});

document.addEventListener("submit", function (e) {
  const form = e.target;

  if (form.classList.contains("gform")) {
    return; // dozvoli GF
  }

  e.preventDefault();
});
