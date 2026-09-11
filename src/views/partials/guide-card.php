<?php /** Expects $g (a guide array) in scope. */ ?>
<a class="guide-card reveal" href="#">
    <div class="guide-media">
        <div class="vcard-media-skeleton"></div>
        <img src="https://loremflickr.com/600/380/<?= htmlspecialchars($g['tag']) ?>?lock=<?= crc32($g['title']) ?>" alt="" loading="lazy" onload="this.classList.add('is-loaded'); this.previousElementSibling.style.opacity=0;">
    </div>
    <div class="guide-body">
        <h3><?= htmlspecialchars($g['title']) ?></h3>
        <p><?= htmlspecialchars($g['excerpt']) ?></p>
        <span class="guide-link">Read more <?= icon_svg('arrow') ?></span>
    </div>
</a>
