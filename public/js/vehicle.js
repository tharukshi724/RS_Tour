(function () {
    /* ---------------------------------------------------------------------
       Photo carousel + thumbnail strip
    --------------------------------------------------------------------- */
    var slides = Array.prototype.slice.call(document.querySelectorAll('.carousel-slide'));
    var dots = Array.prototype.slice.call(document.querySelectorAll('.carousel-dot'));
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.thumb'));
    var slideCountEl = document.getElementById('slideCount');
    var current = 0;

    function goToSlide(index) {
        if (!slides.length) return;
        current = (index + slides.length) % slides.length;
        slides.forEach(function (s, i) { s.classList.toggle('is-active', i === current); });
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
        thumbs.forEach(function (t, i) { t.classList.toggle('is-active', i === current); });
        if (slideCountEl) slideCountEl.textContent = current + 1;
    }

    var prevBtn = document.getElementById('prevSlide');
    var nextBtn = document.getElementById('nextSlide');
    if (prevBtn) prevBtn.addEventListener('click', function () { goToSlide(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goToSlide(current + 1); });
    dots.forEach(function (d) { d.addEventListener('click', function () { goToSlide(parseInt(d.dataset.slide, 10)); }); });
    thumbs.forEach(function (t) { t.addEventListener('click', function () { goToSlide(parseInt(t.dataset.slide, 10)); }); });

    var track = document.querySelector('.carousel-track');
    if (track) {
        var touchStartX = null;
        track.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', function (e) {
            if (touchStartX === null) return;
            var dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 40) goToSlide(current + (dx < 0 ? 1 : -1));
            touchStartX = null;
        });
    }

    /* ---------------------------------------------------------------------
       Pickup / drop location picker
       Leaflet + OpenStreetMap Nominatim (search/reverse-geocode) + browser
       Geolocation ("use my current location"). No API key required.
    --------------------------------------------------------------------- */
    var trip = { pickup: null, drop: null }; // { lat, lng, label }
    var activeField = null; // 'pickup' | 'drop'
    var map, marker;
    var COLOMBO = [6.9271, 79.8612];

    var modal = document.getElementById('mapModal');
    var modalTitle = document.getElementById('mapModalTitle');
    var modalClose = document.getElementById('mapModalClose');
    var confirmBtn = document.getElementById('mapConfirmBtn');
    var pickedText = document.getElementById('mapPickedText');
    var searchInput = document.getElementById('mapSearchInput');
    var searchBtn = document.getElementById('mapSearchBtn');
    var useCurrentBtn = document.getElementById('useCurrentBtn');
    var suggestionsBox = document.getElementById('locationSuggestions');

    // Default the date field to today, and stop the user picking the past.
    var pickupDateInput = document.getElementById('pickupDate');
    if (pickupDateInput) {
        var todayStr = new Date().toISOString().slice(0, 10);
        pickupDateInput.min = todayStr;
        if (!pickupDateInput.value) pickupDateInput.value = todayStr;
    }
    var pickupTimeInput = document.getElementById('pickupTime');

    // If the hero "quick book" widget on the homepage was used, carry its
    // date/time over so this page opens pre-filled instead of starting blank.
    try {
        var prefill = JSON.parse(localStorage.getItem('rstoursPrefill') || 'null');
        if (prefill) {
            if (prefill.date && pickupDateInput) pickupDateInput.value = prefill.date;
            if (prefill.time && pickupTimeInput) pickupTimeInput.value = prefill.time;
            localStorage.removeItem('rstoursPrefill');
        }
    } catch (err) { /* localStorage unavailable — safe to ignore, just skip the prefill */ }

    var pendingPoint = null;
    var userActed = false; // true once the user does something in *this* modal session

    function openModal(field) {
        activeField = field;
        pendingPoint = null;
        userActed = false;
        modalTitle.textContent = field === 'pickup' ? 'Set pickup location' : 'Set drop location';
        pickedText.textContent = 'Tap the map, search, or use current location';
        confirmBtn.disabled = true;
        if (searchInput) searchInput.value = '';
        hideSuggestions();
        modal.classList.add('is-open');

        if (!map) {
            map = L.map('leafletMap').setView(COLOMBO, 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);
            map.on('click', function (e) { userActed = true; placeMarker(e.latlng.lat, e.latlng.lng, true); });
        }

        setTimeout(function () {
            map.invalidateSize();
            var existing = trip[field];
            // Only restore the previously-confirmed pin if the user hasn't
            // already acted (searched / used current location / tapped the
            // map) in the meantime — otherwise a slow restore can overwrite
            // a fresh, correct pick with the stale one.
            if (existing && !userActed) {
                map.setView([existing.lat, existing.lng], 15);
                placeMarker(existing.lat, existing.lng, false, existing.label);
            }
        }, 50);
    }

    function closeModal() {
        modal.classList.remove('is-open');
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
            pickedText.textContent = 'Looking up address…';
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
        pickedText.textContent = 'Searching…';
        var q = /sri lanka/i.test(query) ? query : query + ', Sri Lanka';
        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(q))
            .then(function (r) { return r.json(); })
            .then(function (results) {
                if (!results || !results.length) {
                    pickedText.textContent = 'No match found — try a different search or tap the map.';
                    return;
                }
                var r = results[0];
                var lat = parseFloat(r.lat), lng = parseFloat(r.lon);
                map.setView([lat, lng], 15);
                placeMarker(lat, lng, false, r.display_name);
            })
            .catch(function () {
                pickedText.textContent = 'Search failed — try tapping the map instead.';
            });
    }

    /* ---------------------------------------------------------------------
       Local autocomplete — filters window.SRI_LANKA_LOCATIONS instantly as
       the user types (no network call), so it's fast and never rate-limited.
       Picking a suggestion geocodes just that one place via Nominatim.
    --------------------------------------------------------------------- */
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
        pickedText.textContent = 'Getting your location…';
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
                pickedText.textContent = 'Could not get your location — check location permissions, or pick manually.';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    var pickupBtn = document.getElementById('pickupBtn');
    var dropBtn = document.getElementById('dropBtn');
    if (pickupBtn) pickupBtn.addEventListener('click', function () { openModal('pickup'); });
    if (dropBtn) dropBtn.addEventListener('click', function () { openModal('drop'); });
    if (modalClose) modalClose.addEventListener('click', closeModal);
    if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    if (searchBtn) searchBtn.addEventListener('click', runSearch);
    if (useCurrentBtn) useCurrentBtn.addEventListener('click', useCurrentLocation);

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!pendingPoint || !activeField) return;
            trip[activeField] = pendingPoint;
            updateLocationUI(activeField);
            closeModal();
            updateHireState();
            updateTripPreview();
        });
    }

    function updateLocationUI(field) {
        var valueEl = document.getElementById(field + 'Value');
        var btnEl = document.getElementById(field + 'Btn');
        var point = trip[field];
        if (!point) return;
        valueEl.textContent = point.label || (point.lat.toFixed(5) + ', ' + point.lng.toFixed(5));
        valueEl.classList.remove('is-placeholder');
        btnEl.classList.add('is-set');
    }

    /* ---------------------------------------------------------------------
       Trip preview — once both points are set, show a small map with both
       pins (like a ride-hailing app) and the driving route between them.
    --------------------------------------------------------------------- */
    var previewEl = document.getElementById('tripPreview');
    var previewMap = null;
    var previewLine = null;
    var previewMarkers = [];
    var distanceEl = document.getElementById('tripDistance');

    var pickupIcon = L.divIcon({ className: 'trip-pin trip-pin-pickup', html: '<span>Pickup</span><i></i>', iconSize: [70, 30], iconAnchor: [12, 28] });
    var dropIcon = L.divIcon({ className: 'trip-pin trip-pin-drop', html: '<span>Drop</span><i></i>', iconSize: [60, 30], iconAnchor: [12, 28] });

    function updateTripPreview() {
        if (!trip.pickup || !trip.drop) {
            previewEl.classList.remove('is-visible');
            return;
        }
        previewEl.classList.add('is-visible');

        if (!previewMap) {
            previewMap = L.map('tripPreviewMap', { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(previewMap);
        }
        setTimeout(function () { previewMap.invalidateSize(); }, 50);

        previewMarkers.forEach(function (m) { m.remove(); });
        previewMarkers = [
            L.marker([trip.pickup.lat, trip.pickup.lng], { icon: pickupIcon }).addTo(previewMap),
            L.marker([trip.drop.lat, trip.drop.lng], { icon: dropIcon }).addTo(previewMap),
        ];

        var bounds = L.latLngBounds([[trip.pickup.lat, trip.pickup.lng], [trip.drop.lat, trip.drop.lng]]);
        previewMap.fitBounds(bounds, { padding: [50, 50] });

        if (previewLine) { previewLine.remove(); previewLine = null; }
        distanceEl.textContent = 'Calculating route…';

        var url = 'https://router.project-osrm.org/route/v1/driving/' +
            trip.pickup.lng + ',' + trip.pickup.lat + ';' + trip.drop.lng + ',' + trip.drop.lat +
            '?overview=full&geometries=geojson';

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.routes || !data.routes.length) throw new Error('no route');
                var route = data.routes[0];
                var coords = route.geometry.coordinates.map(function (c) { return [c[1], c[0]]; });
                previewLine = L.polyline(coords, { color: '#d1531f', weight: 4, opacity: 0.85 }).addTo(previewMap);
                previewMap.fitBounds(previewLine.getBounds(), { padding: [50, 50] });
                var km = (route.distance / 1000).toFixed(1);
                var mins = Math.round(route.duration / 60);
                distanceEl.textContent = km + ' km \u00b7 ~' + mins + ' min drive';
            })
            .catch(function () {
                previewLine = L.polyline(
                    [[trip.pickup.lat, trip.pickup.lng], [trip.drop.lat, trip.drop.lng]],
                    { color: '#d1531f', weight: 3, opacity: 0.7, dashArray: '6 8' }
                ).addTo(previewMap);
                distanceEl.textContent = 'Route preview unavailable — straight-line shown';
            });
    }

    /* ---------------------------------------------------------------------
       Hire on WhatsApp
    --------------------------------------------------------------------- */
    var hireBtn = document.getElementById('hireBtn');
    var tripHint = document.getElementById('tripHint');

    function updateHireState() {
        var ready = trip.pickup && trip.drop;
        hireBtn.disabled = !ready;
        tripHint.textContent = ready
            ? 'Ready — this opens WhatsApp with your trip details filled in.'
            : 'Set both locations to enable hiring.';
    }

    if (hireBtn) {
        hireBtn.addEventListener('click', function () {
            if (!trip.pickup || !trip.drop || !window.VEHICLE) return;
            var v = window.VEHICLE;
            var lines = [
                'Hi ' + (window.SITE_NAME || '') + '! I\'d like to hire the ' + v.name + ' (' + v.category + ').',
                'Price: Rs. ' + Number(v.price).toLocaleString() + ' / day',
            ];
            if (pickupDateInput && pickupDateInput.value) {
                var dt = 'Pickup date: ' + pickupDateInput.value;
                if (pickupTimeInput && pickupTimeInput.value) dt += ' at ' + pickupTimeInput.value;
                lines.push(dt);
            }
            lines.push('Pickup: ' + trip.pickup.label);
            lines.push('Drop: ' + trip.drop.label);
            var message = encodeURIComponent(lines.join('\n'));
            var url = 'https://wa.me/' + window.WHATSAPP_NUMBER + '?text=' + message;
            window.open(url, '_blank');
        });
    }
})();
