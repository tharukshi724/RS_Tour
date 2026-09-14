<?php
/**
 * Routing.
 *
 * Deliberately query-string based (index.php?page=vehicle&id=1) rather than
 * path-based (index.php/vehicle/1) — it needs no .htaccess / mod_rewrite
 * configuration and works identically on every PHP host out of the box.
 *
 * Want pretty URLs later (e.g. /vehicle/1)? Add an .htaccess rewrite rule
 * that maps that path to this same query string, and nothing here needs to
 * change — the controllers don't know or care how the URL looked.
 */

function dispatch(): void
{
    $page = $_GET['page'] ?? 'home';

    switch ($page) {
        case 'vehicle':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            VehicleController::show($id);
            break;

        case 'package':
            $slug = $_GET['slug'] ?? '';
            PackageController::show($slug);
            break;

        case 'home':
        default:
            HomeController::index();
            break;
    }
}
