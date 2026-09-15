<?php
/**
 * Reviews API - the one way in and out of data/reviews.json.
 *
 *   GET    api/reviews.php              published reviews (open, no key)
 *   GET    api/reviews.php?all=1        every review incl. hidden ones (key)
 *   POST   api/reviews.php              add one review                (key)
 *   PATCH  api/reviews.php?id=rv_x      { "published": false }        (key)
 *   DELETE api/reviews.php?id=rv_x      remove one review             (key)
 *
 * Anything that changes data must send the key as an X-Api-Key header
 * (?key=... also works, for tools that can't set headers - avoid it where you
 * can, since URLs end up in server logs). Set the key in src/config/config.php.
 *
 * Example - add a review from the command line:
 *   curl -X POST https://yoursite.lk/api/reviews.php \
 *        -H "X-Api-Key: YOUR_KEY" -H "Content-Type: application/json" \
 *        -d '{"name":"Nimal","trip":"Airport hire","source":"whatsapp","rating":5,"text":"Great service."}'
 */

require_once __DIR__ . '/../../src/config/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Reading is public, so let other pages/apps fetch it cross-origin.
if ($method === 'GET' || $method === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
}
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Api-Key');
    http_response_code(204);
    exit;
}

function respond(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** hash_equals keeps the comparison constant-time, so the key can't be guessed
 *  character by character from response timings. */
function authorised(): bool
{
    $key = $_SERVER['HTTP_X_API_KEY'] ?? ($_GET['key'] ?? '');
    $expected = defined('REVIEWS_API_KEY') ? REVIEWS_API_KEY : '';
    if ($expected === '' || $expected === 'change-me-to-a-long-random-string') {
        return false;
    }
    return is_string($key) && hash_equals($expected, $key);
}

function requireKey(): void
{
    if (!authorised()) {
        respond(401, ['ok' => false, 'error' => 'Missing or invalid X-Api-Key. Set REVIEWS_API_KEY in src/config/config.php.']);
    }
}

/** Accepts a JSON body or ordinary form fields, so it works from curl, from
 *  fetch(), and from a plain HTML form alike. */
function input(): array
{
    $raw = file_get_contents('php://input');
    if (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return $decoded;
    }
    return $_POST ?: [];
}

try {
    switch ($method) {
        case 'GET':
            $wantsAll = isset($_GET['all']) && $_GET['all'] !== '0';
            if ($wantsAll) {
                requireKey();
                $reviews = ReviewStore::all();
            } else {
                $reviews = ReviewStore::published();
            }
            respond(200, ['ok' => true, 'count' => count($reviews), 'reviews' => $reviews]);
            break;

        case 'POST':
            requireKey();
            $review = ReviewStore::add(input());
            respond(201, ['ok' => true, 'review' => $review]);
            break;

        case 'PATCH':
        case 'PUT':
            requireKey();
            $id = (string) ($_GET['id'] ?? (input()['id'] ?? ''));
            if ($id === '') respond(400, ['ok' => false, 'error' => 'id is required']);
            $body = input();
            if (!array_key_exists('published', $body)) {
                respond(400, ['ok' => false, 'error' => 'Only "published" can be changed - send {"published": true|false}']);
            }
            $updated = ReviewStore::setPublished($id, (bool) $body['published']);
            if ($updated === null) respond(404, ['ok' => false, 'error' => 'No review with id ' . $id]);
            respond(200, ['ok' => true, 'review' => $updated]);
            break;

        case 'DELETE':
            requireKey();
            $id = (string) ($_GET['id'] ?? (input()['id'] ?? ''));
            if ($id === '') respond(400, ['ok' => false, 'error' => 'id is required']);
            if (!ReviewStore::delete($id)) respond(404, ['ok' => false, 'error' => 'No review with id ' . $id]);
            respond(200, ['ok' => true, 'deleted' => $id]);
            break;

        default:
            header('Allow: GET, POST, PATCH, DELETE, OPTIONS');
            respond(405, ['ok' => false, 'error' => $method . ' is not supported here']);
    }
} catch (InvalidArgumentException $e) {
    respond(422, ['ok' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    // Never leak file paths or stack traces to the caller.
    error_log('reviews-api: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Could not save the review. Check that the data/ folder is writable.']);
}
