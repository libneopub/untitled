<?php
// Creates a new post.

if(!isset($action)) {
  http_response_code(403);
  \resp\json_error("Nice try, hackerboy.");
  exit;
}

$title = $_POST['name'];
$content = $_POST['content'] ?? $_POST['summary'];
$reply_to = $_POST['in-reply-to'];

// If `published` is given, use that, otherwise,
// fall back to the current datetime.
$published = $_POST['published'] ?? date("c");
$published = date("c", strtotime($published));

if(isset($_FILES['photo'])) {
  if(isset($_POST['photo'])) {
    syslog(LOG_WARN, "Micropub: you provided both a photo upload and URL. The URL will be ignored.");
  }

  $tmp_file = $_FILES['photo']['tmp_name'];

  // This checks if someone isn't maliciously trying
  // to overwrite /etc/passwd or something.
  if(!is_uploaded_file($tmp_file) or !getimagesize($tmp_file)) {
    http_response_code(400);
    \resp\json_error("Bad photo upload. Try again.");
    exit;
  }
 
  $path = \store\upload_photo($tmp_file);

  if(!$path) {
    http_response_code(500);
    \resp\json_error("Something went wrong while saving your photo.");
    exit;
  }

  $post = array(
    "id" => uniqid("IMG_"),
    "type" => "photo",
    "path" => $path,
    "caption" => $content,
    "reply-to" => $reply_to,
    "published" => $published
   );

} else if(isset($_POST['photo'])) {
  if(is_array($_POST['photo'])) {
    syslog(LOG_WARN, "Micropub: multiple photos provided, but the endpoint only supports a single photo per post, other photos will be ignored.");

    $url = $_POST['photo'][0];
  } else {
    $url = $_POST['photo'];
  }

  $post = array(
    "id" => uniqid("IMG_"),
    "type" => "photo",
    "url" => $url,
    "caption" => $content,
    "reply-to" => $reply_to,
    "published" => $published
  );

} else {
  if(!$content) {
    http_response_code(400);
    \resp\json_error("Missing 'content' or 'summary' value in post payload.");
    exit;
  }

  $path = \store\upload_text($content);

  $post = array(
    "id" => uniqid(),
    "type" => "toot",
    "title" => $title,
    "path" => $path,
    "reply-to" => $reply_to,
    "published" => $published
  );
}

\core\publish_post($post);
\core\send_webmentions($post);

header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
header("Location: " . \urls\post_url($post));
