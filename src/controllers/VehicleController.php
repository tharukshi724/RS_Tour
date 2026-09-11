<?php
class VehicleController
{
    public static function show(int $id): void
    {
        $vehicle = VehicleModel::find($id);

        if (!$vehicle) {
            http_response_code(404);
            require __DIR__ . '/../views/not-found.php';
            return;
        }

        $photos = VehicleModel::photos($vehicle);
        $catSlug = category_slug($vehicle['category']);
        $related = VehicleModel::related($vehicle['id'], 3);

        require __DIR__ . '/../views/vehicle.php';
    }
}
