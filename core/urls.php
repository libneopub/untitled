<?php
// Functions for generating URLs.

namespace urls;

function photo_url($path) {
  global $CANONICAL;
  return $CANONICAL . "/uploads/" . basename($path);
}

function post_url($post) {
  global $CANONICAL;

  $year_published = \dates\year($post['published']);
  return $CANONICAL . "/" . $year_published . "/" . $post['id'];
}
