(function () {
  let iti = null;

  function showMsg(wrap, text, ok = false) {
    const box = wrap.querySelector(".frb-msg");
    if (!box) return;

    box.style.display = "block";
    box.classList.remove("ok", "err");
    box.classList.add(ok ? "ok" : "err");
    box.textContent = text;
  }

  function getDaysBetween(start, end) {
    const s = new Date(start);
    const e = new Date(end);
    const diff = Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1;
    return diff > 0 ? diff : 0;
  }

  function getPriceForDays(days, prices) {
    if (!prices) return 0;

    if (days >= 30 && prices["30"]) return prices["30"];
    if (days >= 21 && prices["21"]) return prices["21"];
    if (days >= 14 && prices["14"]) return prices["14"];
    if (days >= 8 && prices["8_13"]) return prices["8_13"];
    if (days >= 5 && prices["5_7"]) return prices["5_7"];
    if (days >= 2 && prices["2_4"]) return prices["2_4"];

    return 0;
  }

  function updatePrice(wrap) {
    const start = wrap.querySelector('input[name="start_date"]').value;
    const end = wrap.querySelector('input[name="end_date"]').value;
    const box = wrap.querySelector(".frb-total");
    const pickup = wrap.querySelector('select[name="pickup_location"]').value;
    let total = 0;

    if (!start || !end) {
      box.textContent = "";
      return;
    }

    const days = getDaysBetween(start, end);
    if (days < 2) {
      box.textContent = "Vozilo morate iznajmiti na minimalni period od 2 dana";
      return;
    }

    if (days > 31) {
      box.innerHTML = `<a href="tel:+381649222057" style="color:#c00;font-weight:500">Za period duži od Mesec Dana – kontaktirajte nas.</a>`;
      return;
    }

    const price = getPriceForDays(days, FR_BOOKING.prices);

    if (!price) {
      box.textContent = "";
      return;
    }

    if (days >= 2 && days <= 13) {
      total = price * days;
    } else {
      total = price;
    }

    if (pickup === "custom") {
      total += 20;
    }

    box.textContent = `Ukupna cena za ${days} dana: €${total}`;
  }

  function toFlatpickrRanges(blocked) {
    return blocked.map((r) => ({
      from: r.start_date,
      to: r.end_date,
    }));
  }

  function getPayload(wrap) {
    const getVal = (selector) => wrap.querySelector(selector)?.value || "";

    return {
      car_id: parseInt(wrap.dataset.frbCarId, 10),
      start_date: getVal('input[name="start_date"]'),
      end_date: getVal('input[name="end_date"]'),
      customer_first_name: getVal('input[name="customer_first_name"]'),
      customer_last_name: getVal('input[name="customer_last_name"]'),
      customer_email: getVal('input[name="customer_email"]'),
      customer_phone: iti ? iti.getNumber() : "",
      notes: getVal('textarea[name="notes"]'),
      pickup_location: getVal('select[name="pickup_location"]'),
      dropoff_location: getVal('select[name="dropoff_location"]'),
      pickup_address: getVal('input[name="pickup_address"]'),
      dropoff_address: getVal('input[name="dropoff_address"]'),
    };
  }

  async function post(endpoint, payload) {
    const res = await fetch(FR_BOOKING.restUrl + endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FR_BOOKING.nonce,
      },
      body: JSON.stringify(payload),
    });

    const data = await res.json().catch(() => ({}));
    return { ok: res.ok, data };
  }

  async function fetchCalendar(carId) {
    const res = await fetch(FR_BOOKING.restUrl + "/calendar?car_id=" + carId);
    return await res.json();
  }

  document.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-frb-action]");
    if (!btn) return;

    const wrap = btn.closest(".frb-wrap");
    if (!wrap) return;

    e.preventDefault();

    if (!iti) {
      showMsg(wrap, "Telefon nije inicijalizovan.", false);
      return;
    }

    const payload = getPayload(wrap);

    if (!payload.start_date || !payload.end_date) {
      showMsg(wrap, "Izaberi datume (od–do).", false);
      return;
    }

    if (!payload.customer_first_name || !payload.customer_last_name) {
      showMsg(wrap, "Ime i prezime su obavezni.", false);
      return;
    }

    if (!payload.customer_phone) {
      showMsg(wrap, "Broj telefona je obavezan.", false);
      return;
    }

    if (!iti.isValidNumber()) {
      showMsg(wrap, "Unesite ispravan broj telefona.", false);
      return;
    }

    const r = await post("/book", payload);
    showMsg(wrap, r.data?.message || "Greška", r.ok);
  });

  document.addEventListener("DOMContentLoaded", async () => {
    const wrap = document.querySelector(".frb-wrap");
    if (!wrap) return;

    const carId = parseInt(wrap.dataset.frbCarId, 10);

    const startInput = wrap.querySelector('input[name="start_date"]');
    const endInput = wrap.querySelector('input[name="end_date"]');
    const phoneInput = wrap.querySelector("#frb-phone");

    if (!startInput || !endInput || !phoneInput) {
      console.error("FR BOOKING: missing inputs");
      return;
    }

    // 📅 blokirani datumi
    const blocked = await fetchCalendar(carId);
    const disabledRanges = toFlatpickrRanges(blocked);

    // ☎️ phone input
    iti = window.intlTelInput(phoneInput, {
      initialCountry: "rs",
      separateDialCode: true,
      preferredCountries: ["rs", "de", "at", "ch"],
      utilsScript:
        "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    });

    // 👇 END PICKER (PRVO)
    const endPicker = flatpickr(endInput, {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d-m-Y",
      minDate: "today",
      disable: disabledRanges,
      onChange() {
        const msg = wrap.querySelector(".frb-msg");
        if (msg) msg.style.display = "none";

        updatePrice(wrap);
      },
    });

    flatpickr(startInput, {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d-m-Y",
      minDate: "today",
      disable: disabledRanges,

      onChange(selectedDates) {
        const msg = wrap.querySelector(".frb-msg");
        if (msg) msg.style.display = "none";

        if (!selectedDates.length) return;

        const newStart = selectedDates[0];

        if (endPicker.selectedDates.length) {
          const currentEnd = endPicker.selectedDates[0];
          if (currentEnd < newStart) {
            endPicker.clear();
          }
        }

        updatePrice(wrap);
      },
    });

    const pickupSelect = wrap.querySelector('select[name="pickup_location"]');
    const pickupAddress = wrap.querySelector('input[name="pickup_address"]');

    const dropoffSelect = wrap.querySelector('select[name="dropoff_location"]');
    const dropoffAddress = wrap.querySelector('input[name="dropoff_address"]');

    // PICKUP LOGIKA
    pickupSelect.addEventListener("change", () => {
      if (pickupSelect.value === "custom") {
        pickupAddress.style.display = "block";
      } else {
        pickupAddress.style.display = "none";
        pickupAddress.value = "";
      }

      updatePrice(wrap);
    });

    // DROPOFF LOGIKA
    dropoffSelect.addEventListener("change", () => {
      if (dropoffSelect.value === "custom") {
        dropoffAddress.style.display = "block";
      } else {
        dropoffAddress.style.display = "none";
        dropoffAddress.value = "";
      }
    });
  });
})();
