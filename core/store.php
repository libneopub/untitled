<?php
// Functions to manage the JSON store.

namespace store;

function upload_text($content) {
  $path = path_from_datetime(".md");
  file_put_contents($path, $content);

  return basename($path);
}

function upload_photo($tmp_file) {
  $path = path_from_hash($tmp_file, ext($tmp_file));

  if (file_exists($path) || move_uploaded_file($tmp_file, $path)) {
    return $path;
  } else {
    return false;
  }
}

function upload_contents($path) {
  global $STORE;
  return file_get_contents("$STORE/uploads/$path");
}

function put_post($year, $post) {
  $feed = feed_for_year($year);
  write_json($feed, $post);
}

function get_post($year, $id) {
  foreach (list_posts($year) as $post) {
    if ($post['id'] === $id) return $post;
  }
  return false;
}

function list_posts($year) {
  $feed = feed_for_year($year);
  return read_json($feed);
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
  write_json($feed, $source);
}

function list_mentions($year, $id) {
  $feed = feed_for_post($year, $id);
  return read_json($feed);
}

function put_view($year, $month, $url) {
  $feed = feed_for_month($year, $month);
  write_json($feed, $url);
}

function list_views($year, $month) {
  $feed = feed_for_month($year, $month);
  return read_json($feed);
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

function feed_for_month($year, $month) {
  global $STORE;
  return $STORE . "/stats/$year/$month.json";
}

function path_from_datetime($ext) {
  global $STORE;
  return $STORE . "/uploads/" . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($filename, $ext) {
  global $STORE;
  return $STORE . "/uploads/" . hash_file("md5", $filename) . $ext;
}

function read_json($path, $default = []) {
  if($json = @file_get_contents($path)) {
    return json_decode($json, true);
  } else {
    return $default;
  }
}

function write_json($path, $new, $default = []) {
  $existing = read_json($path, $default);
  array_unshift($existing, $new);

  if(!is_dir(dirname($path))) {
    mkdir(dirname($path), recursive: true);
  }

  file_put_contents($path, json_encode($existing));
}

function ext($path) {
  return "." . strtolower(pathinfo($path, PATHINFO_EXTENSION));
}
