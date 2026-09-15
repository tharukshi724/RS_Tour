<nav class="navbar" id="navbar">
    <div class="navbar-inner">
        <a href="index.php" class="brand">
            <img class="brand-mark" src="<?= htmlspecialchars(public_asset_url('images/logo_new.png?v=' . ASSET_VERSION)) ?>" alt="<?= htmlspecialchars(SITE_NAME) ?> logo" width="46" height="26">
            <span class="brand-text">
                <span class="brand-name"><?= htmlspecialchars(SITE_NAME) ?></span>
                <span class="brand-slogan"><?= htmlspecialchars(SITE_SLOGAN) ?></span>
            </span>
        </a>
        <button class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="index.php">Home</a>
            <a href="index.php#packages">Our Packages</a>
            <a href="index.php#vehicles">Our Vehicles</a>
            <a href="index.php#contact">Contact Us</a>
            <a href="index.php?page=book&mode=online" class="nav-cta">Book now</a>
        </div>
    </div>
</nav>
