<?php
// Delete an existing post.

if(!isset($action)) {
  http_response_code(403);
  echo "Nice try, hackerboy.";
  exit;
}

if(!isset($_POST['url'])) {
  http_response_code(400);
  echo "Missing 'url' parameter.";
  exit;
}

$url = normalize_url($_POST['url']);
$params = \urls\parse($url);

if(!$params) {
  http_response_code(404);
  echo "The post you're trying to remove appears to be gone already?";
  exit;
}

[$year, $id] = $params;
\store\delete_post($year, $id);

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
