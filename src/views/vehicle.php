<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($vehicle['name']) ?> — <?= htmlspecialchars(SITE_NAME) ?></title>
<?php seo_head(
    $vehicle['name'] . ' Rental in Colombo — ' . SITE_NAME,
    'Hire the ' . $vehicle['name'] . ' (' . $vehicle['category'] . ', ' . VehicleModel::seatsLabel($vehicle) . ' seats, ' . VehicleModel::acLabel($vehicle) . ') from ' . SITE_NAME . '. Set your pickup and drop on the map and confirm on WhatsApp.',
    '/index.php?page=vehicle&id=' . $vehicle['id']
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= htmlspecialchars(public_asset_url('css/style.css?v=' . ASSET_VERSION)) ?>">
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
        <div class="detail-price<?= ($vehicle['price'] ?? null) === null ? ' is-ask' : '' ?>"><?= htmlspecialchars(VehicleModel::priceLabel($vehicle)) ?></div>
        <p class="detail-blurb"><?= htmlspecialchars($vehicle['blurb']) ?></p>

        <div class="spec-grid">
            <div class="spec"><span><?= icon_svg('seat') ?> Seats</span><b><?= htmlspecialchars(VehicleModel::seatsLabel($vehicle)) ?></b></div>
            <div class="spec"><span><?= icon_svg('snow') ?> Air conditioning</span><b><?= htmlspecialchars(VehicleModel::acLabel($vehicle)) ?></b></div>
            <div class="spec"><span><?= icon_svg('fleet') ?> In our fleet</span><b><?= VehicleModel::units($vehicle) ?></b></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="<?= htmlspecialchars(public_asset_url('js/locations-data.js?v=' . ASSET_VERSION)) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/main.js?v=' . ASSET_VERSION)) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/vehicle.js?v=' . ASSET_VERSION)) ?>"></script>
</body>
</html>
