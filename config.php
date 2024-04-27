<?php

$HOST = "localhost:4000";
$CANONICAL = "http://$HOST"; // OMIT THE TRAILING SLASH
$MAIN_SITE = $CANONICAL;

$SITE_LANG = "en";
$SITE_TITLE = "@Robijntje";
$SITE_DESCRIPTION = "Verified (€15/year for the domain)";

$MICROPUB_ENDPOINT =  "$CANONICAL/endpoint/micropub";
$MEDIA_ENDPOINT = "$CANONICAL/endpoint/media";
$WEBMENTION_ENDPOINT = "$CANONICAL/endpoint/webmention";

// I can't be bothered to actually implement pingback. Sowwy!
$PINGBACK_ENDPOINT = "https://webmention.io/webmention?forward=$WEBMENTION_ENDPOINT";

// TODO(robin): these services are external for now,
// but later I'd like to write these endpoints myself :)
$TOKEN_ENDPOINT = "https://tokens.indieauth.com/token";
$AUTH_ENDPOINT = "https://indieauth.com/auth";
