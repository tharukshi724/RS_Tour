<?php
class PackageController
{
    public static function show(string $slug): void
    {
        $package = ContentModel::findPackage($slug);

        if (!$package) {
            http_response_code(404);
            require __DIR__ . '/../views/not-found.php';
            return;
        }

        require __DIR__ . '/../views/package.php';
    }
}
