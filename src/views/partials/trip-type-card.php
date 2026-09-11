<?php /** Expects $tt (a trip-type array) in scope. */ ?>
<div class="trip-type-card reveal">
    <div class="trip-type-icon"><?= icon_svg($tt['icon']) ?></div>
    <h3><?= htmlspecialchars($tt['title']) ?></h3>
    <p><?= htmlspecialchars($tt['desc']) ?></p>
</div>
