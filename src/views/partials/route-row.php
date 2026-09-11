<?php /** Expects $r (a route array) in scope. */ ?>
<div class="route-row reveal">
    <div class="route-points">
        <span><?= icon_svg('pin') ?> <?= htmlspecialchars($r['from']) ?></span>
        <span class="route-arrow"><?= icon_svg('arrow') ?></span>
        <span><?= icon_svg('pin') ?> <?= htmlspecialchars($r['to']) ?></span>
    </div>
    <div class="route-price">from <b>Rs. <?= number_format($r['price']) ?></b></div>
</div>
