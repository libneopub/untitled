<?php

define('HOST', "localhost:4000");
define('CANONICAL', "http://" . HOST); // OMIT THE TRAILING SLASH
define('MAIN_SITE', CANONICAL);

define('SITE_LANG', "en");
define('SITE_TITLE', "@Robijntje");
define('SITE_DESCRIPTION', "Verified (€15/year for the domain)");

define('AUTHOR_NAME', SITE_TITLE);
define('AUTHOR_EMAIL', "you@example.com");
define('AUTHOR_PICTURE', "/uploads/axcelott.jpg");

define('MICROPUB_ENDPOINT', CANONICAL . "/endpoint/micropub");
define('MEDIA_ENDPOINT', CANONICAL . "/endpoint/media");
define('WEBMENTION_ENDPOINT', CANONICAL . "/endpoint/webmention");

// Disabled, see https://1mb.club/blog/https-redirects/
define('FORCE_HTTPS', false);

// Notification settings
define('PUBLIC_EMAIL', "noreply@example.com");
define('WEBMASTER_EMAIL', "you@example.com");
define('WEBMENTION_NOTIFICATIONS', true);

// I can't be bothered to actually implement pingback. Sowwy!
define('PINGBACK_ENDPOINT', "https://webmention.io/webmention?forward=" . WEBMENTION_ENDPOINT);

// TODO(robin): these services are external for now,
// but later I'd like to write these endpoints myself :)
define('TOKEN_ENDPOINT', "https://tokens.indieauth.com/token");
define('AUTH_ENDPOINT', "https://indieauth.com/auth");
