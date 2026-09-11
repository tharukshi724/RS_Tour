<?php /** Expects $t (a tour array) in scope. */ ?>
<div class="tour-card reveal">
    <div class="tour-media">
        <div class="vcard-media-skeleton"></div>
        <img src="https://loremflickr.com/700/460/<?= htmlspecialchars($t['tag']) ?>?lock=<?= crc32($t['title']) ?>" alt="<?= htmlspecialchars($t['title']) ?>" loading="lazy" onload="this.classList.add('is-loaded'); this.previousElementSibling.style.opacity=0;">
    </div>
    <div class="tour-body">
        <span class="tour-duration"><?= icon_svg('clock') ?> <?= $t['duration'] ?></span>
        <h3><?= htmlspecialchars($t['title']) ?></h3>
        <p><?= htmlspecialchars($t['blurb']) ?></p>
        <div class="tour-foot">
            <span class="tour-price">Rs. <?= number_format($t['price']) ?></span>
            <a class="btn btn-primary btn-sm" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>?text=<?= urlencode("Hi " . SITE_NAME . "! I'd like to book the " . $t['title'] . ".") ?>">Enquire</a>
        </div>
    </div>
</div>
