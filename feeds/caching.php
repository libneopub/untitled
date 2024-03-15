<?php
// Handles caching via conditional requests.

// We want to only serve the request if the feed
// has changed, which we determine based on the
// `last_updated` property.
$last_updated = \store\last_updated();

$_HEADERS = array();
foreach (getallheaders() as $name => $value) {
    $_HEADERS[$name] = $value;
}

$last_modified = date("r", strtotime($last_updated));
$etag = md5($last_modified);

header("Last-Modified: $last_modified");
header("ETag: $etag");

if ($_HEADERS["If-Modified-Since"] !== $last_modified) $stale = true;
if ($_HEADERS["If-None-Match"] !== $etag) $stale = true;

if ($stale) {
  header($_SERVER["SERVER_PROTOCOL"] . " 304 Not Modified");
  exit;
}
