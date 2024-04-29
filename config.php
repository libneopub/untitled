<?php

define('HOST', "localhost:4000");
define('CANONICAL', "http://" . HOST); // OMIT THE TRAILING SLASH
define('MAIN_SITE', CANONICAL); // Also the site used for logging in via IndieAuth.

define('SITE_LANG', "en");
define('SITE_TITLE', "@Robijntje");
define('SITE_DESCRIPTION', "Verified (€15/year for the domain)");

define('AUTHOR_NAME', SITE_TITLE);
define('AUTHOR_EMAIL', "you@example.com");
define('AUTHOR_PICTURE', "/uploads/axcelott.jpg");

define('FORCE_HTTPS', false); // Disabled by default, see https://1mb.club/blog/https-redirects/

define('PUBLIC_EMAIL', "noreply@example.com");
define('WEBMASTER_EMAIL', "you@example.com");
define('WEBMENTION_NOTIFICATIONS', true);

define('ENCRYPTION_KEY', '317f48e381d6eed8765a0418723ad64f6ac6de528433d2d758ef39750557f6e9');
define('PASSWORD_HASH', 'f5ac0102a91979ded2570f85804d854c');

define('MICROPUB_ENDPOINT', CANONICAL . "/endpoint/micropub");
define('MEDIA_ENDPOINT', CANONICAL . "/endpoint/media");
define('WEBMENTION_ENDPOINT', CANONICAL . "/endpoint/webmention");
define('AUTH_ENDPOINT', CANONICAL . "/endpoint/auth");

// I can't be bothered to actually implement pingback. Sowwy!
define('PINGBACK_ENDPOINT', "https://webmention.io/webmention?forward=" . WEBMENTION_ENDPOINT);

// TODO(robin): these services are external for now,
// but later I'd like to write these endpoints myself :)
define('TOKEN_ENDPOINT', "https://tokens.indieauth.com/token");
