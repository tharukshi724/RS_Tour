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
            ['icon' => 'headset', 'title' => 'Chauffeur rental', 'desc' => 'A local driver who knows the roads, so you can watch the scenery instead.'],
            ['icon' => 'route', 'title' => 'Airport transfers', 'desc' => 'Direct pickup or drop at the airport, timed to your flight.'],
            ['icon' => 'map', 'title' => 'Day tours', 'desc' => 'Sigiriya, Kandy, Galle and more — a driver, a route, and a full day sorted.'],
            ['icon' => 'car', 'title' => 'Long-distance hire', 'desc' => 'Multi-day road trips across the island, one vehicle for the whole route.'],
        ];
    }

    // TODO: placeholder estimates — replace with your real route prices.
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

    // TODO: placeholder prices/durations — set your real tour pricing.
    public static function tours(): array
    {
        return [
            ['title' => 'Sigiriya & Dambulla Day Tour', 'tag' => 'sigiriya,rock', 'price' => 22000, 'duration' => 'Full day &middot; ~10 hrs', 'blurb' => 'The ancient rock fortress and the cave temples, in one long day out from Colombo.'],
            ['title' => 'Galle Day Trip via Bentota', 'tag' => 'galle,fort', 'price' => 18000, 'duration' => 'Full day &middot; ~9 hrs', 'blurb' => 'Coastal drive down to the fort, with a stop at Bentota beach on the way back.'],
            ['title' => 'Kandy & Peradeniya Tour', 'tag' => 'kandy,temple', 'price' => 16000, 'duration' => 'Full day &middot; ~8 hrs', 'blurb' => 'The Temple of the Tooth and the botanical gardens, at an easy pace.'],
        ];
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
            ['value' => count(VehicleModel::all()) . '+', 'label' => 'vehicles listed'],
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

    // PLACEHOLDER — generic sample reviews, not real customers.
    public static function reviews(): array
    {
        return [
            ['name' => 'Kasun P.', 'rating' => 5, 'text' => 'Smooth pickup, vehicle was clean and exactly as pictured. Would rent again.'],
            ['name' => 'Amara S.', 'rating' => 5, 'text' => 'Booked a self-drive for a Kandy trip — easy WhatsApp process, no surprises on price.'],
            ['name' => 'Nimal F.', 'rating' => 4, 'text' => 'Chauffeur was on time and knew the routes well. Good value for a day tour.'],
        ];
    }

    // PLACEHOLDER — replace with your real aggregate rating once you have reviews.
    public static function reviewSummary(): array
    {
        return ['rating' => 4.8, 'count' => 0];
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
