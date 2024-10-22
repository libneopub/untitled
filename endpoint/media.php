<?php
// Media endpoint for uploading static files.
// Currently only photos are supported.

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

include __DIR__ . "/inc/authenticate.php";

if(empty($_FILES)) {
  http_response_code(400);
  \resp\json_error("Please provide media. This is a media endpoint after all.");
  exit;
}

if(!isset($_FILES['photo'])) {
  http_response_code(501);
  \resp\json_error("Only 'photo' uploads are supported.");
  exit;
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

http_response_code(201);
header("Location: " . \urls\photo_url($path));
