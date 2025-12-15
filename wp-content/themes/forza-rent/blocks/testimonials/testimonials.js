const $ = jQuery;
document.addEventListener("DOMContentLoaded", () => {
  $(".testimonials-section-wrapper--box-items").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    autoplay: false,
    autoplaySpeed: 0,
    prevArrow: $(".btn-prev"),
    nextArrow: $(".btn-next"),
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
});
