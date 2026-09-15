<?php
/**
 * One real customer review. Expects $rv (a review array from
 * ContentModel::reviews()) in scope. Rendered inside a .tcar-item by the
 * caller, so no outer spacing lives here.
 */
$isSi = ($rv['lang'] ?? 'en') === 'si';
$src  = $rv['source'] ?? 'whatsapp';

// Where the message came from. Anything we have no badge for falls back to a
// neutral star, so a new source added through the API can never render blank.
$srcLabels = [
    'whatsapp' => 'Sent on WhatsApp',
    'facebook' => 'Shared on Facebook',
    'google'   => 'Google review',
    'other'    => 'Customer feedback',
];
$srcIcons = ['whatsapp' => 'whatsapp', 'facebook' => 'facebook'];
$srcLabel = $srcLabels[$src] ?? 'Customer feedback';
$srcIcon  = $srcIcons[$src] ?? 'star';
?>
<article class="review-card">
    <span class="review-quote" aria-hidden="true"><?= icon_svg('quote') ?></span>

    <div class="review-stars" role="img" aria-label="<?= (int) $rv['rating'] ?> out of 5">
        <?php for ($i = 0; $i < 5; $i++): ?>
            <span class="<?= $i < $rv['rating'] ? 'is-filled' : '' ?>" aria-hidden="true"><?= icon_svg('star') ?></span>
        <?php endfor; ?>
    </div>

    <blockquote class="review-text<?= $isSi ? ' is-si' : '' ?>"<?= $isSi ? ' lang="si"' : '' ?>>
        &ldquo;<?= htmlspecialchars($rv['text']) ?>&rdquo;
    </blockquote>

    <?php if (!empty($rv['text_en'])): ?>
        <p class="review-text-en" lang="en"><?= htmlspecialchars($rv['text_en']) ?></p>
    <?php endif; ?>

    <footer class="review-foot">
        <span class="review-avatar" aria-hidden="true"><?= htmlspecialchars(strtoupper(substr($rv['name'], 0, 1))) ?></span>
        <span class="review-who">
            <span class="review-name"><?= htmlspecialchars($rv['name']) ?></span>
            <?php if (!empty($rv['trip'])): ?><span class="review-trip"><?= htmlspecialchars($rv['trip']) ?></span><?php endif; ?>
        </span>
        <span class="review-source review-source--<?= htmlspecialchars($src) ?>" title="<?= htmlspecialchars($srcLabel) ?>">
            <?= icon_svg($srcIcon) ?><span class="sr-only"><?= htmlspecialchars($srcLabel) ?></span>
        </span>
    </footer>
</article>
