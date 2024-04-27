<?php
// Core neopub API.
// Mostly wrappers around the other namespaces in neopub core.

namespace core;

function publish_post($post) {
  $year = \dates\year($post['published']);
  return \store\put_post($year, $post);
}

function list_mentions($post) {
  $year = \dates\year($post['published']);
  return \store\list_mentions($year, $post['id']);
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
