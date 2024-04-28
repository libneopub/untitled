<?php
// Pretty decent routing based on regexes.
//
// This files exposes four globals:
//
//   $https (bool) indicates whether the current connection is secured.
//   $not_found (bool) can be used to indicate a 404.
//   $path (string) is the request path, after applying normalization.
//   $params (array) contains capture groups from the route regex.
//

$path = $_SERVER['REQUEST_URI'];
$path = explode("?", $path)[0];
$path = "/" . trim($path, "/");

$params = [];
$https = $_SERVER['HTTPS'] === "on";
$not_found = false;

function route($pattern) {
  global $path, $params;
  return preg_match($pattern, $path, $params);
}
