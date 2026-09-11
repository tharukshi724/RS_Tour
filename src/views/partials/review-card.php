<?php /** Expects $rv (a review array) in scope. */ ?>
<div class="review-card reveal">
    <div class="review-stars">
        <?php for ($i = 0; $i < 5; $i++): ?>
            <span class="<?= $i < $rv['rating'] ? 'is-filled' : '' ?>"><?= icon_svg('star') ?></span>
        <?php endfor; ?>
    </div>
    <p>&ldquo;<?= htmlspecialchars($rv['text']) ?>&rdquo;</p>
    <span class="review-name"><?= htmlspecialchars($rv['name']) ?></span>
</div>
