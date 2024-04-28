<?php
// Functions to manage the JSON store.

namespace store;

function upload_text($content) {
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

function put_post($year, $post) {
  $feed = feed_for_year($year);
  $posts = list_posts($year);
  
  array_unshift($posts, $post);
  file_put_contents($feed, json_encode($posts));
}

function get_post($year, $id) {
  foreach (list_posts($year) as $post) {
    if ($post['id'] === $id) return $post;
  }
  
  return false;
}

function list_posts($year) {
  $feed = feed_for_year($year);
  return read_json_file($feed);
}

function list_posts_by_type($year, $type) {
  $posts = list_posts($year);

  return array_filter($posts, function($post) use($type) {
    return $post['type'] === $type;
  });
}

function last_updated() {
  $current_year = date("Y");
  $posts = list_posts($current_year);
  $latest_post = $posts[0];

  return $latest_post['published'];
}

function put_mention($year, $id, $source) {
  $feed = feed_for_post($year, $id);
  $mentions = list_mentions($year, $id);

  array_unshift($mentions, $source);
  file_put_contents($feed, json_encode($mentions));
}

function list_mentions($year, $id) {
  $feed = feed_for_post($year, $id);
  return read_json_file($feed);
}

// Helpers

function feed_for_year($year) {
  global $STORE;
  return $STORE . "/posts/$year.json";
}

function feed_for_post($year, $id) {
  global $STORE;
  return $STORE . "/mentions/$year/$id.json";
}

function path_from_datetime($ext) {
  global $STORE;
  return $STORE . "/uploads/" . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($filename, $ext) {
  global $STORE;
  return $STORE . "/uploads/" . hash_file("md5", $filename) . $ext;
}

function read_json_file($path, $default = []) {
  if(file_exists($path) && ($json = file_get_contents($path))) {
    return json_decode($json);
  } else {
    return $default;
  }
}

function ext($path) {
  return "." . strtolower(pathinfo($path, PATHINFO_EXTENSION));
}
