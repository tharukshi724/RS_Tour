<?php
/**
 * Vehicle fleet data + everything derived from it.
 * Swap the array in all() for a real database query later without
 * touching any controller or view — they only call these static methods.
 */
class VehicleModel
{
    public static function all(): array
    {
        static $vehicles = null;
        if ($vehicles !== null) return $vehicles;

        $vehicles = [
            ['id' => 1, 'name' => 'Toyota Aqua', 'category' => 'Car', 'seats' => 5, 'transmission' => 'Auto', 'fuel' => 'Hybrid', 'price' => 6500, 'blurb' => 'Light on fuel, easy to park — the everyday city runabout.'],
            ['id' => 2, 'name' => 'Suzuki WagonR', 'category' => 'Car', 'seats' => 4, 'transmission' => 'Auto', 'fuel' => 'Petrol', 'price' => 5000, 'blurb' => 'Compact and nimble — perfect for tight Colombo streets.'],
            ['id' => 3, 'name' => 'Toyota Prado', 'category' => 'SUV', 'seats' => 7, 'transmission' => 'Auto', 'fuel' => 'Diesel', 'price' => 18500, 'blurb' => 'Commanding road presence with room for the whole crew.'],
            ['id' => 4, 'name' => 'Mitsubishi Montero', 'category' => 'SUV', 'seats' => 7, 'transmission' => 'Auto', 'fuel' => 'Diesel', 'price' => 16000, 'blurb' => 'Built for the hill country climbs and the long weekend trip.'],
            ['id' => 5, 'name' => 'Toyota HiAce', 'category' => 'Van', 'seats' => 14, 'transmission' => 'Manual', 'fuel' => 'Diesel', 'price' => 14000, 'blurb' => 'The group-trip workhorse — airport runs and tours alike.'],
            ['id' => 6, 'name' => 'Honda Dio', 'category' => 'Bike', 'seats' => 2, 'transmission' => 'Auto', 'fuel' => 'Petrol', 'price' => 1800, 'blurb' => 'Beat the traffic — quick, cheap, and easy to ride.'],
            ['id' => 7, 'name' => 'Bajaj Pulsar', 'category' => 'Bike', 'seats' => 2, 'transmission' => 'Manual', 'fuel' => 'Petrol', 'price' => 2200, 'blurb' => 'A bit more power for the coastal ride out of town.'],
            ['id' => 8, 'name' => 'Mercedes E-Class', 'category' => 'Luxury', 'seats' => 4, 'transmission' => 'Auto', 'fuel' => 'Petrol', 'price' => 32000, 'blurb' => 'For the wedding, the pickup, the day you want to arrive well.'],
        ];
        return $vehicles;
    }

    public static function find(int $id): ?array
    {
        foreach (self::all() as $v) {
            if ($v['id'] === $id) return $v;
        }
        return null;
    }

    public static function categories(): array
    {
        $cats = [];
        foreach (self::all() as $v) $cats[$v['category']] = true;
        return array_keys($cats);
    }

    public static function related(int $excludeId, int $limit = 3): array
    {
        $current = self::find($excludeId);
        $all = self::all();
        $sameCategory = array_filter($all, fn($v) => $v['id'] !== $excludeId && $current && $v['category'] === $current['category']);
        $rest = array_filter($all, fn($v) => $v['id'] !== $excludeId && (!$current || $v['category'] !== $current['category']));
        $ordered = array_merge(array_values($sameCategory), array_values($rest));
        return array_slice($ordered, 0, $limit);
    }

    private static function categoryPhotoTags(string $category): string
    {
        $map = [
            'Car'    => 'hatchback,car',
            'SUV'    => 'suv,car',
            'Van'    => 'van,minivan',
            'Bike'   => 'motorcycle,scooter',
            'Luxury' => 'luxury,sedan,car',
        ];
        return $map[$category] ?? 'car';
    }

    // Deterministic photo set per vehicle (4 photos). LoremFlickr + a
    // category tag so images actually look like the right kind of vehicle,
    // and a numeric lock so a vehicle always shows the same 4 photos.
    public static function photos(array $vehicle): array
    {
        $tags = self::categoryPhotoTags($vehicle['category']);
        $photos = [];
        for ($i = 1; $i <= 4; $i++) {
            $lock = ($vehicle['id'] * 10) + $i;
            $photos[] = "https://loremflickr.com/900/650/{$tags}?lock={$lock}";
        }
        return $photos;
    }
}
