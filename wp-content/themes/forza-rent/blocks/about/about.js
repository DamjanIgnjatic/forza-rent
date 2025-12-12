document.addEventListener("DOMContentLoaded", function () {
  // === CAR FILTERS ===
  const cars = document.querySelectorAll(".grid .car-card");
  const noResults = document.querySelector(".no-results");
  const categorySelect = document.getElementById("category");
  const yearSelect = document.getElementById("yearFilter");
  const gearboxSelect = document.getElementById("gearboxFilter");
  const driveTypeSelect = document.getElementById("driveFilter");
  const priceRange = document.getElementById("priceRange");
  const priceLabel = document.querySelector(".price-label");

  console.log(driveTypeSelect);
  console.log(gearboxSelect);

  const hamburger = document.querySelector(".hamburger");
  const navItems = document.querySelectorAll(".theme-menu-content");
  const socialIcons = document.querySelectorAll(
    ".navigation--items .social-media-icons"
  );
  const body = document.body;

  hamburger.addEventListener("click", () => {
    body.classList.toggle("menu-open");
  });

  navItems.forEach((item) => {
    item.addEventListener("click", () => {
      body.classList.remove("menu-open");
    });
  });

  socialIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      body.classList.remove("menu-open");
    });
  });

  const loadMoreBtn = document.getElementById("loadMoreCars");
  let visibleCount = 12;
  let priceFilterActive = false;

  function applyFilters(resetVisible = false) {
    if (resetVisible) visibleCount = 12;

    const selectedCategory = (categorySelect?.value || "").toLowerCase().trim();
    const selectedYear = (yearSelect?.value || "").trim();
    const selectedGearbox = (gearboxSelect?.value || "").trim();
    const selectedDriveType = (driveTypeSelect?.value || "").trim();
    console.log(selectedGearbox);
    console.log(selectedDriveType);

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

      console.log(driveType);

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
      if (priceFilterActive && maxPrice !== null && carPrice > maxPrice) {
        visible = false;
      }

      if (visible) matchingCars.push(car);
    });

    // Hide all, show only filtered
    cars.forEach((car) => (car.style.display = "none"));

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
    const min = parseFloat(priceRange.min);
    const max = parseFloat(priceRange.max);

    priceLabel.textContent = `Cena: €${value}/dan`;

    let percent = ((value - min) / (max - min)) * 100;
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

  if (gearboxSelect) {
    gearboxSelect.addEventListener("change", () => applyFilters(true));
  }

  if (driveTypeSelect) {
    driveTypeSelect.addEventListener("change", () => applyFilters(true));
  }

  if (priceRange) {
    priceRange.addEventListener("input", () => {
      priceFilterActive = true;
      updatePriceUI();
    });
  }

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", function () {
      visibleCount += 12;
      applyFilters(false);
    });
  }

  setTimeout(updatePriceUI, 30);
  updatePriceUI();

  //DATE RANGE VALIDATION
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

  // hamburger
});
