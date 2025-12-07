document.addEventListener("DOMContentLoaded", function () {
  var items = document.querySelectorAll(".faq-section-wrapper--box-item");

  Array.prototype.forEach.call(items, function (item) {
    var question = item.querySelector(".question");
    if (!question) return;

    question.addEventListener("click", function () {
      var isActive = item.classList.contains("active");

      Array.prototype.forEach.call(items, function (other) {
        other.classList.remove("active");
      });

      if (!isActive) {
        item.classList.add("active");
      }
    });
  });
});
