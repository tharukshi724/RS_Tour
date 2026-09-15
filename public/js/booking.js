(function () {
    var form = document.getElementById('bookingForm');
    if (!form) return; // book.php not the current page

    var COLOMBO = [7.2906, 80.3847]; // Mawanella-ish default centre

    /* ---------------------------------------------------------------------
       Pickup / drop location picker — Leaflet + Nominatim, same approach
       used elsewhere on the site. This is the ONLY modal on this page, so
       it never has to stack on top of another popup.
    --------------------------------------------------------------------- */
    var trip = { pickup: null, drop: null };
    var activeField = null;
    var map, marker;

    var mapModal = document.getElementById('bookingMapModal');
    var mapModalTitle = document.getElementById('bookingMapModalTitle');
    var mapModalClose = document.getElementById('bookingMapModalClose');
    var confirmBtn = document.getElementById('bookingMapConfirmBtn');
    var pickedText = document.getElementById('bookingMapPickedText');
    var searchInput = document.getElementById('bookingMapSearchInput');
    var searchBtn = document.getElementById('bookingMapSearchBtn');
    var useCurrentBtn = document.getElementById('bookingUseCurrentBtn');
    var suggestionsBox = document.getElementById('bookingLocationSuggestions');

    var pendingPoint = null;
    var userActed = false;

    var pickupTimeInput = document.getElementById('bookPickupTime');
    if (pickupTimeInput && !pickupTimeInput.value) pickupTimeInput.value = '09:00';

    function openMapModal(field) {
        activeField = field;
        pendingPoint = null;
        userActed = false;
        mapModalTitle.textContent = field === 'pickup' ? 'Set pickup location' : 'Set drop off location';
        pickedText.textContent = 'Tap the map, search, or use current location';
        confirmBtn.disabled = true;
        if (searchInput) searchInput.value = '';
        hideSuggestions();
        mapModal.classList.add('is-open');
        document.body.classList.add('modal-open');

        if (!map) {
            map = L.map('bookingLeafletMap').setView(COLOMBO, 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);
            map.on('click', function (e) { userActed = true; placeMarker(e.latlng.lat, e.latlng.lng, true); });
        }

        setTimeout(function () {
            map.invalidateSize();
            var existing = trip[field];
            if (existing && !userActed) {
                map.setView([existing.lat, existing.lng], 15);
                placeMarker(existing.lat, existing.lng, false, existing.label);
            }
        }, 50);
    }

    function closeMapModal() {
        mapModal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
        activeField = null;
    }

    function placeMarker(lat, lng, reverseGeocode, knownLabel) {
        if (marker) marker.remove();
        marker = L.marker([lat, lng]).addTo(map);
        pendingPoint = { lat: lat, lng: lng, label: knownLabel || null };
        confirmBtn.disabled = false;

        if (knownLabel) {
            pickedText.textContent = knownLabel;
            return;
        }
        if (reverseGeocode) {
            pickedText.textContent = 'Looking up address\u2026';
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var label = (data && data.display_name) ? data.display_name : (lat.toFixed(5) + ', ' + lng.toFixed(5));
                    pendingPoint.label = label;
                    pickedText.textContent = label;
                })
                .catch(function () {
                    var label = lat.toFixed(5) + ', ' + lng.toFixed(5);
                    pendingPoint.label = label;
                    pickedText.textContent = label;
                });
        }
    }

    function runSearch() {
        var q = searchInput.value.trim();
        if (!q) return;
        userActed = true;
        hideSuggestions();
        geocodeAndPlace(q);
    }

    function geocodeAndPlace(query) {
        pickedText.textContent = 'Searching\u2026';
        var q = /sri lanka/i.test(query) ? query : query + ', Sri Lanka';
        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(q))
            .then(function (r) { return r.json(); })
            .then(function (results) {
                if (!results || !results.length) {
                    pickedText.textContent = 'No match found \u2014 try a different search or tap the map.';
                    return;
                }
                var r = results[0];
                var lat = parseFloat(r.lat), lng = parseFloat(r.lon);
                map.setView([lat, lng], 15);
                placeMarker(lat, lng, false, r.display_name);
            })
            .catch(function () {
                pickedText.textContent = 'Search failed \u2014 try tapping the map instead.';
            });
    }

    var suggestionIndex = -1;
    var currentMatches = [];

    function showSuggestions(matches) {
        currentMatches = matches;
        suggestionIndex = -1;
        if (!matches.length) { hideSuggestions(); return; }
        suggestionsBox.innerHTML = matches.map(function (name, i) {
            return '<button type="button" class="location-suggestion" data-index="' + i + '">' + name + '</button>';
        }).join('');
        suggestionsBox.classList.add('is-open');
    }

    function hideSuggestions() {
        suggestionsBox.classList.remove('is-open');
        suggestionsBox.innerHTML = '';
        currentMatches = [];
        suggestionIndex = -1;
    }

    function pickSuggestion(name) {
        searchInput.value = name;
        hideSuggestions();
        userActed = true;
        geocodeAndPlace(name);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = searchInput.value.trim().toLowerCase();
            if (!q || !window.SRI_LANKA_LOCATIONS) { hideSuggestions(); return; }
            var matches = window.SRI_LANKA_LOCATIONS
                .filter(function (name) { return name.toLowerCase().indexOf(q) !== -1; })
                .slice(0, 6);
            showSuggestions(matches);
        });
        searchInput.addEventListener('keydown', function (e) {
            if (suggestionsBox.classList.contains('is-open')) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    suggestionIndex = Math.min(suggestionIndex + 1, currentMatches.length - 1);
                    highlightSuggestion();
                    return;
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    suggestionIndex = Math.max(suggestionIndex - 1, 0);
                    highlightSuggestion();
                    return;
                }
                if (e.key === 'Enter' && suggestionIndex >= 0) {
                    e.preventDefault();
                    pickSuggestion(currentMatches[suggestionIndex]);
                    return;
                }
                if (e.key === 'Escape') { hideSuggestions(); return; }
            }
            if (e.key === 'Enter') { e.preventDefault(); runSearch(); }
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.map-search-input-wrap')) hideSuggestions();
        });
    }
    if (suggestionsBox) {
        suggestionsBox.addEventListener('click', function (e) {
            var btn = e.target.closest('.location-suggestion');
            if (!btn) return;
            pickSuggestion(currentMatches[parseInt(btn.dataset.index, 10)]);
        });
    }
    function highlightSuggestion() {
        var items = suggestionsBox.querySelectorAll('.location-suggestion');
        items.forEach(function (el, i) { el.classList.toggle('is-active', i === suggestionIndex); });
    }

    function useCurrentLocation() {
        if (!navigator.geolocation) {
            pickedText.textContent = 'Your browser does not support location access.';
            return;
        }
        userActed = true;
        pickedText.textContent = 'Getting your location\u2026';
        useCurrentBtn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                useCurrentBtn.disabled = false;
                var lat = pos.coords.latitude, lng = pos.coords.longitude;
                map.setView([lat, lng], 16);
                placeMarker(lat, lng, true);
            },
            function () {
                useCurrentBtn.disabled = false;
                pickedText.textContent = 'Could not get your location \u2014 check location permissions, or pick manually.';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    var pickupBtn = document.getElementById('bookPickupBtn');
    var dropBtn = document.getElementById('bookDropBtn');
    if (pickupBtn) pickupBtn.addEventListener('click', function () { openMapModal('pickup'); });
    if (dropBtn) dropBtn.addEventListener('click', function () { openMapModal('drop'); });
    if (mapModalClose) mapModalClose.addEventListener('click', closeMapModal);
    if (mapModal) mapModal.addEventListener('click', function (e) { if (e.target === mapModal) closeMapModal(); });
    if (searchBtn) searchBtn.addEventListener('click', runSearch);
    if (useCurrentBtn) useCurrentBtn.addEventListener('click', useCurrentLocation);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mapModal.classList.contains('is-open')) closeMapModal();
    });

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!pendingPoint || !activeField) return;
            trip[activeField] = pendingPoint;
            updateLocationUI(activeField);
            closeMapModal();
        });
    }

    function updateLocationUI(field) {
        var valueEl = document.getElementById(field === 'pickup' ? 'bookPickupValue' : 'bookDropValue');
        var btnEl = document.getElementById(field === 'pickup' ? 'bookPickupBtn' : 'bookDropBtn');
        var point = trip[field];
        if (!valueEl || !btnEl || !point) return;
        valueEl.textContent = point.label || (point.lat.toFixed(5) + ', ' + point.lng.toFixed(5));
        valueEl.classList.remove('is-placeholder');
        btnEl.classList.add('is-set');
    }

    /* ---------------------------------------------------------------------
       Shared validation
    --------------------------------------------------------------------- */
    var hint = document.getElementById('bookingLocationHint');

    function validate() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        if (!trip.pickup || !trip.drop) {
            hint.textContent = 'Please set both the pickup and drop off locations.';
            hint.classList.add('is-error');
            return false;
        }
        hint.classList.remove('is-error');
        return true;
    }

    /* ---------------------------------------------------------------------
       "Submit" — no backend here, so this simply confirms the request was
       captured and lets the customer know an agent will call, then sends
       them back to the home page.
    --------------------------------------------------------------------- */
    var submitBtn = document.getElementById('bookingSubmitBtn');
    var confirmModal = document.getElementById('bookingConfirmModal');
    var confirmOkBtn = document.getElementById('bookingConfirmOkBtn');
    var confirmNameEl = document.getElementById('confirmName');

    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            if (!validate()) return;
            var name = form.elements.name.value.trim();
            if (confirmNameEl) confirmNameEl.textContent = name ? name.split(' ')[0] : 'there';
            confirmModal.classList.add('is-open');
            document.body.classList.add('modal-open');
        });
    }
    if (confirmOkBtn) {
        confirmOkBtn.addEventListener('click', function () {
            window.location.href = 'index.php';
        });
    }

    /* ---------------------------------------------------------------------
       "Send on WhatsApp" — builds the trip into a WhatsApp message
    --------------------------------------------------------------------- */
    var whatsappBtn = document.getElementById('bookingWhatsappBtn');
    var waNumber = document.body.getAttribute('data-whatsapp') || window.WHATSAPP_NUMBER;
    var siteName = document.body.getAttribute('data-sitename') || window.SITE_NAME || '';
    var vehicleNameField = document.getElementById('bookVehicleName');
    var vehiclePriceField = document.getElementById('bookVehiclePrice');

    if (whatsappBtn) {
        whatsappBtn.addEventListener('click', function () {
            if (!validate()) return;
            var f = form.elements;
            var vehicleName = vehicleNameField ? vehicleNameField.value : '';
            var vehiclePrice = vehiclePriceField ? vehiclePriceField.value : '';

            var lines = [];
            if (vehicleName) {
                lines.push('Hi ' + siteName + '! I\u2019d like to book the ' + vehicleName + '.');
                if (vehiclePrice && Number(vehiclePrice) > 0) lines.push('Price: Rs. ' + Number(vehiclePrice).toLocaleString() + ' / day');
            } else {
                lines.push('Hi ' + siteName + '! I\u2019d like to book a vehicle.');
            }
            lines.push('Name: ' + f.name.value.trim());
            lines.push('Phone: ' + f.phone.value.trim());
            lines.push('Vehicle Type: ' + f.vehicleType.value);
            lines.push('Passengers: ' + f.passengers.value);
            lines.push('Pickup Time: ' + f.pickupTime.value);
            lines.push('Pickup: ' + trip.pickup.label);
            lines.push('Drop: ' + trip.drop.label);
            if (f.note.value.trim()) lines.push('Additional info: ' + f.note.value.trim());

            var message = encodeURIComponent(lines.join('\n'));
            window.open('https://wa.me/' + waNumber + '?text=' + message, '_blank');
        });
    }
})();
