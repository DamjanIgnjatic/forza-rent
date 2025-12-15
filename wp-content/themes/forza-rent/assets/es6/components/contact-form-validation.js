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

  validation: function () {
    var form = document.getElementById("gform_2");
    if (!form) return;

    var dateFrom = document.getElementById("input_11");
    var dateTo = document.getElementById("input_12");

    if (!dateFrom || !dateTo) return;

    form.addEventListener("submit", function (e) {
      var fromVal = dateFrom.value.trim();
      var toVal = dateTo.value.trim();

      if (!fromVal || !toVal) {
        return;
      }

      var fromDate = new Date(fromVal);
      var toDate = new Date(toVal);

      if (toDate < fromDate) {
        e.preventDefault();
        alert("End date cannot be earlier than start date.");
        dateTo.value = "";
        dateTo.focus();
      }
    });
  },

  /**
   * Init functions
   */
  init: function () {
    this.validation();
  },
});
