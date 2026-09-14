<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($package['title']) ?> Package — <?= htmlspecialchars(SITE_NAME) ?></title>
<?php seo_head(
    $package['title'] . ' Package — ' . SITE_NAME,
    $package['summary'],
    '/index.php?page=package&slug=' . $package['slug']
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= htmlspecialchars(public_asset_url('css/style.css?v=' . ASSET_VERSION)) ?>">
</head>
<body class="has-hero" data-whatsapp="<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" data-sitename="<?= htmlspecialchars(SITE_NAME) ?>">

<?php require __DIR__ . '/partials/header.php'; ?>

<header class="package-hero">
    <div class="hero-bg">
        <img src="<?= htmlspecialchars(ContentModel::packageImageUrl($package)) ?>" alt="" aria-hidden="true">
        <div class="hero-bg-tint"></div>
    </div>
    <div class="hero-inner">
        <a href="index.php#packages" class="breadcrumb breadcrumb-light">&larr; Back to Our Packages</a>
        <span class="package-icon-badge-lg"><?= icon_svg($package['icon']) ?></span>
        <h1><?= htmlspecialchars($package['title']) ?></h1>
        <p class="package-hero-summary"><?= htmlspecialchars($package['summary']) ?></p>
    </div>
</header>

<main>
    <section class="section">
        <div class="container package-detail">
            <?php foreach ($package['groups'] as $group): ?>
                <div class="package-group reveal">
                    <?php if (!empty($group['label'])): ?>
                        <h2 class="package-group-title"><?= htmlspecialchars($group['label']) ?></h2>
                    <?php endif; ?>
                    <div class="package-item-list">
                        <?php foreach ($group['items'] as $item):
                            $isFree = !empty($item['free']);
                            $priceLabel = $isFree ? 'FREE' : 'Rs. ' . number_format($item['price']);
                            $msg = "Hi " . SITE_NAME . "! I'd like to inquire about the " . $package['title'] . " package";
                            if (!empty($group['label'])) $msg .= " — " . $group['label'];
                            $msg .= ": " . $item['name'] . " (" . $priceLabel . ")";
                        ?>
                            <div class="package-item-row">
                                <span class="package-item-name"><?= htmlspecialchars($item['name']) ?></span>
                                <span class="package-item-price<?= $isFree ? ' is-free' : '' ?>"><?= htmlspecialchars($priceLabel) ?></span>
                                <a class="btn btn-go btn-sm" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>?text=<?= urlencode($msg) ?>"><?= icon_svg('whatsapp') ?> Inquire</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($related = array_filter(ContentModel::packages(), fn($p) => $p['slug'] !== $package['slug'])): ?>
            <div class="related-section reveal">
                <h3>Other packages</h3>
                <div class="package-grid package-grid-related">
                    <?php foreach ($related as $pkg): require __DIR__ . '/partials/package-card.php'; endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="<?= htmlspecialchars(public_asset_url('js/main.js?v=' . ASSET_VERSION)) ?>"></script>
</body>
</html>
