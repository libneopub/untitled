<?php
// Various utility functions.

function is_whitespace($c) {
  return in_array($c, array(" ", "\t", "\n", "\r", "\0", "\x0B"));
}

function normalize_url(&$url) {
  $url = replace_prefix($url, "http://", "https://");
  $url = strtolower($url);
  
  if (!str_ends_with($str, "/")) {
    return $url .= "/";
  } else {
    return $url;
  }
}

function replace_prefix($str, $old, $new) {
  if(str_starts_with($str, $old)) {
    return $new . substr($str, strlen($old));
  } else {
    return $str;
  }
}

function strip_comments($body) {
  return preg_replace('/<!--(.*)-->/Us', "", $body);
}

function flatten($separator, $array) {
  $keys = array_keys($array);
  $values = array_values($array);
  
  return array_map(function($key, $value) use ($separator) {
    return $key . $seperator . $value;
  }, $keys, $values);
}
