<?php
// Custom routing based on regexes

$path = $_SERVER['REQUEST_URI'];
$path = explode("?", $path)[0];
$path = "/" . trim($path, "/");

$params = [];
$not_found = false;

function route($pattern) {
  global $path, $params;
  return preg_match($pattern, $path, $params);
}
