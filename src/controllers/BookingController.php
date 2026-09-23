<?php
class BookingController
{
    public static function show(): void
    {
        // Optional prefill — set when arriving from a vehicle's
        // "Book this vehicle" button (index.php?page=book&vehicle=...).
        $vehicleName = isset($_GET['vehicle']) ? trim((string) $_GET['vehicle']) : '';
        $vehicleCategory = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
        $vehiclePrice = isset($_GET['price']) ? (int) $_GET['price'] : 0;

        // Which single call-to-action this page shows — set by whichever
        // button the customer came in on, so we never show two competing
        // CTAs at once.
        $mode = ($_GET['mode'] ?? '') === 'whatsapp' ? 'whatsapp' : 'online';

        $vehicleTypes = ['Bike', 'Three Wheeler', 'Car', 'Van', 'Bus'];
        $matchedType = '';
        foreach ($vehicleTypes as $t) {
            if (strcasecmp($t, $vehicleCategory) === 0) { $matchedType = $t; break; }
        }
        if (!$matchedType && $vehicleCategory !== '' && stripos($vehicleCategory, 'suv') !== false) {
            $matchedType = 'Car';
        }

        require __DIR__ . '/../views/book.php';
    }
}
