<?php
// Pebble is the best CMS in the world.
// (Sorry Pluto, there can only be one...)

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

include __DIR__ . "/auth.php";

$path = trim(remove_prefix($path, CMS), "/");
if($path === "") $path = "home";

// This is dangerous. But the user has already been
// authenticated at this point, so technically we can trust them.
// So let's leave it in. I like living on the edge.

$view = __DIR__ . "/views/$path.php";
$action = __DIR__ . "/actions/$path.php";

?><!DOCTYPE html>
<html lang="en">
  <head>
    <?php include __DIR__ . "/partials/head.php" ?>
    <title>Pebble</title>
  </head>
  <body>
    <?php include __DIR__ . "/partials/header.php" ?>
    <main>
      <?php
        switch(true) {
          case file_exists($view):
            include $view;
            break;

          case file_exists($action):
            include $action;
            break;

          default:
            include __DIR__ . "/views/404.php";
            break;
        }
      ?>
    </main>
  </body>
</html>
