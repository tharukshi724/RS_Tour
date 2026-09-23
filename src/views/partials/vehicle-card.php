<?php
/**
 * Single-vehicle card. Expects $v (a vehicle array) in scope.
 * Used by the fleet grid and the "you might also like" strip - the one
 * place this markup exists, so every card on the site stays in sync.
 */
$photos = VehicleModel::photos($v);
$catSlug = category_slug($v['category']);
$hasPrice = ($v['price'] ?? null) !== null;
?>
<a class="vcard reveal" href="index.php?page=vehicle&id=<?= $v['id'] ?>"
   data-category="<?= htmlspecialchars($catSlug) ?>"
   style="--cat-color: var(--cat-<?= htmlspecialchars($catSlug) ?>)">
    <div class="vcard-media">
        <div class="vcard-media-skeleton"></div>
        <img src="<?= htmlspecialchars($photos[0]) ?>" alt="<?= htmlspecialchars($v['name']) ?>" loading="lazy"
             onload="this.classList.add('is-loaded'); this.previousElementSibling.style.opacity=0;">
        <span class="vcard-tag vcard-tag-overlay"><?= htmlspecialchars($v['category']) ?></span>
        <?php if ($hasPrice): ?>
            <span class="vcard-price-overlay"><?= number_format((int) $v['price']) ?><small>/day</small></span>
        <?php else: ?>
            <span class="vcard-price-overlay is-ask">Price on request</span>
        <?php endif; ?>
    </div>
    <div class="vcard-body">
        <div class="vcard-top">
            <span class="vcard-name"><?= htmlspecialchars($v['name']) ?></span>
        </div>
        <p class="vcard-blurb"><?= htmlspecialchars($v['blurb']) ?></p>
        <div class="vcard-foot">
            <div class="vcard-specs">
                <span class="spec-icon"><?= icon_svg('seat') ?> <?= htmlspecialchars(VehicleModel::seatsLabel($v)) ?></span>
                <span class="spec-icon"><?= icon_svg('snow') ?> <?= htmlspecialchars(VehicleModel::acLabel($v)) ?></span>
            </div>
            <span class="vcard-link">View details <?= icon_svg('arrow') ?></span>
        </div>
    </div>
</a>
