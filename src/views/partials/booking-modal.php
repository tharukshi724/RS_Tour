<?php
/**
 * Shared "Book a vehicle" form modal + its own location-picker map modal.
 * One component, included once per page (home + vehicle detail), opened
 * from any button carrying the `js-open-booking` class. See public/js/booking.js.
 *
 * Optional trigger data-attributes (read by booking.js on open):
 *   data-vehicle-name, data-vehicle-category, data-vehicle-price
 */
?>
<!-- ============================== BOOKING FORM MODAL ============================== -->
<div class="booking-modal" id="bookingModal" aria-hidden="true">
    <div class="booking-modal-box">
        <button class="booking-modal-close" id="bookingModalClose" type="button" aria-label="Close">&times;</button>

        <div class="booking-modal-bg" aria-hidden="true">
            <img src="<?= htmlspecialchars(public_asset_url('images/bg.jpeg')) ?>" alt="">
        </div>

        <div class="booking-modal-scroll">
            <div class="booking-modal-head">
                <span class="booking-modal-eyebrow"><?= icon_svg('car') ?> <?= htmlspecialchars(SITE_NAME) ?></span>
                <h3>Book your ride</h3>
                <p id="bookingModalSub">Fill this in and we'll open WhatsApp with everything ready to send.</p>
            </div>

            <form id="bookingForm" class="booking-form" novalidate>
                <div class="booking-field">
                    <label for="bookPhone"><?= icon_svg('phone') ?> Phone Number</label>
                    <input type="tel" id="bookPhone" name="phone" placeholder="07X XXX XXXX" required autocomplete="tel">
                </div>

                <div class="booking-field">
                    <label for="bookName"><?= icon_svg('user') ?> Name</label>
                    <input type="text" id="bookName" name="name" placeholder="Your full name" required autocomplete="name">
                </div>

                <div class="booking-field-row">
                    <button type="button" class="location-btn" id="bookPickupBtn">
                        <span class="location-label"><?= icon_svg('pin') ?> Pick up Location</span>
                        <span class="location-value is-placeholder" id="bookPickupValue">Set pickup location</span>
                    </button>
                    <button type="button" class="location-btn" id="bookDropBtn">
                        <span class="location-label"><?= icon_svg('pin') ?> Drop Off Location</span>
                        <span class="location-value is-placeholder" id="bookDropValue">Set drop off location</span>
                    </button>
                </div>
                <p class="booking-field-hint" id="bookingLocationHint">Tap a location field above to pick it on the map.</p>

                <div class="booking-field-row">
                    <div class="booking-field">
                        <label for="bookVehicleType"><?= icon_svg('car') ?> Vehicle Type</label>
                        <select id="bookVehicleType" name="vehicleType" required>
                            <option value="" disabled selected>Select a vehicle type</option>
                            <option value="Van">Van</option>
                            <option value="Car">Car</option>
                            <option value="Bus">Bus</option>
                            <option value="Bike">Bike</option>
                            <option value="Three Wheeler">Three Wheeler</option>
                        </select>
                    </div>
                    <div class="booking-field">
                        <label for="bookPassengers"><?= icon_svg('seat') ?> Number of Passengers</label>
                        <input type="number" id="bookPassengers" name="passengers" min="1" max="60" placeholder="e.g. 2" required>
                    </div>
                </div>

                <div class="booking-field">
                    <label for="bookPickupTime"><?= icon_svg('clock') ?> Pickup Time</label>
                    <input type="time" id="bookPickupTime" name="pickupTime" required>
                </div>

                <div class="booking-field">
                    <label for="bookNote"><?= icon_svg('note') ?> Additional information</label>
                    <textarea id="bookNote" name="note" rows="3" placeholder="Anything else we should know? (optional)"></textarea>
                </div>

                <button type="submit" class="btn btn-go booking-submit" id="bookingSubmitBtn"><?= icon_svg('whatsapp') ?> Send on WhatsApp</button>
                <p class="booking-form-foot">No payment here — this sends your trip details straight to our WhatsApp so we can confirm it with you.</p>
            </form>
        </div>
    </div>
</div>

<!-- ============================== LOCATION PICKER MAP MODAL ============================== -->
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
