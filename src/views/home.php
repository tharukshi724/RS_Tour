<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars(SITE_NAME) ?> — Vehicle rentals in Colombo</title>
<?php seo_head(
    SITE_NAME . ' — Vehicle Rentals in Mawanella',
    'Rent cars, SUVs, vans and bikes in Mawanella ,Alupotha, Ussanpitiya. Set your pickup and drop on the map and hire instantly on WhatsApp — no forms, no waiting.',
    '/index.php'
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= htmlspecialchars(public_asset_url('css/style.css')) ?>">
</head>
<body class="has-hero" data-whatsapp="<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" data-sitename="<?= htmlspecialchars(SITE_NAME) ?>">

<?php require __DIR__ . '/partials/header.php'; ?>

<header class="hero">
    <div class="hero-bg">
        <img src="<?= htmlspecialchars(public_asset_url('public/images/bg.jpeg')) ?>" alt="" aria-hidden="true">
        <div class="hero-bg-tint"></div>
    </div>
    <div class="hero-inner">
        <p class="hero-eyebrow"><span class="eyebrow-dash" aria-hidden="true"></span> <?= htmlspecialchars(SITE_TAGLINE) ?></p>
        <h1>Enjoy Your<br>Vacation<br>With <em>Us.</em></h1>
        <p class="hero-tagline-si"><span id="siTagline" lang="si"><?= htmlspecialchars(SITE_TAGLINE_SI) ?></span></p>
        <p class="hero-sub">Cars, SUVs, vans and bikes ready across the city. Set your pickup and drop on the map, and we'll confirm the rest over WhatsApp — no forms, no waiting on hold.</p>
        <div class="hero-actions">
            <a href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" class="btn btn-go"><?= icon_svg('whatsapp') ?> Book with WhatsApp</a>
            <a href="#vehicles" class="btn btn-primary"><?= icon_svg('search') ?> Book online now</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><b><?= count($vehicles) ?></b><span>vehicles listed</span></div>
            <div class="hero-stat"><b>24/7</b><span>WhatsApp booking</span></div>
            <div class="hero-stat"><b>&lt;10 min</b><span>average reply time</span></div>
        </div>
    </div>

    <!-- Quick-book widget — floats over the bottom edge of the hero -->
  
</header>

<main>
   

 <!-- ============================== VEHICLES ============================== -->
    <section class="section section-alt" id="vehicles">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Choose the right vehicle</h2>
                    <p>Every listing below is ready to book. Open one to see more photos, pick your locations, and hire it on WhatsApp.</p>
                </div>
            </div>

            <div class="filter-select-wrap">
                <label for="categoryFilter" class="sr-only">Filter by vehicle type</label>
                <select id="categoryFilter" class="filter-select">
                    <option value="all">All vehicles</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars(category_slug($cat)) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="filter-select-icon"><?= icon_svg('chevron-down') ?></span>
            </div>

            <div class="vehicle-grid" id="vehicleGrid">
                <?php foreach ($vehicles as $v): require __DIR__ . '/partials/vehicle-card.php'; endforeach; ?>
            </div>

            <div class="promo-strip reveal">
                <span><?= icon_svg('key') ?> Looking for something more premium? Luxury vehicles available on open request.</span>
                <a class="btn btn-sm btn-primary" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>?text=<?= urlencode("Hi " . SITE_NAME . "! I'm looking for a luxury vehicle — what's available?") ?>">Ask us</a>
            </div>
        </div>
    </section>

     <!-- ============================== SERVICES ============================== -->
    <section class="section" id="services">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Explore our services</h2>
                    <p>Whichever way you'd rather travel, we've got a way to get you there.</p>
                </div>
            </div>
            <div class="service-grid">
                <?php foreach ($services as $s): require __DIR__ . '/partials/service-card.php'; endforeach; ?>
            </div>
        </div>
    </section>


    <!-- ============================== OUR PACKAGES ============================== -->
    <section class="section" id="packages">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Our Packages</h2>
                    <p>Fixed-price packages for airport transfers, events, and weddings — open one to see every option and price.</p>
                </div>
            </div>
            <div class="package-grid">
                <?php foreach ($packages as $pkg): require __DIR__ . '/partials/package-card.php'; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== WHY CHOOSE US + STATS ============================== -->
    <section class="section" id="why-us">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Why travelers choose <?= htmlspecialchars(SITE_NAME) ?></h2>
                    <p>Straightforward pricing and a real person on WhatsApp when you need one.</p>
                </div>
            </div>
            <div class="why-grid">
                <?php foreach ($whyUs as $w): require __DIR__ . '/partials/why-item.php'; endforeach; ?>
            </div>
            <div class="stats-bar reveal">
                <?php foreach ($stats as $stat): ?>
                    <div class="stats-item">
                        <b><?= $stat['value'] ?></b>
                        <span><?= htmlspecialchars($stat['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== REVIEWS ============================== -->
    <!-- PLACEHOLDER reviews — see ContentModel::reviews(). Replace with real ones. -->
    <section class="section section-alt" id="reviews">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>What travelers say</h2>
                    <p class="placeholder-note">Sample reviews shown while we collect real ones — not actual customers yet.</p>
                </div>
                <div class="rating-summary">
                    <span class="rating-number"><?= $reviewSummary['rating'] ?></span>
                    <div class="review-stars">
                        <?php for ($i = 0; $i < 5; $i++): ?><span class="is-filled"><?= icon_svg('star') ?></span><?php endfor; ?>
                    </div>
                </div>
            </div>
            <div class="review-grid">
                <?php foreach ($reviews as $rv): require __DIR__ . '/partials/review-card.php'; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== QUOTE FORMS ============================== -->
    <section class="section" id="quote">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Check special offers on your trip</h2>
                    <p>Either way, it ends up as a message to us on WhatsApp — pick whichever's quicker for you.</p>
                </div>
            </div>
            <div class="quote-grid">
                <div class="quote-card reveal">
                    <h3><?= icon_svg('whatsapp') ?> Quick quote on WhatsApp</h3>
                    <p>Tell us what you need in one line and we'll take it from there.</p>
                    <form class="quote-quick-form" id="quoteQuickForm">
                        <input type="text" name="need" placeholder="e.g. SUV for 3 days, Colombo to Kandy" required>
                        <button type="submit" class="btn btn-go">Ask on WhatsApp</button>
                    </form>
                </div>
               
            </div>
        </div>
    </section>

    <!-- ============================== TRIP TYPES ============================== -->
    <section class="section section-alt" id="trip-types">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>A rental for every kind of trip</h2>
                </div>
            </div>
            <div class="trip-type-grid">
                <?php foreach ($tripTypes as $tt): require __DIR__ . '/partials/trip-type-card.php'; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== BANNER CTA ============================== -->
    <section class="cta-banner">
        <div class="container cta-banner-inner">
            <div class="cta-banner-text">
                <span class="cta-banner-brand"><?= htmlspecialchars(SITE_NAME) ?></span>
                <span>Your journey, our priority</span>
            </div>
            <a class="cta-banner-phone" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>">
                <?= icon_svg('phone') ?>
                <span>Vehicle at your doorstep &middot; 24/7</span>
                <b>+<?= htmlspecialchars(WHATSAPP_NUMBER) ?></b>
            </a>
        </div>
    </section>


    <!-- ============================== FAQ ============================== -->
    <section class="section section-alt" id="faq">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Questions, answered</h2>
                </div>
            </div>
            <div class="faq-list">
                <?php foreach ($faqs as $i => $f): require __DIR__ . '/partials/faq-item.php'; endforeach; ?>
            </div>
        </div>
    </section>

   

    <!-- ============================== FINAL CTA BANNER ============================== -->
    <section class="cta-banner cta-banner-alt">
        <div class="container cta-banner-inner">
            <div class="cta-banner-text">
                <span class="cta-banner-brand">Vehicle at your doorstep</span>
                <span>Self-drive, chauffeur, or a full day tour — sorted over one WhatsApp chat.</span>
            </div>
            <a class="cta-banner-phone" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>">
                <?= icon_svg('whatsapp') ?>
                <span>Chat with us now &middot; 24/7</span>
                <b>+<?= htmlspecialchars(WHATSAPP_NUMBER) ?></b>
            </a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="<?= htmlspecialchars(public_asset_url('js/locations-data.js?v=' . ASSET_VERSION)) ?>"></script>
<script src="<?= htmlspecialchars(public_asset_url('js/main.js?v=' . ASSET_VERSION)) ?>"></script>
</body>
</html>
