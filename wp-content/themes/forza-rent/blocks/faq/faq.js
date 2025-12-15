document.addEventListener("DOMContentLoaded", function () {
  const items = Array.from(
    document.querySelectorAll(".faq-section-wrapper--box-item")
  );

  if (!items.length) return;

  items.forEach((item) => {
    item.addEventListener("click", function () {
      const isActive = item.classList.contains("active");

      items.forEach((i) => {
        i.classList.remove("active");
      });

      if (!isActive) {
        item.classList.add("active");
      }
    });
  });
});
