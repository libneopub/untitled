<?php
// Various utility functions.

function is_whitespace($c) {
  return in_array($c, array(" ", "\t", "\n", "\r", "\0", "\x0B"));
}

function normalize_url($url) {
  if (substr($url, -1) != "/") {
      return $url .= "/";
  }
}

function strip_comments($body) {
  return preg_replace('/<!--(.*)-->/Us', '', $body);
}
