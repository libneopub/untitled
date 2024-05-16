<?php

define('PUBB_VERSION', "0.1a");
define('STORE', __DIR__ . "/data");
define('CMS', '/cms');

if($json = @json_decode(file_get_contents(STORE . "/config.json"))) {
  foreach($json as $key => $value) {
    define(normalize_key($key), $value);
  }
} else {
  die("Failed to read config file.");
}

required('host');
required('site.title');
required('site.description');

// Optional

// author.name
// author.email
// author.picture
// notifications.admin

// TODO(robin): also make description optional

// Required, with defaults

add_default('force-https', false);
add_default('canonical', (FORCE_HTTPS ? "https" : "http") . "://" . HOST);

add_default('site.lang', "en");
add_default('author.main-site', CANONICAL);
add_default('notifications.sender', "noreply@" . HOST);
add_default('notifications.webmention', true);

add_default('micropub-endpoint', CANONICAL . "/endpoint/micropub");
add_default('media-endpoint', CANONICAL . "/endpoint/media");
add_default('webmention-endpoint', CANONICAL . "/endpoint/webmention");
add_default('auth-endpoint', CANONICAL . "/endpoint/auth");
// TODO(robin): Implement this myself :)
add_default('token-endpoint', "https://tokens.indieauth.com/token");

// I can't be bothered to actually implement pingback. Sowwy!
add_default('pingback-endpoint', "https://webmention.io/webmention?forward=" . WEBMENTION_ENDPOINT);

// Helpers

function normalize_key($key) {
  $key = str_replace("-", "_", $key);
  $key = str_replace(".", "_", $key);

  return strtoupper($key);
}

function add_default($key, $value) {
  $key = normalize_key($key);
  
  if(!defined($key)) {
    define($key, $value);
  }
}

function required($key) {
  if(!defined(normalize_key($key))) {
    die("Missing required key '$key' in config.");
  }
}
