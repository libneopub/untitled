<?php
// Updates an existing post.

if(!isset($action)) {
  http_response_code(403);
  \resp\json_error("Nice try, hackerboy.");
  exit;
}

if(!isset($_POST['url'])) {
  http_response_code(400);
  \resp\json_error("Missing 'url' parameter.");
  exit;
}

$url = normalize_url($_POST['url']);
$params = \urls\parse($url);

if(!$params) {
  http_response_code(404);
  \resp\json_error("The post you're trying to edit appears to be non-existant.");
  exit;
}

[$volume, $id] = $params;
\store\get_post($volume, $id);

// TODO(robin): implement post updating.

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
