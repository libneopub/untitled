<?php
// Pebble is the best CMS in the world.
// (Sorry Pluto, there can only be one...)

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../router.php";

// TODO(robin): (Indie)Auth!!

$path = trim(remove_prefix($path, CMS), "/");
if($path === "") $path = "home";

$view = __DIR__ . "/views/$path.php";

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
        if(file_exists($view)) {
          include $view;
        } else {
          http_response_code(404);
          include __DIR__ . "/views/404.php";
        }
      ?>
    </main>
  </body>
</html>
