// Example class
let _this = (module.exports = {
  // Set the dom elements
  dom: {
    body: document.querySelector("body"),
  },

  // Set variables
  vars: {
    flag: false,
  },

  carReservation: function () {
    const reservationBtn = document.querySelectorAll(".reservation-btn");

    reservationBtn.forEach((btn) =>
      btn.addEventListener("click", () => {
        const form = document.querySelector(".section-reservation-form");
        console.log(form);
        form.classList.add("show");
      })
    );
  },

  /**
   * Init functions
   */
  init: function () {
    this.carReservation();
  },
});
