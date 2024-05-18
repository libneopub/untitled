<?php
// Static configuration used throughout the Pubb API.

define('CMS', '/cms');

define('ISSUER', CANONICAL . "/");
define('CLIENT_ID', ISSUER);
define('REDIRECT_URI', CANONICAL.CMS . "/auth");

define('SUPPORTED_SCOPES', [
  "create", 
  "update", 
  "delete", 
  "media"
]);
