<?php
/**
 * Every homepage content block that isn't a vehicle. One array per section;
 * edit here and every place it renders updates — the markup lives once in
 * src/views/partials/.
 */
class ContentModel
{
    public static function services(): array
    {
        return [
            ['icon' => 'key', 'title' => 'Self-drive rental', 'desc' => 'Pick it up, drive yourself, drop it off — no driver, no schedule but your own.'],
            ['icon' => 'plane', 'title' => 'Airport', 'desc' => 'Direct pickup or drop at the airport, timed to your flight.'],
            ['icon' => 'heart', 'title' => 'Wedding', 'desc' => 'Elegant transport for your special day, from arrivals to the grand exit.'],
            ['icon' => 'star', 'title' => 'Graduation', 'desc' => 'Comfortable rides for graduation day, family pickups, and celebration travel.'],
            ['icon' => 'map', 'title' => 'Day tours', 'desc' => 'Sigiriya, Kandy, Galle and more — a driver, a route, and a full day sorted.'],
            ['icon' => 'car', 'title' => 'Long-distance hire', 'desc' => 'Multi-day road trips across the island, one vehicle for the whole route.'],
        ];
    }

    public static function routes(): array
    {
        return [
            ['from' => 'Colombo Airport (CMB)', 'to' => 'Colombo City', 'price' => 4500],
            ['from' => 'Colombo', 'to' => 'Kandy', 'price' => 12000],
            ['from' => 'Colombo', 'to' => 'Galle', 'price' => 9500],
            ['from' => 'Negombo', 'to' => 'Sigiriya', 'price' => 15500],
            ['from' => 'Kandy', 'to' => 'Ella', 'price' => 18000],
            ['from' => 'Colombo', 'to' => 'Nuwara Eliya', 'price' => 14000],
        ];
    }

    // Real service packages — Airport transfers, BMICH events, Wedding hire.
    // Each package has one or more priced groups; every line item gets its
    // own WhatsApp inquire button on the detail page.
    public static function packages(): array
    {
        return [
            [
                'slug' => 'airport',
                'title' => 'Airport',
                'icon' => 'route',
                'image' => 'airport,departures',
                'summary' => 'Drop-off, pickup, or a round trip to the airport — pick the vehicle that fits your group.',
                'groups' => [
                    [
                        'label' => 'Drop Only',
                        'items' => [
                            ['name' => 'Alto, WagonR', 'price' => 12000],
                            ['name' => 'Mini Van (Every)', 'price' => 13000],
                            ['name' => 'Normal Van (Caravan, Dolphin)', 'price' => 15000],
                            ['name' => 'Bus', 'price' => 15000],
                        ],
                    ],
                    [
                        'label' => 'Pickup Only',
                        'items' => [
                            ['name' => 'Alto, WagonR', 'price' => 12000],
                            ['name' => 'Mini Van (Every)', 'price' => 13000],
                            ['name' => 'Normal Van (Caravan, Dolphin)', 'price' => 15000],
                            ['name' => 'Bus', 'price' => 15000],
                        ],
                    ],
                    [
                        'label' => 'Up & Down (Both ways)',
                        'items' => [
                            ['name' => 'Alto, WagonR', 'price' => 15000],
                            ['name' => 'Mini Van (Every)', 'price' => 16000],
                            ['name' => 'Normal Van (Caravan, Dolphin)', 'price' => 18000],
                            ['name' => 'Bus', 'price' => 28500],
                            ['name' => 'KDH', 'price' => 20000],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'bmich',
                'title' => 'BMICH',
                'icon' => 'map',
                'image' => 'convention,hall',
                'summary' => 'Transport for BMICH events — pick the vehicle size that fits your party.',
                'groups' => [
                    [
                        'label' => null,
                        'items' => [
                            ['name' => 'Alto, WagonR', 'price' => 16500],
                            ['name' => 'Mini Van (Every)', 'price' => 17500],
                            ['name' => 'Normal Van (Caravan, Dolphin)', 'price' => 17500],
                            ['name' => 'KDH', 'price' => 22000],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'wedding',
                'title' => 'Wedding',
                'icon' => 'car',
                'image' => 'bride,wedding',
                'summary' => 'A decorated vehicle for the big day, priced by distance — vehicle decoration included free.',
                'groups' => [
                    [
                        'label' => 'Per Day Package',
                        'items' => [
                            ['name' => '20 km', 'price' => 14000],
                            ['name' => '40 km', 'price' => 18000],
                            ['name' => 'Vehicle Decoration', 'price' => null, 'free' => true],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function findPackage(string $slug): ?array
    {
        foreach (self::packages() as $p) {
            if ($p['slug'] === $slug) return $p;
        }
        return null;
    }

    // Real photo if you've uploaded one to public/images/packages/{slug}.*,
    // otherwise a generic (loosely-matching) stock photo as a placeholder.
    // Stock-photo auto-matching can't reliably find a specific place like
    // BMICH — dropping a real photo in that folder is the permanent fix,
    // and needs no code change; this just starts picking it up.
    public static function packageImageUrl(array $package): string
    {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $rootFile = __DIR__ . '/../../public/images/' . $package['slug'] . '.' . $ext;
            if (file_exists($rootFile)) {
                return public_asset_url('images/' . $package['slug'] . '.' . $ext);
            }
        }

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $file = __DIR__ . '/../../public/images/packages/' . $package['slug'] . '.' . $ext;
            if (file_exists($file)) {
                return public_asset_url('images/packages/' . $package['slug'] . '.' . $ext);
            }
        }
        return 'https://loremflickr.com/900/600/' . $package['image'] . '?lock=' . crc32($package['slug']);
    }

    // PLACEHOLDER — seasonal-sounding offers, not live promotions.
    public static function deals(): array
    {
        $month = date('F');
        return [
            ['title' => 'Weekday self-drive discount', 'tag' => 'car,city', 'save' => '15% off', 'blurb' => 'Book any car Monday to Thursday and take 15% off the daily rate.'],
            ['title' => '3-day+ long weekend rate', 'tag' => 'suv,mountain', 'save' => 'Rs. 3,000 off', 'blurb' => 'Rent an SUV or van for 3 days or more and save on the total.'],
            ['title' => $month . ' airport transfer special', 'tag' => 'airport,car', 'save' => 'Fixed Rs. 4,000', 'blurb' => 'Flat rate airport pickup or drop within Colombo, this month only.'],
        ];
    }

    public static function whyUs(): array
    {
        return [
            ['icon' => 'shield', 'title' => 'Verified vehicles', 'desc' => 'Every vehicle is inspected before it goes out — no surprises on pickup day.'],
            ['icon' => 'tag', 'title' => 'Transparent pricing', 'desc' => 'The price you see is what you pay. No hidden fees added at pickup.'],
            ['icon' => 'headset', 'title' => '24/7 WhatsApp support', 'desc' => 'Real replies from real people, any time you need to reach us.'],
            ['icon' => 'check', 'title' => 'Flexible pickup & drop', 'desc' => 'Your current location, a search address, or a pin you drop yourself.'],
        ];
    }

    // TODO: vehicle count is real; the other two are placeholders until you
    // have real figures — not invented claims.
    public static function stats(): array
    {
        return [
            ['value' => (string) VehicleModel::unitCount(), 'label' => 'vehicles in our fleet'],
            ['value' => '24/7', 'label' => 'WhatsApp support'],
            ['value' => '<10 min', 'label' => 'average reply time'],
        ];
    }

    public static function tripTypes(): array
    {
        return [
            ['icon' => 'car', 'title' => 'City rental', 'desc' => 'Short trips and daily errands around Colombo, no long-term commitment.'],
            ['icon' => 'route', 'title' => 'Long-distance rental', 'desc' => 'Multi-day trips across the island — hill country, coast, or both.'],
            ['icon' => 'map', 'title' => 'Airport transfers', 'desc' => 'One-way or return, timed to your flight, no waiting around.'],
        ];
    }

    public static function areas(): array
    {
        return [
            'Colombo', 'Kandy', 'Galle', 'Negombo', 'Jaffna', 'Ella',
            'Nuwara Eliya', 'Sigiriya', 'Bentota', 'Trincomalee', 'Anuradhapura', 'Batticaloa',
        ];
    }

    public static function faqs(): array
    {
        return [
            ['q' => 'What documents do I need to rent a vehicle?', 'a' => 'A valid driving licence (international permit if you\'re a visitor), your NIC or passport, and a refundable security deposit.'],
            ['q' => 'Can I get a driver instead of self-drive?', 'a' => 'Yes — every vehicle can be booked with a chauffeur. Just mention it when you hire on WhatsApp.'],
            ['q' => 'Is fuel included in the price?', 'a' => 'No, fuel isn\'t included unless we agree otherwise. Vehicles go out with a set amount and should come back the same.'],
            ['q' => 'How do I actually book?', 'a' => 'Open a vehicle, set your pickup and drop locations, then tap "Hire on WhatsApp" — we confirm dates and paperwork with you there.'],
            ['q' => 'Can I extend my rental period?', 'a' => 'Usually yes. Message us on WhatsApp before your scheduled return time and we\'ll sort out an extension if the vehicle is free.'],
        ];
    }

    // Customer reviews now live in data/reviews.json, added and removed at
    // runtime through public/api/reviews.php - see ReviewStore. Nothing here
    // needs editing to publish new feedback.
    public static function reviews(): array
    {
        return ReviewStore::published();
    }

    // The content data/reviews.json is seeded with: real customer feedback,
    // each one transcribed from an actual WhatsApp chat or Facebook comment
    // (screenshots kept by the business). Sinhala entries keep the customer's
    // own wording in 'text' and carry an English rendering in 'text_en'.
    // Used only as a fallback if the JSON file is missing or unreadable.
    public static function seedReviews(): array
    {
        return [
            [
                'name'    => 'Kaushalya Wickramasinghe',
                'trip'    => 'Airport hire',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'en',
                'text'    => 'Thank you so much for the airport hire your company arranged for us with Mr. Sangeeth. Excellent timing, safe driving and great customer service. I highly recommend RS Tours for any sort of vehicle hiring needs.',
            ],
            [
                'name'    => 'Thushara Aththanayake',
                'trip'    => 'Wedding hire, Gampola',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'si',
                'text'    => 'ඇත්තටම මගේ වෙඩිං එකට මම කාර් එකක් ගත්තා. වෙඩිං එකේ වැඩ සේරම කරගෙන නුවර එළියෙත් ගිහින් දවස් 02ක් ඉඳලා ආවා. වාහනය ගත්ත දවස් 04ට මට ගියේ තෙල් වියදමත් එක්ක 18000ක් වගේ සුළු මුදලක්.',
                'text_en' => 'I hired a car for my wedding. We got everything done, then drove up to Nuwara Eliya and stayed two days. For the four days I had the vehicle it cost me only about Rs. 18,000 - fuel included.',
            ],
            [
                'name'    => 'Nalin Senevirathna',
                'trip'    => 'Wedding hire',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'si',
                'text'    => 'කාර් එකත් සුපිරි, කිසි අවුලක් නැහැ. බය නැතුව යන්න පුළුවන්. මේ සමාගමට දිනෙන් දින හරියන්න ඕනි.',
                'text_en' => 'The car was superb - not a single problem, you can travel without a worry. This company deserves to grow bigger by the day.',
            ],
            [
                'name'    => 'Boyagama Vidyalaya staff',
                'trip'    => 'School trip, Kumbalwela',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'si',
                'text'    => '2025.08.12 දින කුඹල්වෙල චාරිකාවේ, රියදුරු ලෙස තම වගකීම මැනවින් ඉටු කල අජිත් මහතාට, පේරාදෙණිය බෝයගම විද්‍යාලයීය කාර්ය මණ්ඩලයේ හද පිරි ප්‍රණාමය පුදමි.',
                'text_en' => 'Our heartfelt thanks to Mr. Ajith, who carried out his duty as driver so well on the Kumbalwela trip of 12 Aug 2025 - from the staff of Boyagama Vidyalaya, Peradeniya.',
            ],
            [
                'name'    => 'Auto Kleen, Mawanella',
                'trip'    => 'Family trip, Galle & Matara',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'en',
                'text'    => 'Great, thank you. Will get back for future travels. Good driver for a family trip.',
            ],
            [
                'name'    => 'GKUC',
                'trip'    => 'Staff bus hire',
                'source'  => 'whatsapp',
                'rating'  => 5,
                'lang'    => 'en',
                'text'    => 'Your bus is superb. We\'ll give you all our hires from now on - our team is fully satisfied.',
            ],
            [
                'name'    => 'Sunee Gunasinghe',
                'trip'    => 'Group tour',
                'source'  => 'facebook',
                'rating'  => 5,
                'lang'    => 'en',
                'text'    => 'This is the best transport service for your tours.',
            ],
            [
                'name'    => 'Sadali Randika',
                'trip'    => 'Bus tour',
                'source'  => 'facebook',
                'rating'  => 5,
                'lang'    => 'si',
                'text'    => 'ඊළඟ පාරත් SENU ANGEL තමයි.',
                'text_en' => 'Next trip too, it is Senu Angel for us.',
            ],
        ];
    }

    // Aggregate for the section header — derived from the real reviews above,
    // so it can never drift out of sync with them.
    public static function reviewSummary(): array
    {
        $reviews = self::reviews();
        $count = count($reviews);
        if ($count === 0) {
            return ['rating' => 0.0, 'count' => 0];
        }
        $sum = array_sum(array_column($reviews, 'rating'));
        return ['rating' => round($sum / $count, 1), 'count' => $count];
    }

    // PLACEHOLDER — generic starter topics, not published articles yet.
    public static function guides(): array
    {
        return [
            ['title' => 'A first-timer\'s guide to driving in Sri Lanka', 'tag' => 'srilanka,road', 'excerpt' => 'What the roads are actually like, and what to expect on your first self-drive.'],
            ['title' => '5 day trips you can do from Colombo', 'tag' => 'srilanka,travel', 'excerpt' => 'Sigiriya, Galle, Kandy and more — which ones fit into a single day.'],
            ['title' => 'Self-drive vs. chauffeur: which should you pick?', 'tag' => 'srilanka,car', 'excerpt' => 'The honest trade-offs between driving yourself and hiring a driver.'],
        ];
    }
}
