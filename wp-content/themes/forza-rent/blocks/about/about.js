document.addEventListener('DOMContentLoaded', function() {

	// === CAR FILTERS ===
	const filterButtons = document.querySelectorAll('.car-category');
	const cars          = document.querySelectorAll('.grid .car-card');

	const fuelSelect    = document.getElementById('fuelFilter');
	const gearboxSelect = document.getElementById('gearboxFilter');
	const priceRange    = document.getElementById('priceRange');
	const priceLabel    = document.querySelector('.price-label');

	const loadMoreBtn   = document.getElementById('loadMoreCars');
	let visibleCount    = 12;

	if (!cars.length) return;

	function normalizeGearbox(value) {
	const v = (value || '').toLowerCase().trim();

	if (['auto', 'automatic', 'automat', 'automatika'].includes(v)) {
		return 'automatic';
	}

	if (['manual', 'man', 'rucni', 'ručni'].includes(v)) {
		return 'manual';
	}

	return v;
}


	function applyFilters(resetVisible = false) {
		if (resetVisible) {
			visibleCount = 12;
		}

		const activeTypeBtn = document.querySelector('.car-category.active');
		const selectedType = activeTypeBtn
			? activeTypeBtn.textContent.toLowerCase().trim()
			: 'all';

		const selectedFuel    = (fuelSelect?.value || '').toLowerCase().trim();
		const selectedGearbox = normalizeGearbox(gearboxSelect?.value || '');

		const maxPrice = priceRange && priceRange.value !== ''
			? parseFloat(priceRange.value)
			: null;

		const matchingCars = [];

		cars.forEach(function (car) {
			const carType    = (car.dataset.type || '').toLowerCase().trim();
			const carFuel    = (car.dataset.fuel || '').toLowerCase().trim();
			const carGearbox = normalizeGearbox(car.dataset.gearbox || '');
			let   carPrice   = car.dataset.price || null;

			if (carPrice !== null) {
				carPrice = parseFloat(String(carPrice).replace(/[^\d.]/g, ''));
			}

			let visible = true;

			if (selectedType !== 'all' && selectedType !== '' && carType !== selectedType) {
				visible = false;
			}

			if (selectedFuel && carFuel !== selectedFuel) {
				visible = false;
			}

			if (selectedGearbox && carGearbox !== selectedGearbox) {
				visible = false;
			}

			if (maxPrice !== null && (carPrice === null || carPrice > maxPrice)) {
				visible = false;
			}

			if (visible) {
				matchingCars.push(car);
			}
		});

		cars.forEach(function (car) {
			car.style.display = 'none';
		});

		matchingCars.slice(0, visibleCount).forEach(function (car) {
			car.style.display = '';
		});

		if (loadMoreBtn) {
			if (matchingCars.length > visibleCount) {
				loadMoreBtn.style.display = '';
			} else {
				loadMoreBtn.style.display = 'none';
			}
		}
	}

	function updatePriceUI() {
		if (!priceRange || !priceLabel) return;

		const value = parseFloat(priceRange.value) || 0;
		const min   = parseFloat(priceRange.min) || 0;
		const max   = parseFloat(priceRange.max) || 100;
		const percent = ((value - min) / (max - min)) * 100;

		priceRange.style.setProperty('--val', percent);
		priceLabel.textContent = `Max. $${value.toFixed(2)}`;

		applyFilters(true);
	}

	if (filterButtons.length) {
		filterButtons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				filterButtons.forEach(function (b) {
					b.classList.remove('active');
				});
				btn.classList.add('active');
				applyFilters(true);
			});
		});
	}

	[fuelSelect, gearboxSelect].forEach(function (el) {
		if (el) {
			el.addEventListener('change', function() {
				applyFilters(true);
			});
		}
	});

	if (priceRange) {
		priceRange.addEventListener('input', updatePriceUI);
	}

	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function () {
			visibleCount += 12;
			applyFilters(false);
		});
	}

	updatePriceUI();

	//DATE RANGE VALIDATION
	var form = document.getElementById('gform_2');
    if (!form) return;

    var dateFrom = document.getElementById('input_11');
    var dateTo   = document.getElementById('input_12');

    if (!dateFrom || !dateTo) return;

    form.addEventListener('submit', function (e) {
        var fromVal = dateFrom.value.trim();
        var toVal   = dateTo.value.trim();

        if (!fromVal || !toVal) {
            return;
        }

        var fromDate = new Date(fromVal);
        var toDate   = new Date(toVal);

        if (toDate < fromDate) {
            e.preventDefault();
            alert('End date cannot be earlier than start date.');
            dateTo.value = '';
            dateTo.focus();
        }
    });
});
