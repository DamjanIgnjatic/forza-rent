// DOM content loaded event listener
document.addEventListener("DOMContentLoaded", function () {
  const featured = document.getElementById("featuredImage");
  const thumbs = document.querySelectorAll(".car-gallery-items .item");

  thumbs.forEach((item) => {
    item.addEventListener("click", () => {
      const img = item.querySelector("img");

      featured.src = img.src;

      thumbs.forEach((i) => i.classList.remove("active"));
      item.classList.add("active");
    });
  });
});
