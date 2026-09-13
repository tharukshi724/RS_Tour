<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars(SITE_NAME) ?> — Vehicle rentals in Colombo</title>
<?php seo_head(
    SITE_NAME . ' — Vehicle Rentals in Colombo',
    'Rent cars, SUVs, vans and bikes in Colombo. Set your pickup and drop on the map and hire instantly on WhatsApp — no forms, no waiting.',
    '/index.php'
); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Sinhala:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="has-hero" data-whatsapp="<?= htmlspecialchars(WHATSAPP_NUMBER) ?>" data-sitename="<?= htmlspecialchars(SITE_NAME) ?>">

<?php require __DIR__ . '/partials/header.php'; ?>

<header class="hero">
    <div class="hero-bg">
        <img src="images/bg.jpeg" alt="" aria-hidden="true">
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

    <!-- ============================== HOW IT WORKS ============================== -->
    <section class="section" id="how">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>How it works</h2>
                    <p>Three steps between browsing and driving off.</p>
                </div>
            </div>
            <div class="steps">
                <div class="step reveal">
                    <div class="step-icon step-icon-blue"><?= icon_svg('search') ?></div>
                    <b>1</b>
                    <h3>Browse &amp; pick</h3>
                    <p>Open a vehicle, look through the photos, and check it fits what you need.</p>
                </div>
                <div class="step reveal">
                    <div class="step-icon step-icon-amber"><?= icon_svg('route') ?></div>
                    <b>2</b>
                    <h3>Set your route</h3>
                    <p>Drop a pin for pickup and drop-off — use your current location, search an address, or tap the map.</p>
                </div>
                <div class="step reveal">
                    <div class="step-icon step-icon-green"><?= icon_svg('whatsapp') ?></div>
                    <b>3</b>
                    <h3>Hire on WhatsApp</h3>
                    <p>Hit "Hire on WhatsApp" and we'll confirm the price, dates and paperwork with you there.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================== ROUTES ============================== -->
    <section class="section section-alt" id="routes">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Most requested routes</h2>
                    <p>Rough one-way estimates for our most popular pickups — your quote may vary with vehicle and dates.</p>
                </div>
            </div>
            <div class="route-list">
                <?php foreach ($routes as $r): require __DIR__ . '/partials/route-row.php'; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== DAY TOURS ============================== -->
    <section class="section" id="tours">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Popular day tours</h2>
                    <p>A vehicle, a driver, and a route already planned — just pick a day.</p>
                </div>
            </div>
            <div class="tour-grid">
                <?php foreach ($tours as $t): require __DIR__ . '/partials/tour-card.php'; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== BEST DEALS ============================== -->
    <!-- PLACEHOLDER offers — see ContentModel::deals(). Replace with real ones. -->
    <section class="section section-alt" id="deals">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Best deals this month</h2>
                    <p class="placeholder-note">Sample offers for now — swap in your real current deals.</p>
                </div>
            </div>
            <div class="deal-grid">
                <?php foreach ($deals as $d): require __DIR__ . '/partials/deal-card.php'; endforeach; ?>
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
                <div class="quote-card reveal">
                    <h3>Request a detailed quote</h3>
                    <p>Give us a few more details and we'll come back with an exact price.</p>
                    <form class="quote-detail-form" id="quoteDetailForm">
                        <div class="quote-form-row">
                            <input type="text" name="name" placeholder="Your name" required>
                            <input type="tel" name="phone" placeholder="WhatsApp number" required>
                        </div>
                        <input type="text" name="dates" placeholder="Pickup date &amp; number of days">
                        <textarea name="message" placeholder="Vehicle type, route, anything else we should know" rows="3"></textarea>
                        <button type="submit" class="btn btn-primary">Send request</button>
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

    <!-- ============================== AREAS SERVED ============================== -->
    <section class="section" id="areas">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Rental &amp; tours across Sri Lanka</h2>
                    <p>Based in Colombo, and happy to arrange pickup and drop-off across the island.</p>
                </div>
            </div>
            <div class="areas-list reveal">
                <?php foreach ($areas as $area): ?>
                    <span class="area-chip"><?= icon_svg('pin') ?> <?= htmlspecialchars($area) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== ABOUT ============================== -->
    <section class="section section-alt" id="about">
        <div class="container about-grid">
            <div class="reveal">
                <h2>Your trusted travel partner in Sri Lanka</h2>
                <p><?= htmlspecialchars(SITE_NAME) ?> rents out a checked, ready-to-go fleet across Colombo — self-drive if you want the freedom, or with a driver if you'd rather not deal with the traffic. Every booking is confirmed by a real person on WhatsApp, not a form that disappears into an inbox.</p>
                <a href="#vehicles" class="btn btn-ghost">See the vehicles</a>
            </div>
            <div class="about-media reveal">
                <img src="https://loremflickr.com/700/500/roadtrip,srilanka?lock=900" alt="On the road in Sri Lanka" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ============================== VIDEO ============================== -->
    <!-- PLACEHOLDER — inert until VIDEO_EMBED_URL is set in config.php -->
    <section class="section" id="video">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>See <?= htmlspecialchars(SITE_NAME) ?> in action</h2>
                    <?php if (!VIDEO_EMBED_URL): ?>
                        <p class="placeholder-note">No video yet — this activates automatically once one is added.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (VIDEO_EMBED_URL): ?>
                <div class="video-embed reveal">
                    <iframe src="<?= htmlspecialchars(VIDEO_EMBED_URL) ?>" title="<?= htmlspecialchars(SITE_NAME) ?> video" allowfullscreen loading="lazy"></iframe>
                </div>
            <?php else: ?>
                <button class="video-placeholder reveal" type="button" onclick="alert('Video coming soon!')">
                    <img src="https://loremflickr.com/1200/675/srilanka,car?lock=910" alt="" loading="lazy">
                    <span class="video-play"><?= icon_svg('play') ?></span>
                </button>
            <?php endif; ?>
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

    <!-- ============================== GUIDES ============================== -->
    <!-- PLACEHOLDER topics — see ContentModel::guides(). No real posts yet. -->
    <section class="section" id="guides">
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <h2>Guides &amp; tips</h2>
                    <p class="placeholder-note">Starter topics — real articles go here once they're written.</p>
                </div>
            </div>
            <div class="guide-grid">
                <?php foreach ($guides as $g): require __DIR__ . '/partials/guide-card.php'; endforeach; ?>
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

<script src="js/locations-data.js?v=<?= ASSET_VERSION ?>"></script>
<script src="js/main.js?v=<?= ASSET_VERSION ?>"></script>
</body>
</html>
