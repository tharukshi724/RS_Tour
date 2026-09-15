<?php
/**
 * Review storage.
 *
 * Reviews live in data/reviews.json, not in PHP code, so new feedback can be
 * added at runtime (through public/api/reviews.php) without editing or
 * redeploying anything. The file sits OUTSIDE public/, so it can never be
 * fetched directly over HTTP - the API is the only way in or out.
 *
 * If the file is missing or unreadable the built-in seed below is used, so the
 * reviews section can never render empty because of a permissions problem.
 */
class ReviewStore
{
    public const SOURCES = ['whatsapp', 'facebook', 'google', 'other'];
    public const LANGS   = ['en', 'si'];

    public static function file(): string
    {
        return __DIR__ . '/../../data/reviews.json';
    }

    /** Every review on file, published or not. */
    public static function all(): array
    {
        $path = self::file();
        if (is_readable($path)) {
            $raw = file_get_contents($path);
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded, 'is_array'));
            }
        }
        return self::seed();
    }

    /** Only what the site should show, newest additions last. */
    public static function published(): array
    {
        return array_values(array_filter(self::all(), static function ($r) {
            return ($r['published'] ?? true) === true;
        }));
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $r) {
            if (($r['id'] ?? '') === $id) return $r;
        }
        return null;
    }

    /**
     * Validate + normalise one submission. Throws InvalidArgumentException
     * with a human-readable message on bad input, so the API can return it.
     */
    public static function normalise(array $in): array
    {
        $name = trim((string) ($in['name'] ?? ''));
        $text = trim((string) ($in['text'] ?? ''));
        if ($name === '') throw new InvalidArgumentException('name is required');
        if ($text === '') throw new InvalidArgumentException('text is required');
        if (self::len($name) > 80)   throw new InvalidArgumentException('name is too long (max 80)');
        if (self::len($text) > 1200) throw new InvalidArgumentException('text is too long (max 1200)');

        $source = strtolower(trim((string) ($in['source'] ?? 'whatsapp')));
        if (!in_array($source, self::SOURCES, true)) {
            throw new InvalidArgumentException('source must be one of: ' . implode(', ', self::SOURCES));
        }

        $lang = strtolower(trim((string) ($in['lang'] ?? 'en')));
        if (!in_array($lang, self::LANGS, true)) {
            throw new InvalidArgumentException('lang must be one of: ' . implode(', ', self::LANGS));
        }

        $rating = (int) ($in['rating'] ?? 5);
        if ($rating < 1 || $rating > 5) throw new InvalidArgumentException('rating must be 1-5');

        $trip   = trim((string) ($in['trip'] ?? ''));
        $textEn = trim((string) ($in['text_en'] ?? ''));

        $record = [
            'id'         => $in['id'] ?? ('rv_' . bin2hex(random_bytes(6))),
            'name'       => $name,
            'trip'       => self::cut($trip, 60),
            'source'     => $source,
            'rating'     => $rating,
            'lang'       => $lang,
            'text'       => $text,
            'published'  => array_key_exists('published', $in) ? (bool) $in['published'] : true,
            'created_at' => $in['created_at'] ?? date('c'),
        ];
        if ($textEn !== '') {
            $record['text_en'] = self::cut($textEn, 1200);
        }
        return $record;
    }

    /** Add one review and persist. Returns the stored record. */
    public static function add(array $in): array
    {
        $record = self::normalise($in);
        $all = self::all();
        $all[] = $record;
        self::save($all);
        return $record;
    }

    /** Publish / unpublish an existing review. Returns it, or null if absent. */
    public static function setPublished(string $id, bool $published): ?array
    {
        $all = self::all();
        $found = null;
        foreach ($all as $i => $r) {
            if (($r['id'] ?? '') === $id) {
                $all[$i]['published'] = $published;
                $found = $all[$i];
                break;
            }
        }
        if ($found === null) return null;
        self::save($all);
        return $found;
    }

    public static function delete(string $id): bool
    {
        $all = self::all();
        $kept = array_values(array_filter($all, static function ($r) use ($id) {
            return ($r['id'] ?? '') !== $id;
        }));
        if (count($kept) === count($all)) return false;
        self::save($kept);
        return true;
    }

    /**
     * Write the whole list back. Writes to a temp file and renames it, so a
     * crash mid-write can never leave a half-written reviews.json behind.
     */
    public static function save(array $reviews): void
    {
        $path = self::file();
        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Cannot create data directory: ' . $dir);
        }
        $json = json_encode(
            array_values($reviews),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($json === false) {
            throw new RuntimeException('Could not encode reviews as JSON');
        }
        $tmp = $path . '.tmp';
        if (@file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
            throw new RuntimeException('Cannot write to ' . $dir . ' - check folder permissions (755/775)');
        }
        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException('Cannot replace ' . $path);
        }
    }

    /* Sinhala text is multi-byte, and mbstring isn't guaranteed on every
       shared host - these count and cut by character either way. */
    private static function len(string $s): int
    {
        if (function_exists('mb_strlen')) return mb_strlen($s, 'UTF-8');
        $count = preg_match_all('/./us', $s);
        return $count === false ? strlen($s) : $count;
    }

    private static function cut(string $s, int $max): string
    {
        if (self::len($s) <= $max) return $s;
        if (function_exists('mb_substr')) return mb_substr($s, 0, $max, 'UTF-8');
        return preg_match('/^.{0,' . $max . '}/us', $s, $m) ? $m[0] : substr($s, 0, $max);
    }

    /** Fallback content if data/reviews.json is missing - the same real
     *  customer messages the file ships with. */
    public static function seed(): array
    {
        return ContentModel::seedReviews();
    }
}
