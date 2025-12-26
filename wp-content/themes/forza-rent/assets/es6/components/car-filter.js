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

  carFilter: function () {
    const cars = document.querySelectorAll(".grid .car-card");
    const noResults = document.querySelector(".no-results");
    const categorySelect = document.getElementById("category");
    const yearSelect = document.getElementById("yearFilter");
    const gearboxSelect = document.getElementById("gearboxFilter");
    const driveTypeSelect = document.getElementById("driveFilter");
    const priceRange = document.getElementById("priceRange");
    const priceLabel = document.querySelector(".price-label");

    const loadMoreBtn = document.getElementById("loadMoreCars");
    let visibleCount = 12;
    // let priceFilterActive = false;

    function applyFilters(resetVisible = false) {
      if (resetVisible) visibleCount = 12;

      const selectedCategory = (categorySelect?.value || "")
        .toLowerCase()
        .trim();
      const selectedYear = (yearSelect?.value || "").trim();
      const selectedGearbox = (gearboxSelect?.value || "").trim();
      const selectedDriveType = (driveTypeSelect?.value || "").trim();

      const maxPrice =
        priceRange && priceRange.value !== ""
          ? parseFloat(priceRange.value)
          : null;

      const matchingCars = [];

      cars.forEach(function (car) {
        const carCategory = (car.dataset.category || "").toLowerCase().trim();
        const carYear = (car.dataset.year || "").trim();
        const carGearbox = (car.dataset.gearbox || "").trim();
        const driveType = (car.dataset.drive || "").trim();

        let carPrice = car.dataset.price || null;
        if (carPrice !== null) {
          carPrice = parseFloat(String(carPrice).replace(/[^\d.]/g, ""));
        }

        let visible = true;

        // CATEGORY filter
        if (selectedCategory && carCategory !== selectedCategory) {
          visible = false;
        }

        // YEAR filter
        if (selectedYear && carYear !== selectedYear) {
          visible = false;
        }

        // GEARBOX filter
        if (selectedGearbox && carGearbox !== selectedGearbox) {
          visible = false;
        }

        // DRIVETYPE filter
        if (selectedDriveType && driveType !== selectedDriveType) {
          visible = false;
        }

        // PRICE filter
        if (
          maxPrice !== null &&
          maxPrice > 0 &&
          carPrice !== null &&
          carPrice > maxPrice
        ) {
          visible = false;
        }

        if (visible) matchingCars.push(car);
      });

      // Hide all, show only filtered
      cars.forEach((car) => {
        if (car.style.display !== "none") {
          car.style.display = "none";
        }
      });

      matchingCars.slice(0, visibleCount).forEach((car) => {
        car.style.display = "";
      });

      if (noResults) {
        if (matchingCars.length === 0) {
          noResults.style.display = "block";
        } else {
          noResults.style.display = "none";
        }
      }

      if (loadMoreBtn) {
        loadMoreBtn.style.display =
          matchingCars.length > visibleCount ? "" : "none";
      }
    }

    function updatePriceUI() {
      if (!priceRange || !priceLabel) return;

      const value = parseFloat(priceRange.value);
      const max = parseFloat(priceRange.max);

      if (value === 0) {
        priceLabel.textContent = `Max: €${max}/dan`;
      } else {
        priceLabel.textContent = `Max: €${value}/dan`;
      }

      const percent = (value / max) * 100;
      priceRange.style.setProperty("--val", percent);

      applyFilters(true);
    }

    // EVENT LISTENERS
    if (categorySelect) {
      categorySelect.addEventListener("change", () => applyFilters(true));
    }

    if (yearSelect) {
      yearSelect.addEventListener("change", () => applyFilters(true));
    }

    if (gearboxSelect) {
      gearboxSelect.addEventListener("change", () => applyFilters(true));
    }

    if (driveTypeSelect) {
      driveTypeSelect.addEventListener("change", () => applyFilters(true));
    }

    if (priceRange) {
      priceRange.addEventListener("input", updatePriceUI);
    }

    if (loadMoreBtn) {
      loadMoreBtn.addEventListener("click", function () {
        visibleCount += 12;
        applyFilters(false);
      });
    }

    updatePriceUI();
  },

  /**
   * Init functions
   */
  init: function () {
    this.carFilter();
  },
});
