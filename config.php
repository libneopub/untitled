<?php
// Dynamic configuration based on the JSON store.

define('STORE', __DIR__ . "/data");
define('CONFIG', STORE . "/config.json");

$_DEFAULTS = [];

if($json = @json_decode(file_get_contents(CONFIG))) {
  foreach($json as $key => $value) {
    define(normalize_key($key), $value);
  }
} else {
  die("Failed to read config file.");
}

required('host');
required('site.title');
required('site.description');

fallback('force-https', false);
fallback('canonical', (FORCE_HTTPS ? "https" : "http") . "://" . HOST);

fallback('site.lang', "en");
fallback('author.main-site', CANONICAL);
fallback('notifications.admin', value("author.email"));
fallback('notifications.sender', "noreply@" . HOST);
fallback('notifications.webmention', true);

fallback('micropub-endpoint', CANONICAL . "/endpoint/micropub");
fallback('media-endpoint', CANONICAL . "/endpoint/media");
fallback('webmention-endpoint', CANONICAL . "/endpoint/webmention");
fallback('auth-endpoint', CANONICAL . "/endpoint/indieauth");
fallback('token-endpoint', "https://tokens.indieauth.com/token");
fallback('pingback-endpoint', 
  "https://webmention.io/webmention?forward=" . WEBMENTION_ENDPOINT);

// Helpers

function required($key) {
  if(!defined(normalize_key($key))) {
    die("Missing required key '$key' in config.");
  }
}

function fallback($key, $value) {
  global $_DEFAULTS;
  $key = normalize_key($key);
  $_DEFAULTS[$key] = $value;
  
  if(!defined($key)) {
    define($key, $value);
  }
}

function value($key) {
  $key = normalize_key($key);
  return defined($key) ? constant($key) : null;
}

function canonical_value($key) {
  $key = normalize_key($key);
  return is_default($key) ? "" : value($key);
}

function is_default($key) {
  global $_DEFAULTS;
  $key = normalize_key($key);

  if(defined($key) and isset($_DEFAULTS[$key])) {
    return $_DEFAULTS[$key] == constant($key);
  } else {
    return false;
  }
}

function normalize_key($key) {
  $key = str_replace("-", "_", $key);
  $key = str_replace(".", "_", $key);

  return strtoupper($key);
}