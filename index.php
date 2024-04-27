<?php
// Public facing rendering engine.

// Enable error reporting for development
// TODO(robin): turn this off before deploying!!
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/core.php";
require_once __DIR__ . "/router.php";

// Maps URL type -> store type
$page_types = array(
  "toots" => "toot",
  "replies" => "reply",
  "photos" => "photo",
  "code" => "code"
);

switch(true) {
  case $path === "/":
    header($_SERVER["SERVER_PROTOCOL"] . " 302 Found");
    header("Location: $CANONICAL/" . date("Y"));
    exit;

  case route('|/(\d{4})|'):
    $year = $params[1];
    break;

  case route('|/(\d{4})/(toots|replies|photos|code)|'):
    $year = $params[1];
    $type = $page_types[$params[2]];
    
    $posts = \store\list_posts_by_type($year, $type);
    
    break;

  case route('|/(\d{4})/(\w+)|'):
    $year = $params[1];
    $id = $params[2];

    $post = \store\get_post($year, $id);
    if(!$post) $not_found = true;
    
    break;

  case true:
    $not_found = true;
    break;
}

if($not_found) {
  header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
}

?>
<!DOCTYPE html>
<html lang="<?= $SITE_LANG ?>">
  <head>
    <?php include "partials/head.php" ?>
    <title><?= $SITE_TITLE; ?></title>
  </head>
  <body>
    <header>
      <?php include "partials/header.php" ?>
    </header>
    <main <?php if(isset($posts)) echo 'class="h-feed"' ?>>
      <?php

        switch(true) {
          case isset($post):
            \renderer\render_post($post);
            \renderer\render_comment_section($post);
            
            break;

          case isset($posts):
            foreach($posts as $post) {
              \renderer\render_post($post);
            }
            
            break;

          case $not_found:
            include "partials/404.php";
            break;
        }
        
      ?>
    </main>
    <aside>
      <?php include "partials/menu.php" ?>
    </aside>
    <footer>
      <?php include "partials/footer.php" ?>
    </footer>
  </body>
</html>
