<?php
// Functions for generating URLs.

namespace urls;

function photo_url($path) {
  return CANONICAL . "/uploads/" . $path;
}

function post_url($post) {
  $year_published = \dates\year($post['published']);
  return CANONICAL . "/" . $year_published . "/" . $post['id'];
}

function parse($url) {
  $path = parse_url($url, PHP_URL_PATH);
  $path_pattern = '/^\/(\d{4})\/([a-zA-Z0-9]+)$/';

  if(preg_match($path_pattern, $path, $matches)) {
    return $matches;
  } else {
    return false;
  }
}