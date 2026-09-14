<?php
/**
 * App bootstrap. public/index.php requires this one file, which pulls in
 * everything else in the right order.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

require_once __DIR__ . '/../models/VehicleModel.php';
require_once __DIR__ . '/../models/ContentModel.php';

require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/VehicleController.php';
require_once __DIR__ . '/../controllers/PackageController.php';
