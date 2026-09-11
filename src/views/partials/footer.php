<footer class="site-footer" id="contact">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <span class="brand-mark"><?= htmlspecialchars(BRAND_INITIAL) ?></span>
                <span class="brand-name"><?= htmlspecialchars(SITE_NAME) ?></span>
            </div>
            <p class="footer-tagline"><?= htmlspecialchars(SITE_TAGLINE) ?></p>
        </div>
        <div class="footer-contact">
            <h3>Contact Us</h3>
            <a class="footer-contact-row" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>">
                <?= icon_svg('whatsapp') ?>
                <span>+<?= htmlspecialchars(WHATSAPP_NUMBER) ?></span>
            </a>
            <div class="footer-contact-row">
                <?= icon_svg('pin') ?>
                <span>Colombo, Sri Lanka</span>
            </div>
            <div class="footer-contact-row">
                <?= icon_svg('clock') ?>
                <span>Open 7:00 AM &ndash; 10:00 PM</span>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?>. All rights reserved.</span>
    </div>
</footer>
