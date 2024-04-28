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
