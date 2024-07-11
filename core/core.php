<?php
// Core Pubb API.
// Mostly wrappers around the other namespaces in neopub core.

// TODO(robin):
// add 'volume' field to posts
// add 'slug' field to posts
// some sort of "current volume"?

namespace core;

function get_post($url) {
  if($params = \urls\parse($url)) {
    [$volume, $id] = $params;
    return \store\get_post($volume, $id);
  } else {
    return false;
  }
}

function publish_post($post) {
  $volume = \store\current_volume();
  return \store\put_post($volume, $post);
}

function list_mentions($post) {
  return \store\list_mentions($post['volume'], $post['id']);
}

function send_webmentions($post) {
  $source_url = post_url($post);
  $targets = []; // TODO(robin): get all URLs from post.

  foreach($targets as $target_url) {
    \webmentions\send_webmention($source_url, $target_url);
  }
}

function send_pingbacks($post) {
  $source_url = post_url($post);
  $targets = []; // TODO(robin): get all URLs from post.

  foreach($targets as $target_url) {
    \pingbacks\send_pingback($source_url, $target_url);
  }
}
