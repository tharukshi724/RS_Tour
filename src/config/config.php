<?php
/**
 * Site-wide configuration.
 * Every value a business owner needs to change to launch this site lives
 * here — nowhere else in the codebase should hardcode these.
 */

define('SITE_NAME', 'RS Tours');
define('SITE_SLOGAN', 'Trust & Secure');
define('SITE_TAGLINE', 'Self-drive & chauffeur rentals, Mawanella');
define('SITE_TAGLINE_SI', 'නොසැලී පෙරටම......'); 
define('LOGO_PATH', 'images/logo_new.png');

define('WHATSAPP_NUMBER', '94714857998'); // +94 71 485 7998
define('CONTACT_PHONE', '071 485 7998');
define('CONTACT_EMAIL', 'roshantr93@gmail.com');
define('CONTACT_LOCATION', 'Mawanella, 71500, Sri Lanka');

define('SITE_URL', 'https://example.com'); // TODO: your real domain — used in SEO tags, sitemap, structured data
define('VIDEO_EMBED_URL', ''); // TODO: e.g. 'https://www.youtube.com/embed/XXXXXXXXXXX' — leave blank to show the placeholder

// Key for public/api/reviews.php. Anything that CHANGES reviews (adding,
// hiding, deleting) must send it as an X-Api-Key header; reading is open.
// TODO: replace with your own long random string before going live, and never
// commit the real one to a public repo.
define('REVIEWS_API_KEY', 'change-me-to-a-long-random-string');

// Key for reading public/api/feedback.php (GET ?all=1) once the admin panel
// exists. Submitting feedback (POST) needs no key - it's the public "Add
// Feedback" button on the site.
define('FEEDBACK_API_KEY', 'change-me-to-a-long-random-string');

define('ASSET_VERSION', '26');
