<?php
// Webmention endpoint for pulling comments from other sites.

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

if (!isset($_POST['source'])) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  echo "Missing 'source' parameter.";
  exit;
}

if (!isset($_POST['target'])) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  echo "Missing 'target' parameter.";
  exit;
}

$target = normalize_url($_POST['target']);

if(!str_starts_with($target, $CANONICAL)) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  echo "You can only send webmentions for URLs hosted on this site.";
  exit;
}

// Validate whether the 'source' page actually contains a link to 'target'.

ob_start();
$ch = curl_init($_POST['source']);
curl_setopt($ch, CURLOPT_USERAGENT, $CANONICAL);
curl_setopt($ch, CURLOPT_HEADER, 0);
$ok = curl_exec($ch);
curl_close($ch);
$source = ob_get_contents();
ob_end_clean();

if (stristr($source, $_POST['target'])) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  echo "Your page doesn't actually mention mine.";
  exit;
}

// Everything looks good (*yay!). Let's process the Webmention!

header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');

$uri = parse_url($target);
$path = $uri['path'];

$path_pattern = '/^\/(\d{4})\/([a-zA-Z0-9]+)$/';

if(preg_match($path_pattern, $path, $matches)) {
  $year = $matches[1];
  $id = $matches[2];

  \store\put_mention($year, $id, $source);
}

header('Link: ' . $target);
