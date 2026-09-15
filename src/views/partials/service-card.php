<?php /** Expects $s (a service array) in scope. */ ?>
<div class="service-card">
    <div class="service-icon"><?= icon_svg($s['icon']) ?></div>
    <h3><?= htmlspecialchars($s['title']) ?></h3>
    <p><?= htmlspecialchars($s['desc']) ?></p>
</div>
