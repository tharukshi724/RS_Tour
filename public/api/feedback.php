<?php
/**
 * Feedback API - the one way in and out of data/feedback.json.
 *
 *   POST api/feedback.php              add one piece of feedback   (open, no key)
 *   GET  api/feedback.php?all=1        every submission            (key required)
 *
 * Submitting feedback is deliberately open (it's a public "Add Feedback"
 * button on the site), but reading it back is not - that's for the admin
 * panel this gets merged into later. Set the key in src/config/config.php.
 */

require_once __DIR__ . '/../../src/config/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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

/** hash_equals keeps the comparison constant-time. */
function feedbackAuthorised(): bool
{
    $key = $_SERVER['HTTP_X_API_KEY'] ?? ($_GET['key'] ?? '');
    $expected = defined('FEEDBACK_API_KEY') ? FEEDBACK_API_KEY : '';
    if ($expected === '' || $expected === 'change-me-to-a-long-random-string') {
        return false;
    }
    return is_string($key) && hash_equals($expected, $key);
}

/** Accepts a JSON body or ordinary form fields, so it works from fetch()
 *  (the feedback modal) and from a plain HTML form alike. */
function feedbackInput(): array
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
        case 'POST':
            $feedback = FeedbackStore::add(feedbackInput());
            respond(201, ['ok' => true, 'feedback' => ['id' => $feedback['id']]]);
            break;

        case 'GET':
            if (!feedbackAuthorised()) {
                respond(401, ['ok' => false, 'error' => 'Missing or invalid X-Api-Key. Set FEEDBACK_API_KEY in src/config/config.php.']);
            }
            $all = FeedbackStore::all();
            respond(200, ['ok' => true, 'count' => count($all), 'feedback' => $all]);
            break;

        default:
            header('Allow: GET, POST, OPTIONS');
            respond(405, ['ok' => false, 'error' => $method . ' is not supported here']);
    }
} catch (InvalidArgumentException $e) {
    respond(422, ['ok' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    // Never leak file paths or stack traces to the caller.
    error_log('feedback-api: ' . $e->getMessage());
    respond(500, ['ok' => false, 'error' => 'Could not save your feedback. Please try again in a moment.']);
}
