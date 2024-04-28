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
$our_site = normalize_url(CANONICAL);

if(!str_starts_with($target, $our_site) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
  echo "You can only send webmentions for URLs hosted on this site.";
  exit;
}

// Validate whether the 'source' page actually contains a link to 'target'.

$response = \http\get($_POST['source']);
$source = $reponse['body'];

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

  \store\put_mention($year, $id, $_POST['source']);

  $url = \urls\post_url($year, $id);
}

\notifications\new_webmention($_POST['target'], $_POST['source']);

// Redirect to target page, useful in case of sending 
// a webmention via the form in the comment section.
header('Link: ' . $target);
