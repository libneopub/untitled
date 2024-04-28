<?php
// The contents of this file will be evaluated
// whenever the Core API loads.

// Initialize data store if it doesn't exist yet
if(!is_dir($BASE)) {
  mkdir($BASE);
}

// Prevent mixed-content warnings
if($FORCE_HTTPS) {
  replace_prefix($CANONICAL, "http://", "https://")
}
