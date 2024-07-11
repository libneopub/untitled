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

  if(file_exists($path) or move_uploaded_file($tmp_file, $path)) {
    return $path;
  } else {
    return false;
  }
}

function upload_contents($path) {
  return file_get_contents(STORE . "/uploads/$path");
}

function put_post($volume, $post) {
  $feed = feed_for_volume($volume);
  write_json($feed, $post);
}

function get_post($volume, $id) {
  foreach(list_posts($volume) as $post) {
    if($post['id'] == $id) return $post;
  }
  return false;
}

function get_post_by_slug($volume, $slug) {
  foreach(list_posts($volume) as $post) {
    if($post['slug'] == $slug) return $post;
  }
  return false;
}

function list_posts($volume) {
  $feed = feed_for_volume($volume);
  return read_json($feed);
}

function list_posts_by_type($volume, $type) {
  $posts = list_posts($volume);

  return array_filter($posts, function($post) use($type) {
    return $post['type'] == $type;
  });
}

function last_updated() {
  $posts = list_posts(last_volume());
  $latest_post = $posts[0];

  return $latest_post['published'];
}

// TODO(robin)
function last_volume() { return date("Y"); }
function current_volume() { return date("Y"); }

function put_mention($volume, $id, $source) {
  $feed = feed_for_post($volume, $id);
  write_json($feed, $source);
}

function list_mentions($volume, $id) {
  $feed = feed_for_post($volume, $id);
  return read_json($feed);
}

function list_all_mentions($volume) {
  $posts = list_posts($volume);
  $mentions = [];

  foreach($posts as $post) {
    $for_post = list_mentions($volume, $post['id']);
    $mentions[] = [$post, $for_post];
  }

  return $mentions;
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

function feed_for_volume($volume) {
  return STORE . "/posts/$volume.json";
}

function feed_for_post($volume, $id) {
  return STORE . "/mentions/$volume/$id.json";
}

function feed_for_month($year, $month) {
  return STORE . "/stats/$year/$month.json";
}

function path_from_datetime($ext) {
  return STORE . "/uploads/" . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($filename, $ext) {
  return STORE . "/uploads/" . hash_file("md5", $filename) . $ext;
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
