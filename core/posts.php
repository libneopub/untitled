<?php
// Helpers to manage the JSON store.

// NOTE(robin): we're using a year-based system again,
// where each year is a seperate JSON file. However, this
// time filters will work for all years, not just the current
// year. Also, I want the site to work a bit like a
// time machine. Each year has its own CSS and templating,
// and a bar on the top allows you to switch between years.

namespace core;

function new_post($content) {
  $path = path_from_datetime(".md");
  file_put_contents($path, $content);

  return $path;
}

function upload_photo($tmp_file) {
  $path = path_from_hash($tmp_file, ext($tmp_file));

  if (file_exists($path) || move_uploaded_file($tmp_file, $path)) {
    return $path;
  } else {
    return false;
  }
}

function publish_post($post) {
  global $BASE;

  $feed = $BASE . \dates\year($post["published"]) . ".json";
  write_post($feed, $post);
}

function write_post($feed, $post) {
  $posts_json = file_get_contents($feed);
  $posts = json_decode($posts_json);

  array_unshift($posts, $post);
  file_put_contents($feed, json_encode($posts));
}

function send_webmentions($post) {
  $source_url = post_url($post);
  $targets = []; // TODO(robin): get all URLs from post.

  foreach ($targets as $target_url) {
    \webmentions\send_webmention($source_url, $target_url);
  }
}

function send_pingbacks($post) {
  $source_url = post_url($post);
  $targets = []; // TODO(robin): get all URLs from post.

  foreach ($targets as $target_url) {
    \pingbacks\send_pingback($source_url, $target_url);
  }
}
