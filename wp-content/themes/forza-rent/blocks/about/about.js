document.addEventListener("DOMContentLoaded", function () {
  // === CAR FILTERS ===
  const cars = document.querySelectorAll(".grid .car-card");
  const noResults = document.querySelector(".no-results");
  const categorySelect = document.getElementById("category");
  const yearSelect = document.getElementById("yearFilter");
  const gearboxSelect = document.getElementById("gearboxFilter");
  const priceRange = document.getElementById("priceRange");
  const priceLabel = document.querySelector(".price-label");

  const loadMoreBtn = document.getElementById("loadMoreCars");
  let visibleCount = 12;
  let priceFilterActive = false;

  if (!cars.length) return;

  function normalizeGearbox(value) {
    const v = (value || "").toLowerCase().trim();

    if (["auto", "automatic", "automat", "automatika"].includes(v)) {
      return "automatic";
    }
    if (["manual", "man", "rucni", "ručni"].includes(v)) {
      return "manual";
    }
    return v;
  }

  function applyFilters(resetVisible = false) {
    if (resetVisible) visibleCount = 12;

    const selectedCategory = (categorySelect?.value || "").toLowerCase().trim();
    const selectedYear = (yearSelect?.value || "").trim();
    const selectedGearbox = normalizeGearbox(gearboxSelect?.value || "");

    const maxPrice =
      priceRange && priceRange.value !== ""
        ? parseFloat(priceRange.value)
        : null;

    const matchingCars = [];

    cars.forEach(function (car) {
      const carCategory = (car.dataset.category || "").toLowerCase().trim();
      const carYear = (car.dataset.year || "").trim();
      const carGearbox = normalizeGearbox(car.dataset.gearbox || "");

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

    priceLabel.textContent = `Price: €${value}/day`;

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
});
