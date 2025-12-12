document.addEventListener("DOMContentLoaded", function () {
  const track = document.querySelector(".track");
  if (!track) return;

  const speed = 0.9;

  const originalContent = track.innerHTML;
  track.innerHTML += originalContent;

  let position = 0;

  const animate = () => {
    position -= speed;

    track.style.transform = `translateX(${position}px)`;

    if (Math.abs(position) >= track.scrollWidth / 2) {
      position = 0;
    }

    requestAnimationFrame(animate);
  };

  animate();
});
