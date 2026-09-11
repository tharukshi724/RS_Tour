<?php /** Expects $d (a deal array) in scope. */ ?>
<div class="deal-card reveal">
    <div class="deal-media">
        <div class="vcard-media-skeleton"></div>
        <img src="https://loremflickr.com/640/420/<?= htmlspecialchars($d['tag']) ?>?lock=<?= crc32($d['title']) ?>" alt="<?= htmlspecialchars($d['title']) ?>" loading="lazy" onload="this.classList.add('is-loaded'); this.previousElementSibling.style.opacity=0;">
        <span class="deal-badge"><?= htmlspecialchars($d['save']) ?></span>
    </div>
    <div class="deal-body">
        <h3><?= htmlspecialchars($d['title']) ?></h3>
        <p><?= htmlspecialchars($d['blurb']) ?></p>
        <a class="btn btn-sm btn-go" href="https://wa.me/<?= htmlspecialchars(WHATSAPP_NUMBER) ?>?text=<?= urlencode("Hi " . SITE_NAME . "! I'd like to ask about: " . $d['title']) ?>"><?= icon_svg('whatsapp') ?> Ask about this</a>
    </div>
</div>
