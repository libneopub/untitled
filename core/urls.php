<?php
// Functions for generating URLs

function photo_url($path) {
  global $CANONICAL;
  return $CANONICAL . "/photos/" . basename($path);
}

function post_url($post) {
  global $CANONICAL;

  $year_published = year($post["published"]);
  return $CANONICAL . "/" . $year_published . "/" . $post["id"];
}

function normalize_url($url) {
  if (substr($url, -1) != "/") {
      return $url .= "/";
  }
}
