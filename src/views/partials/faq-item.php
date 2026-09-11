<?php /** Expects $f (a faq array) and $i (index) in scope. */ ?>
<details class="faq-item reveal" <?= $i === 0 ? 'open' : '' ?>>
    <summary>
        <span><?= htmlspecialchars($f['q']) ?></span>
        <span class="faq-toggle"><?= icon_svg('plus') ?></span>
    </summary>
    <p><?= htmlspecialchars($f['a']) ?></p>
</details>
