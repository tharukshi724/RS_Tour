<?php /** Expects $pkg (a package array) in scope. */ ?>
<a class="package-card reveal" href="index.php?page=package&amp;slug=<?= urlencode($pkg['slug']) ?>">
    <div class="package-media">
        <div class="vcard-media-skeleton"></div>
        <img src="<?= htmlspecialchars(ContentModel::packageImageUrl($pkg)) ?>" alt="<?= htmlspecialchars($pkg['title']) ?>" loading="lazy" onload="this.classList.add('is-loaded'); this.previousElementSibling.style.opacity=0;">
        <span class="package-icon-badge"><?= icon_svg($pkg['icon']) ?></span>
    </div>
    <div class="package-body">
        <h3><?= htmlspecialchars($pkg['title']) ?></h3>
        <p><?= htmlspecialchars($pkg['summary']) ?></p>
        <span class="package-link">View Details <?= icon_svg('arrow') ?></span>
    </div>
</a>
