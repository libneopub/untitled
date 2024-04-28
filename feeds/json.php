<?php
// JSON feed, because. it. is. so. much. simpler.
// (Who tf thought XML was a good idea?!?)

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

include __DIR__ . "/caching.php";

header("Content-Type: application/feed+json; charset=UTF-8");

$posts = \store\list_posts(date("Y"));
$entries = [];

foreach($posts as $post) {
  $entries[] = array(
    "id" => $post['id'],
    "url" => \urls\post_url($post),
    "title" => $post['title'],
    "content_html" => \renderer\render_content($post),
    "date_published" => $post['published'] . "Z"
  );
}

echo json_encode(array(
  "version" => "https://jsonfeed.org/version/1.1",
  "title" => SITE_TITLE,
  "description" => SITE_DESCRIPTION,
  "language" => SITE_LANG,
  "author" => AUTHOR_NAME,
  "home_page_url" => CANONICAL . "/",
  "feed_url" => CANONICAL . "/feed.json",
  "items" => $entries
));
