<?php
// Updates an existing post.

if(!isset($action)) {
  http_response_code(403);
  json_error("Nice try, hackerboy.");
  exit;
}

if(!isset($_POST['url'])) {
  http_response_code(400);
  json_error("Missing 'url' parameter.");
  exit;
}

$url = normalize_url($_POST['url']);
$params = \urls\parse($url);

if(!$params) {
  http_response_code(404);
  json_error("The post you're trying to edit appears to be non-existant.");
  exit;
}

[$year, $id] = $params;
\store\get_post($year, $id);

// TODO(robin): implement post updating.

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
