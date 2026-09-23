<?php
/**
 * Feedback storage.
 *
 * Customer feedback submitted through the site's "Add Feedback" modal is
 * saved to data/feedback.json (through public/api/feedback.php), the same
 * pattern ReviewStore.php uses for testimonials. It's a separate file from
 * reviews.json on purpose - this is raw, unmoderated submissions, not the
 * curated quotes shown on the homepage. Once there's an admin panel, that's
 * where this file gets read, approved/rejected and optionally promoted into
 * reviews.json - nothing here needs to change for that.
 *
 * The file sits outside public/, so it can never be fetched directly over
 * HTTP - the API is the only way in or out.
 */
class FeedbackStore
{
    public static function file(): string
    {
        return __DIR__ . '/../../data/feedback.json';
    }

    /** Every piece of feedback on file, newest last. */
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
        return [];
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $f) {
            if (($f['id'] ?? '') === $id) return $f;
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
        $comments = trim((string) ($in['comments'] ?? ''));
        if ($name === '') throw new InvalidArgumentException('name is required');
        if ($comments === '') throw new InvalidArgumentException('comments is required');
        if (self::len($name) > 80) throw new InvalidArgumentException('name is too long (max 80)');
        if (self::len($comments) > 1200) throw new InvalidArgumentException('comments is too long (max 1200)');

        $rating = (int) ($in['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) throw new InvalidArgumentException('rating must be 1-5');

        return [
            'id'         => 'fb_' . bin2hex(random_bytes(6)),
            'name'       => $name,
            'rating'     => $rating,
            'comments'   => $comments,
            'status'     => 'new', // new | reviewed | promoted — for the future admin panel
            'created_at' => date('c'),
        ];
    }

    /** Add one piece of feedback and persist. Returns the stored record. */
    public static function add(array $in): array
    {
        $record = self::normalise($in);
        $all = self::all();
        $all[] = $record;
        self::save($all);
        return $record;
    }

    /**
     * Write the whole list back. Writes to a temp file and renames it, so a
     * crash mid-write can never leave a half-written feedback.json behind.
     */
    public static function save(array $feedback): void
    {
        $path = self::file();
        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Cannot create data directory: ' . $dir);
        }
        $json = json_encode(
            array_values($feedback),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($json === false) {
            throw new RuntimeException('Could not encode feedback as JSON');
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

    /* Sinhala/mixed input is multi-byte, and mbstring isn't guaranteed on
       every shared host - these count by character either way. */
    private static function len(string $s): int
    {
        if (function_exists('mb_strlen')) return mb_strlen($s, 'UTF-8');
        $count = preg_match_all('/./us', $s);
        return $count === false ? strlen($s) : $count;
    }
}
