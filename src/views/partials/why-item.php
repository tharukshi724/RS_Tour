<?php /** Expects $w (a why-us array) in scope. */ ?>
<div class="why-item reveal">
    <div class="why-icon"><?= icon_svg($w['icon']) ?></div>
    <div>
        <h3><?= htmlspecialchars($w['title']) ?></h3>
        <p><?= htmlspecialchars($w['desc']) ?></p>
    </div>
</div>
