<?php
require_once __DIR__ . '/../src/config/bootstrap.php';
header('Content-Type: application/xml; charset=utf-8');
$base = rtrim(SITE_URL, '/');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= htmlspecialchars($base) ?>/</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <?php foreach (VehicleModel::all() as $v): ?>
    <url>
        <loc><?= htmlspecialchars($base) ?>/index.php?page=vehicle&amp;id=<?= $v['id'] ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>
</urlset>
