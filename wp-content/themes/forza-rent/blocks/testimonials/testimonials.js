// DOM content loaded event listener
document.addEventListener("DOMContentLoaded", function () {
  const btnNext = document.querySelector(".btn-next");
  const btnPrev = document.querySelector(".btn-prev");
  const descirption = document.querySelectorAll(".testimonial");

  const totalSlidesCount = descirption.length;
  const realSlidesCount = totalSlidesCount - 2;
  const TRANSITION_DURATION = 500;

  let curSlide = 1;
  descirption[curSlide].classList.add("active");
  const sliderContainer = descirption[0].parentElement;

  let startX = 0;
  let startY = 0;
  let isDragging = false;
  const SWIPE_THRESHOLD = 70;

  descirption.forEach((s, i) => {
    if (i === curSlide) {
      s.style.transform = "translateX(0)";
    } else {
      s.style.transform = `translateX(${100 * (i - curSlide)}%)`;
    }
  });

  const setTransition = (duration) => {
    descirption.forEach(
      (slide) => (slide.style.transition = `transform ${duration}ms ease-out`)
    );
  };

  const updateDisplay = (slideIndex) => {
    let realIndex = slideIndex;

    if (slideIndex === 0) {
      realIndex = realSlidesCount;
    } else if (slideIndex === totalSlidesCount - 1) {
      realIndex = 1;
    } else {
      realIndex = slideIndex;
    }
  };

  const goToSlide = function (slide) {
    setTransition(TRANSITION_DURATION);

    descirption.forEach((s, i) => {
      s.style.transform = `translateX(${100 * (i - slide)}%)`;
    });

    curSlide = slide;
    updateDisplay(curSlide);
    descirption.forEach((slide) => slide.classList.remove("active"));

    descirption[curSlide].classList.add("active");

    if (curSlide === totalSlidesCount - 1) {
      setTimeout(() => {
        setTransition(0);

        descirption.forEach((s, i) => {
          s.style.transform = `translateX(${100 * (i - 1)}%)`;
        });
        curSlide = 1;
        setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      }, TRANSITION_DURATION);
    } else if (curSlide === 0) {
      setTimeout(() => {
        setTransition(0);

        descirption.forEach((s, i) => {
          s.style.transform = `translateX(${100 * (i - realSlidesCount)}%)`;
        });
        curSlide = realSlidesCount;
        setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      }, TRANSITION_DURATION);
    }
  };

  const nextSlide = function () {
    goToSlide(curSlide + 1);
  };

  const prevSlide = function () {
    goToSlide(curSlide - 1);
  };

  sliderContainer.addEventListener("mousedown", (e) => {
    if (e.button !== 0) return;
    isDragging = true;
    startX = e.clientX;
    e.preventDefault();
    setTransition(0);
  });

  sliderContainer.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    const currentX = e.clientX;
    const diffX = currentX - startX;

    descirption.forEach((s, i) => {
      const currentPos = 100 * (i - curSlide);
      const movePercentage = (diffX / sliderContainer.offsetWidth) * 100;
      s.style.transform = `translateX(${currentPos + movePercentage}%)`;
    });
  });

  document.addEventListener("mouseup", (e) => {
    if (!isDragging) return;
    isDragging = false;

    const endX = e.clientX;
    const diffX = endX - startX;
    setTransition(TRANSITION_DURATION);

    if (Math.abs(diffX) >= SWIPE_THRESHOLD) {
      if (diffX > 0) {
        prevSlide();
      } else {
        nextSlide();
      }
    } else {
      goToSlide(curSlide);
    }
  });

  document.addEventListener("mouseleave", () => {
    if (isDragging) {
      isDragging = false;
      setTransition(TRANSITION_DURATION);
      goToSlide(curSlide);
    }
  });

  sliderContainer.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    setTransition(0);
  });

  sliderContainer.addEventListener("touchmove", (e) => {
    const currentX = e.touches[0].clientX;
    const currentY = e.touches[0].clientY;

    const deltaX = currentX - startX;
    const deltaY = currentY - startY;

    if (Math.abs(deltaX) > Math.abs(deltaY)) {
      e.preventDefault();

      descirption.forEach((s, i) => {
        const currentPos = 100 * (i - curSlide);
        const movePercentage = (deltaX / sliderContainer.offsetWidth) * 100;
        s.style.transform = `translateX(${currentPos + movePercentage}%)`;
      });
    }
  });

  sliderContainer.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    const diffX = endX - startX;
    setTransition(TRANSITION_DURATION);

    if (Math.abs(diffX) >= SWIPE_THRESHOLD) {
      if (diffX > 0) {
        prevSlide();
      } else {
        nextSlide();
      }
    } else {
      goToSlide(curSlide);
    }

    startX = 0;
    startY = 0;
  });

  btnNext.addEventListener("click", nextSlide);
  btnPrev.addEventListener("click", prevSlide);

  document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") prevSlide();
    e.key === "ArrowRight" && nextSlide();
  });

  const init = () => {
    if (descirption.length > 1) {
      setTransition(0);
      descirption.forEach((slide, i) => {
        slide.style.transform = `translateX(${100 * (i - curSlide)}%)`;
      });

      updateDisplay(curSlide);
      setTimeout(() => setTransition(TRANSITION_DURATION), 50);
    }
  };

  init();
});
