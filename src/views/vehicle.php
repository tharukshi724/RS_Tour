<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($vehicle['name']) ?> — <?= htmlspecialchars(SITE_NAME) ?></title>
<?php seo_head(
    $vehicle['name'] . ' Rental in Colombo — ' . SITE_NAME,
    'Hire the ' . $vehicle['name'] . ' (' . $vehicle['category'] . ') from ' . format_lkr($vehicle['price']) . '. Set your pickup and drop on the map and confirm on WhatsApp.',
    '/index.php?page=vehicle&id=' . $vehicle['id']
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="public/css/style.css?v=<?= ASSET_VERSION ?>">
</head>
<body data-whatsapp="<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" data-sitename="<?= htmlspecialchars(SITE_NAME) ?>">

<?php require __DIR__ . '/partials/header.php'; ?>

<div class="detail-wrap">
    <div>
        <a href="index.php#vehicles" class="breadcrumb">&larr; Back to <?= htmlspecialchars(SITE_NAME) ?> vehicles</a>

        <div class="carousel" id="carousel" style="--cat-color: var(--cat-<?= htmlspecialchars($catSlug) ?>)">
            <div class="carousel-count"><span id="slideCount">1</span> / <?= count($photos) ?></div>
            <div class="carousel-track">
                <div class="vcard-media-skeleton" id="carouselSkeleton"></div>
                <?php foreach ($photos as $i => $photo): ?>
                    <div class="carousel-slide<?= $i === 0 ? ' is-active' : '' ?>" data-slide="<?= $i ?>">
                        <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($vehicle['name']) ?> photo <?= $i + 1 ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" onload="document.getElementById('carousel').classList.add('is-ready')">
                    </div>
                <?php endforeach; ?>
                <button class="carousel-arrow prev" id="prevSlide" aria-label="Previous photo">&#8249;</button>
                <button class="carousel-arrow next" id="nextSlide" aria-label="Next photo">&#8250;</button>
                <div class="carousel-dots" id="carouselDots">
                    <?php foreach ($photos as $i => $photo): ?>
                        <button class="carousel-dot<?= $i === 0 ? ' is-active' : '' ?>" data-slide="<?= $i ?>" aria-label="Photo <?= $i + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="thumb-row" id="thumbRow">
            <?php foreach ($photos as $i => $photo): ?>
                <button class="thumb<?= $i === 0 ? ' is-active' : '' ?>" data-slide="<?= $i ?>" aria-label="Show photo <?= $i + 1 ?>">
                    <img src="<?= htmlspecialchars($photo) ?>" alt="" loading="lazy">
                </button>
            <?php endforeach; ?>
        </div>

        <?php if ($related): ?>
        <div class="related-section">
            <h3>You might also like</h3>
            <div class="related-grid">
                <?php foreach ($related as $v): require __DIR__ . '/partials/vehicle-card.php'; endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="detail-info">
        <div class="detail-tag-row">
            <span class="vcard-tag" style="--cat-color: var(--cat-<?= htmlspecialchars($catSlug) ?>); background: color-mix(in srgb, var(--cat-color) 30%, transparent);"><?= htmlspecialchars($vehicle['category']) ?></span>
        </div>
        <h1><?= htmlspecialchars($vehicle['name']) ?></h1>
        <div class="detail-price"><?= format_lkr($vehicle['price']) ?></div>
        <p class="detail-blurb"><?= htmlspecialchars($vehicle['blurb']) ?></p>

        <div class="spec-grid">
            <div class="spec"><span><?= icon_svg('seat') ?> Seats</span><b><?= $vehicle['seats'] ?></b></div>
            <div class="spec"><span><?= icon_svg('gear') ?> Transmission</span><b><?= htmlspecialchars($vehicle['transmission']) ?></b></div>
            <div class="spec"><span><?= icon_svg('fuel') ?> Fuel</span><b><?= htmlspecialchars($vehicle['fuel']) ?></b></div>
        </div>

        <div class="trip-panel">
            <h3>Plan the trip</h3>
            <p>Set where we pick the vehicle up and where you'll leave it.</p>

            <div class="datetime-row">
                <label class="datetime-field">
                    <span><?= icon_svg('clock') ?> Pickup date</span>
                    <input type="date" id="pickupDate">
                </label>
                <label class="datetime-field">
                    <span><?= icon_svg('clock') ?> Pickup time</span>
                    <input type="time" id="pickupTime" value="09:00">
                </label>
            </div>

            <div class="location-row">
                <button class="location-btn" id="pickupBtn" type="button">
                    <span class="location-label"><?= icon_svg('pin') ?> Pickup</span>
                    <span class="location-value is-placeholder" id="pickupValue">Set pickup location</span>
                </button>
                <button class="location-btn" id="dropBtn" type="button">
                    <span class="location-label"><?= icon_svg('pin') ?> Drop</span>
                    <span class="location-value is-placeholder" id="dropValue">Set drop location</span>
                </button>
            </div>

            <!-- Mini route preview — appears once both points are set -->
            <div class="trip-preview" id="tripPreview">
                <div id="tripPreviewMap"></div>
                <div class="trip-preview-info">
                    <span id="tripDistance"></span>
                </div>
            </div>

            <div class="trip-cta">
                <button class="btn btn-go" id="hireBtn" disabled><?= icon_svg('whatsapp') ?> Hire on WhatsApp</button>
                <p class="trip-hint" id="tripHint">Set both locations to enable hiring.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

<!-- Map picker modal (Leaflet + OpenStreetMap — no API key needed) -->
<div class="map-modal" id="mapModal">
    <div class="map-modal-box">
        <div class="map-modal-head">
            <h3 id="mapModalTitle">Set pickup location</h3>
            <button class="map-modal-close" id="mapModalClose" aria-label="Close">&times;</button>
        </div>
        <div class="map-modal-scroll">
            <div class="map-search-row">
                <div class="map-search-input-wrap">
                    <input type="text" id="mapSearchInput" placeholder="Type a city or area&hellip;" autocomplete="off">
                    <div class="location-suggestions" id="locationSuggestions"></div>
                </div>
                <button id="mapSearchBtn" type="button"><?= icon_svg('search') ?></button>
            </div>
            <button class="use-current-btn" id="useCurrentBtn" type="button">
                <?= icon_svg('crosshair') ?> Use my current location
            </button>
            <div id="leafletMap"></div>
        </div>
        <div class="map-modal-foot">
            <div class="map-picked-address">
                <b>Selected point</b>
                <span id="mapPickedText">Tap the map, search, or use current location</span>
            </div>
            <button class="btn btn-primary" id="mapConfirmBtn" disabled>Confirm</button>
        </div>
    </div>
</div>

<script>
    window.VEHICLE = <?= json_encode([
        'name' => $vehicle['name'],
        'category' => $vehicle['category'],
        'price' => $vehicle['price'],
    ], JSON_UNESCAPED_SLASHES) ?>;
    window.WHATSAPP_NUMBER = "<?= htmlspecialchars(WHATSAPP_NUMBER) ?>";
    window.SITE_NAME = "<?= htmlspecialchars(SITE_NAME) ?>";
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/locations-data.js?v=<?= ASSET_VERSION ?>"></script>
<script src="js/main.js?v=<?= ASSET_VERSION ?>"></script>
<script src="js/vehicle.js?v=<?= ASSET_VERSION ?>"></script>
</body>
</html>
