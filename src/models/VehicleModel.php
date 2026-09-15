<?php
/**
 * The fleet, straight from RS_Tours-Vehicles.xlsx (35 vehicles).
 *
 * One entry per MODEL, not per vehicle: the sheet has eight Altos and five
 * WagonRs, and eight identical cards would be noise, not choice. Each entry
 * carries its own registration numbers in 'units', so the site can say "8
 * available" and you still have the plate list in one place. Plates are never
 * printed on the page - they are here for your own records.
 *
 * To update after a sale or a new purchase: add or remove the plate in
 * 'units'. Nothing else changes - counts, totals and the "X available" badge
 * are all derived from that list.
 *
 * 'price' is null everywhere because the sheet has no rates in it. A null
 * price renders as "Price on request". Fill in a daily rate and that vehicle
 * starts showing the price instead, with no other change needed.
 */
class VehicleModel
{
    public static function all(): array
    {
        static $vehicles = null;
        if ($vehicles !== null) return $vehicles;

        $vehicles = [
            // ---------------------------------------------------------- Cars
            [
                'id' => 1, 'name' => 'Suzuki Alto', 'category' => 'Car',
                'seats' => 4, 'ac' => true, 'price' => null,
                'units' => ['CAJ-5712', 'KH-4025', 'CAK-5487', 'CAM-0845', 'CAM-0802', 'KR-8709', 'KQ-8047', 'CAI-6967'],
                'blurb' => 'Our most-hired car - light on fuel and easy to park anywhere in town.',
            ],
            [
                'id' => 2, 'name' => 'Suzuki WagonR', 'category' => 'Car',
                'seats' => 4, 'ac' => true, 'price' => null,
                'units' => ['CAG-9491', 'CBF-0026', 'CAQ-5858', 'CAL-7549', 'CAO-4860'],
                'blurb' => 'More headroom and boot space than the Alto, with the same easy running cost.',
            ],
            [
                'id' => 3, 'name' => 'Toyota Aqua', 'category' => 'Car',
                'seats' => 4, 'ac' => true, 'price' => null,
                'units' => ['CAK-5777'],
                'blurb' => 'Hybrid, quiet and very light on fuel - a comfortable pick for longer drives.',
            ],
            [
                'id' => 4, 'name' => 'Honda Fit', 'category' => 'Car',
                'seats' => 4, 'ac' => true, 'price' => null,
                'units' => ['KW-4037'],
                'blurb' => 'Roomy for its size and steady on the highway without drinking fuel.',
            ],

            // ----------------------------------------------------------- SUV
            [
                'id' => 5, 'name' => 'Honda Vezel', 'category' => 'SUV',
                'seats' => 4, 'ac' => true, 'price' => null,
                'units' => ['CAC-7518'],
                'blurb' => 'A compact SUV for when you want a higher seat and a bit more presence.',
            ],

            // ---------------------------------------------------------- Vans
            [
                'id' => 6, 'name' => 'Toyota KDH', 'category' => 'Van',
                'seats' => 14, 'ac' => true, 'price' => null,
                'units' => ['NB-5566'],
                'blurb' => 'Fourteen seats with AC - the standard choice for airport runs and group tours.',
            ],
            [
                'id' => 7, 'name' => 'Toyota KDH High Roof', 'category' => 'Van',
                'seats' => 14, 'ac' => true, 'price' => null,
                'units' => ['NC-0636', 'PG-6683'],
                'blurb' => 'The high-roof KDH - stand-up headroom and far more luggage space for long tours.',
            ],
            [
                'id' => 8, 'name' => 'Toyota Dolphin', 'category' => 'Van',
                'seats' => 10, 'seats_max' => 15, 'ac' => true, 'price' => null,
                'units' => ['63-1329', '58-7326'],
                'blurb' => 'A dependable AC van for day trips and family travel.',
            ],
            [
                'id' => 9, 'name' => 'Nissan Super Long', 'category' => 'Van',
                'seats' => 10, 'seats_max' => 14, 'ac' => true, 'price' => null,
                'units' => ['61-6590', '61-4011'],
                'blurb' => 'The extra length means real legroom and luggage room on long routes.',
            ],
            [
                'id' => 10, 'name' => 'Suzuki Every', 'category' => 'Van',
                'seats' => 7, 'ac' => true, 'price' => null,
                'units' => ['PG-4430'],
                'blurb' => 'Seven seats in a small van - right for a family or a small group with bags.',
            ],
            [
                'id' => 11, 'name' => 'Nissan Caravan', 'category' => 'Van',
                'seats' => 15, 'ac' => false, 'price' => null,
                'units' => ['252-4734'],
                'blurb' => 'A fifteen-seat non-AC van for shorter local runs, priced to match.',
            ],

            // ---------------------------------------------------------- Buses
            [
                'id' => 12, 'name' => 'Mitsubishi Bola Rosa', 'category' => 'Bus',
                'seats' => 25, 'seats_max' => 29, 'ac' => true, 'price' => null,
                'units' => ['NC-4424', 'ND-1004', 'ND-1257'],
                'blurb' => 'A 25 to 29 seat AC coach for weddings, office trips and school tours.',
            ],
            [
                'id' => 13, 'name' => 'Mitsubishi Baby Rosa', 'category' => 'Bus',
                'seats' => 28, 'ac' => true, 'price' => null,
                'units' => ['NC-1023', 'NC-8585', 'GH-3377'],
                'blurb' => 'Twenty-eight seats with AC - our workhorse for full-day group tours.',
            ],
            [
                'id' => 14, 'name' => 'Toyota Coaster', 'category' => 'Bus',
                'seats' => 28, 'ac' => true, 'price' => null,
                'units' => ['NC-7192'],
                'blurb' => 'Twenty-eight AC seats, comfortable enough for long-distance runs.',
            ],
            [
                'id' => 15, 'name' => 'Eicher Skyline', 'category' => 'Bus',
                'seats' => 32, 'ac' => false, 'price' => null,
                'units' => ['NC-6826', 'ND-2420'],
                'blurb' => 'Thirty-two seats, non-AC - the economical pick for a big group on a short route.',
            ],
            [
                'id' => 16, 'name' => 'Tata Ultra', 'category' => 'Bus',
                'seats' => 33, 'ac' => false, 'price' => null,
                'units' => ['NC-4406'],
                'blurb' => 'Our largest bus at thirty-three seats, non-AC, for the biggest groups.',
            ],
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

    /** Category names in the order they appear above - drives the filter. */
    public static function categories(): array
    {
        $cats = [];
        foreach (self::all() as $v) $cats[$v['category']] = true;
        return array_keys($cats);
    }

    /** How many actual vehicles are on the road (35), not how many listings. */
    public static function unitCount(): int
    {
        $total = 0;
        foreach (self::all() as $v) $total += self::units($v);
        return $total;
    }

    public static function units(array $vehicle): int
    {
        return max(1, count($vehicle['units'] ?? []));
    }

    /** "14" or "10-15" when a model comes in more than one seating layout. */
    public static function seatsLabel(array $vehicle): string
    {
        $min = (int) $vehicle['seats'];
        $max = (int) ($vehicle['seats_max'] ?? $min);
        return $max > $min ? $min . '-' . $max : (string) $min;
    }

    public static function acLabel(array $vehicle): string
    {
        return !empty($vehicle['ac']) ? 'AC' : 'Non-AC';
    }

    /** Daily rate if one is set, otherwise the honest fallback. */
    public static function priceLabel(array $vehicle): string
    {
        $price = $vehicle['price'] ?? null;
        return ($price === null || $price === '') ? 'Price on request' : format_lkr((int) $price);
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
            'Car' => 'hatchback,car',
            'SUV' => 'suv,car',
            'Van' => 'van,minivan',
            'Bus' => 'bus,coach',
        ];
        return $map[$category] ?? 'car';
    }

    // Deterministic photo set per vehicle (4 photos). LoremFlickr + a
    // category tag so images actually look like the right kind of vehicle,
    // and a numeric lock so a vehicle always shows the same 4 photos.
    // TODO: drop real photos of each model into public/images/fleet/ and
    // swap this out - stock photos are the one placeholder left in here.
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
