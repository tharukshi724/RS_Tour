<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book a Vehicle — <?= htmlspecialchars(SITE_NAME) ?></title>
<?php seo_head(
    'Book a Vehicle — ' . SITE_NAME,
    'Fill in your trip details — pickup, drop, vehicle type and time — and confirm your ride with ' . SITE_NAME . '.',
    '/index.php?page=book'
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= htmlspecialchars(public_asset_url('vendor/leaflet/leaflet.css')) ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(public_asset_url('css/style.css?v=' . ASSET_VERSION)) ?>">
</head>
<body data-whatsapp="<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" data-sitename="<?= htmlspecialchars(SITE_NAME) ?>">

<?php require __DIR__ . '/partials/header.php'; ?>

<section class="book-hero">
    <div class="book-hero-bg" aria-hidden="true">
        <img src="<?= htmlspecialchars(public_asset_url('images/bg.jpeg')) ?>" alt="">
    </div>
    <div class="container book-hero-inner">
        <a href="index.php" class="book-breadcrumb">&larr; Back to <?= htmlspecialchars(SITE_NAME) ?></a>
        <span class="book-hero-badge"><?= icon_svg('car') ?> Book a vehicle</span>
        <h1>Let's plan your ride</h1>
        <p>Fill in a few details below — pickup, drop, vehicle type and time — and we'll take it from there.</p>

        <?php if ($vehicleName): ?>
        <div class="book-vehicle-chip">
            <span class="chip-icon"><?= icon_svg('car') ?></span>
            <div>
                <b><?= htmlspecialchars($vehicleName) ?></b>
                <?php if ($vehiclePrice): ?><span> &middot; <?= format_lkr($vehiclePrice) ?></span><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<div class="book-wrap">
    <div class="container">
        <div class="book-card">
            <div class="book-card-head">
                <h2>Your trip details</h2>
                <p>No payment here — we'll confirm everything with you directly.</p>
            </div>

            <form id="bookingForm" class="booking-form" novalidate>

                <div class="booking-group">
                    <p class="booking-group-label"><span class="eyebrow-dash"></span> Contact details</p>
                    <div class="booking-field-row">
                        <div class="booking-field">
                            <label for="bookName"><?= icon_svg('user') ?> Name</label>
                            <input type="text" id="bookName" name="name" placeholder="Your full name" required autocomplete="name">
                        </div>
                        <div class="booking-field">
                            <label for="bookPhone"><?= icon_svg('phone') ?> Phone Number</label>
                            <input type="tel" id="bookPhone" name="phone" placeholder="07X XXX XXXX" required autocomplete="tel">
                        </div>
                    </div>
                </div>

                <div class="booking-group">
                    <p class="booking-group-label"><span class="eyebrow-dash"></span> Route</p>
                    <div class="booking-field-row">
                        <div class="booking-field">
                            <label for="bookPickupBtn"><?= icon_svg('pin') ?> Pick up Location</label>
                            <button type="button" class="location-btn" id="bookPickupBtn">
                                <span class="location-value is-placeholder" id="bookPickupValue">Set pickup location</span>
                            </button>
                        </div>
                        <div class="booking-field">
                            <label for="bookDropBtn"><?= icon_svg('pin') ?> Drop Off Location</label>
                            <button type="button" class="location-btn" id="bookDropBtn">
                                <span class="location-value is-placeholder" id="bookDropValue">Set drop off location</span>
                            </button>
                        </div>
                    </div>
                    <p class="booking-field-hint" id="bookingLocationHint">Tap a location field above to pick it on the map.</p>
                </div>

                <div class="booking-group">
                    <p class="booking-group-label"><span class="eyebrow-dash"></span> Trip details</p>
                    <div class="booking-field-row">
                        <div class="booking-field">
                            <label for="bookVehicleType"><?= icon_svg('car') ?> Vehicle Type</label>
                            <select id="bookVehicleType" name="vehicleType" required>
                                <option value="" disabled<?= $matchedType ? '' : ' selected' ?>>Select type</option>
                                <?php foreach ($vehicleTypes as $t): ?>
                                    <option value="<?= htmlspecialchars($t) ?>"<?= $matchedType === $t ? ' selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="booking-field">
                            <label for="bookPassengers"><?= icon_svg('seat') ?> Passengers</label>
                            <input type="number" id="bookPassengers" name="passengers" min="1" max="60" placeholder="e.g. 2" required>
                        </div>
                    </div>
                </div>

                <div class="booking-group">
                    <p class="booking-group-label"><span class="eyebrow-dash"></span> Trip Date &amp; Time</p>
                    <div class="booking-field-row">
                        <div class="booking-field">
                            <label for="bookTripDate"><?= icon_svg('clock') ?> Trip Date</label>
                            <input type="date" id="bookTripDate" name="tripDate" required>
                        </div>
                        <div class="booking-field">
                            <label for="bookTripTime"><?= icon_svg('clock') ?> Trip Time</label>
                            <input type="time" id="bookTripTime" name="tripTime" required>
                        </div>
                    </div>
                </div>

                <div class="booking-group booking-group-last">
                    <p class="booking-group-label"><span class="eyebrow-dash"></span> Anything else?</p>
                    <div class="booking-field">
                        <label for="bookNote"><?= icon_svg('note') ?> Additional information <span class="booking-optional">(optional)</span></label>
                        <textarea id="bookNote" name="note" rows="3" placeholder="Anything else we should know?"></textarea>
                    </div>
                </div>

                <input type="hidden" id="bookVehicleName" value="<?= htmlspecialchars($vehicleName) ?>">
                <input type="hidden" id="bookVehiclePrice" value="<?= (int) $vehiclePrice ?>">

                <div class="booking-actions">
                    <?php if ($mode === 'whatsapp'): ?>
                        <button type="button" class="btn btn-go" id="bookingWhatsappBtn"><?= icon_svg('whatsapp') ?> Send on WhatsApp</button>
                        <p class="booking-form-foot">This sends your trip straight to our WhatsApp so we can confirm it with you.</p>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary" id="bookingSubmitBtn"><?= icon_svg('check') ?> Submit booking</button>
                        <p class="booking-form-foot">One of our agents will call to confirm your ride.</p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

<!-- Location picker map modal — the only modal on this page, so it never
     has to stack behind or beside another popup. -->
<div class="map-modal" id="bookingMapModal">
    <div class="map-modal-box">
        <div class="map-modal-head">
            <h3 id="bookingMapModalTitle">Set pickup location</h3>
            <button class="map-modal-close" id="bookingMapModalClose" aria-label="Close">&times;</button>
        </div>
        <div class="map-modal-scroll">
            <div class="map-search-row">
                <div class="map-search-input-wrap">
                    <input type="text" id="bookingMapSearchInput" placeholder="Type a city or area&hellip;" autocomplete="off">
                    <div class="location-suggestions" id="bookingLocationSuggestions"></div>
                </div>
                <button id="bookingMapSearchBtn" type="button"><?= icon_svg('search') ?></button>
            </div>
            <button class="use-current-btn" id="bookingUseCurrentBtn" type="button">
                <?= icon_svg('crosshair') ?> Use my current location
            </button>
            <div id="bookingLeafletMap"></div>
        </div>
        <div class="map-modal-foot">
            <div class="map-picked-address">
                <b>Selected point</b>
                <span id="bookingMapPickedText">Tap the map, search, or use current location</span>
            </div>
            <button class="btn btn-primary" id="bookingMapConfirmBtn" disabled>Confirm</button>
        </div>
    </div>
</div>

<!-- Confirmation popup — shown only after "Submit". Never open at the same
     time as the map modal above. -->
<div class="confirm-modal" id="bookingConfirmModal">
    <div class="confirm-modal-box">
        <div class="confirm-modal-icon"><?= icon_svg('check') ?></div>
        <h3>Thanks, <span id="confirmName">there</span>!</h3>
        <p>One of our agents will call you shortly to confirm your ride. Keep your phone nearby.</p>
        <button type="button" class="btn btn-primary" id="bookingConfirmOkBtn">OK</button>
    </div>
</div>

<script>
    window.WHATSAPP_NUMBER = "<?= htmlspecialchars(WHATSAPP_NUMBER) ?>";
    window.SITE_NAME = "<?= htmlspecialchars(SITE_NAME) ?>";
</script>
<script src="<?= htmlspecialchars(public_asset_url('vendor/leaflet/leaflet.js')) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/locations-data.js?v=' . ASSET_VERSION)) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/main.js?v=' . ASSET_VERSION)) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/booking.js?v=' . ASSET_VERSION)) ?>"></script>
</body>
</html>
