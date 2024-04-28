<?php
// The contents of this file will be evaluated
// whenever the Core API loads.

// Enable error reporting for development
// TODO(robin): turn this off before deploying!!
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

// Initialize data store if it doesn't exist yet
if(!is_dir($STORE)) {
  mkdir($STORE);
  mkdir("$STORE/posts");
  mkdir("$STORE/mentions");
  mkdir("$STORE/styles");
  mkdir("$STORE/uploads");
  mkdir("$STORE/stats");

  @symlink("$STORE/styles", __DIR__ . "/styles");
  @symlink("$STORE/uploads", __DIR__ . "/uploads");
}

// Prevent mixed-content warnings
if($FORCE_HTTPS) {
  replace_prefix($CANONICAL, "http://", "https://");
}
