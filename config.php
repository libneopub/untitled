<?php

$HOST = "u.roblog.nl";
$CANONICAL = "https://$HOST"; // OMIT THE TRAILING SLASH
$MAIN_SITE = $CANONICAL;

$SITE_LANG = "en";
$SITE_TITLE = "@Robijntje";
$SITE_DESCRIPTION = "Verified (€15/year for the domain)";

$MICROPUB_ENDPOINT =  "$CANONICAL/endpoint/micropub.php";
$MEDIA_ENDPOINT = "$CANONICAL/endpoint/media.php";

// TODO(robin): these services are external for now,
// but later I'd like to write these endpoints myself :)
$TOKEN_ENDPOINT = "https://tokens.indieauth.com/token";
$AUTH_ENDPOINT = "https://indieauth.com/auth";
$WEBMENTION_ENDPOINT = "https://webmention.io/$HOST/webmention";
$PINGBACK_ENDPOINT = "https://webmention.io/$HOST/xmlrp";

// NOTE(robin): later use this:
// https://webmention.io/webmention?forward=https://example.com/webmention/endpoint
