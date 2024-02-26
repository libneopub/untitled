<?php
// Helpers to manage the JSON store.

$BASE = __DIR__ . "../data/";

function path_from_datetime($ext) {
  global $BASE;
  return $BASE . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($filename, $ext) {
  global $BASE;
  return $BASE . hash_file("md5", $filename) . $ext;
}

function new_post($content) {
  $path = path_from_datetime(".md");
  file_put_contents($path, $content);

  return $path;
}

function upload_photo($tmp_file) {
  $path = path_from_hash($tmp_file, ext($tmp_file));

  if(file_exists($path) || move_uploaded_file($tmp_file, $path)) {
    return $path;
  } else {
    return false;
  }
}

function ext($path) {
  return "." . strtolower(pathinfo($path, PATHINFO_EXTENSION));
}

// NOTE(robin): we're using a year-based system again,
// where each year is a seperate JSON file. However, this
// time filters will work for all years, not just the current
// year. Also, I want the site to work a bit like a
// time machine. Each year has its own CSS and templating,
// and a bar on the top allows you to switch between years.

function publish_post($post) {
  global $BASE;

  $feed = $BASE . year($post["published"]) . ".json";
  write_post($feed, $post);
}

function write_post($feed, $post)  {
  $posts_json = file_get_contents($feed);
  $posts = json_decode($posts_json);

  array_unshift($posts, $post);
  file_put_contents($feed, json_encode($posts));
}
