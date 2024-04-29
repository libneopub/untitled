<?php
// The contents of this file will be evaluated
// whenever the Core API loads.

// Enable error reporting for development
// TODO(robin): turn this off before deploying!!
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

if(!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 80000) {
  die("The minimum required PHP version is 8.0. Please upgrade your PHP installation to continue.");
}

// Initialize data store if it doesn't exist yet
if(!is_dir(STORE)) {
  mkdir(STORE);
  mkdir(STORE . "/posts");
  mkdir(STORE . "/mentions");
  mkdir(STORE . "/styles");
  mkdir(STORE . "/uploads");
  mkdir(STORE . "/stats");

  @symlink(STORE . "/styles", __DIR__ . "/styles");
  @symlink(STORE . "/uploads", __DIR__ . "/uploads");
}

// Error for mismatches between CANONICAL and FORCE_HTTPS
if(FORCE_HTTPS && str_starts_with(CANONICAL, "http://")) {
  die("Error: you've set FORCE_HTTPS to true, but the CANONICAL still contains http:// (instead of https://). This can potentially cause mixed content warnings, and messes with the canonical URL of your site!");
}
