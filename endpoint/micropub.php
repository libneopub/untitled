<?php
// Micropub endpoint for creating new posts.

// NOTE(robin): I *should* refactor the error return stuff to
// adhere to the spec (aka return an JSON object with "error" key),
// but that means letting go of my beloved "418 I'm a teapot", which
// I am, quite frankly, not willing to do. So fuck the spec.

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

// Configuration requests don't need authentication
if ($_GET["q"] === "config") {
    header($_SERVER['SERVER_PROTOCOL'] . " 200 OK");
    echo json_encode(["media-endpoint" => $MEDIA_ENDPOINT]);
    exit;
}

include __DIR__ . "/authenticate.php";

// First, perform some checks to filter out requests that use features that
// we (intentionally) don't support.

if (isset($_POST['action'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 418 I'm a teapot");
    echo "Deleting and restoring posts is unsupported.";
    exit;
}

if (isset($_POST['repost-of']) || isset($_POST['like-of']) || isset($_POST['bookmark-of'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 418 I'm a teapot");
    echo "Reposts, likes, or bookmarks are unsupported, use a reply instead.";
    exit;
}

if (!empty($_FILES) && !isset($_FILES['photo'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 418 I'm a teapot");
    echo "Only 'photo' uploads are supported.";
    exit;
}

// Okey, request lookin' good, let's process it :D

$title = $_POST['name'];
$content = $_POST['content'] ?? $_POST['summary'];
$reply_to = $_POST['in-reply-to'];

// If `published` is given, use that, otherwise,
// fall back to the current datetime.
$published = $_POST['published'] ?? date("Y");
$published = date("c", strtotime($published));

if (isset($_FILES['photo'])) {
    if (isset($_POST['photo'])) {
        echo "Warning: you provided both a photo upload and URL. The URL will be ignored.";
    }

    $tmp_file = $_FILES['photo']['tmp_name'];

    // This checks if someone isn't maliciously trying
    // to overwrite /etc/passwd or something.
    if (!is_uploaded_file($tmp_file) || !getimagesize($tmp_file)) {
        header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
        echo "Bad photo upload. Try again.";
        exit;
    }

    $path = \store\upload_photo($tmp_file);

    if (!$path) {
        header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error");
        echo "Something went wrong while saving your photo.";
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

} else if (isset($_POST['photo'])) {
    if (is_array($_POST['photo'])) {
        echo "Warning: this endpoint only supports a single photo per post, other photos will be ignored.";

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
    if (!$content) {
        header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
        echo "Missing 'content' or 'summary' value in post payload.";
        exit;
    }

    $path = \store\upload_text($content);
    $type = empty($title) ? "toot" : "article";

    $post = array(
        "id" => uniqid(),
        "type" => $type,
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
