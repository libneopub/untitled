<?php
// Various utility functions.

$BASE = __DIR__ . "../data/";

function path_from_datetime($ext) {
  global $BASE;
  return $BASE . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($filename, $ext) {
  global $BASE;
  return $BASE . hash_file("md5", $filename) . $ext;
}

function ext($path) {
  return "." . strtolower(pathinfo($path, PATHINFO_EXTENSION));
}

function is_whitespace($c) {
  return in_array($c, array(" ", "\t", "\n", "\r", "\0", "\x0B"));
}

function normalize_url($url) {
  if (substr($url, -1) != "/") {
      return $url .= "/";
  }
}
