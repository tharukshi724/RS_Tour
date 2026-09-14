<footer class="site-footer" id="contact">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <img class="brand-mark" src="<?= htmlspecialchars(public_asset_url('images/logo_new.png?v=' . ASSET_VERSION)) ?>" alt="<?= htmlspecialchars(SITE_NAME) ?> logo" width="46" height="26">
                <span class="brand-text">
                    <span class="brand-name"><?= htmlspecialchars(SITE_NAME) ?></span>
                    <span class="brand-slogan"><?= htmlspecialchars(SITE_SLOGAN) ?></span>
                </span>
            </div>
            <p class="footer-tagline"><?= htmlspecialchars(SITE_TAGLINE) ?></p>
        </div>
        <div class="footer-contact">
            <h3>Contact Us</h3>
            <a class="footer-contact-row" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>">
                <?= icon_svg('whatsapp') ?>
                <span>+<?= htmlspecialchars(WHATSAPP_NUMBER) ?> (WhatsApp)</span>
            </a>
            <a class="footer-contact-row" href="tel:<?= htmlspecialchars(str_replace(' ', '', CONTACT_PHONE)) ?>">
                <?= icon_svg('phone') ?>
                <span><?= htmlspecialchars(CONTACT_PHONE) ?></span>
            </a>
            <a class="footer-contact-row" href="mailto:<?= htmlspecialchars(CONTACT_EMAIL) ?>">
                <?= icon_svg('mail') ?>
                <span><?= htmlspecialchars(CONTACT_EMAIL) ?></span>
            </a>
            <div class="footer-contact-row">
                <?= icon_svg('pin') ?>
                <span><?= htmlspecialchars(CONTACT_LOCATION) ?></span>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?> &ndash; <?= htmlspecialchars(SITE_SLOGAN) ?>. All rights reserved.</span>
    </div>
</footer>
