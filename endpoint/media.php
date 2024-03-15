<?php
// Media endpoint for uploading static files.
// Currently only photos are supported.

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

include __DIR__ . "/auth.php";

if(empty($_FILES)) {
  header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
  echo "Please provide media. This is a media endpoint after all :p";
  exit;
}

if(!isset($_FILES["photo"])) {
  header($_SERVER["SERVER_PROTOCOL"] . " 418 I'm a teapot");
  echo "Only 'photo' uploads are supported.";
  exit;
}

$tmp_file = $_FILES["photo"]["tmp_name"];

// This checks if someone isn't maliciously trying
// to overwrite /etc/passwd or something.
if(!is_uploaded_file($tmp_file) || !getimagesize($tmp_file)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    echo "Bad photo upload. Try again.";
    exit;
}

$path = \store\upload_photo($tmp_file);

if(!$path) {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    echo "Something went wrong while saving your photo.";
    exit;
}

header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
header("Location: " . \urls\photo_url($path));
