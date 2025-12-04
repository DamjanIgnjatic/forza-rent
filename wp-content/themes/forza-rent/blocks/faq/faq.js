// DOM content loaded event listener
document.addEventListener("DOMContentLoaded", function () {
  const faqAll = document.querySelectorAll(".faq-section-wrapper--box-item");

  faqAll.forEach((question) => {
    question.addEventListener("click", () => {
      faqAll.forEach((q) => {
        q.classList.remove("active");
      });

      question.classList.add("active");
    });
  });
});
