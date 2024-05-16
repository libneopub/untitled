<?php
// Public facing rendering engine.

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/core.php";
require_once __DIR__ . "/router.php";

$not_found = false;

// Maps URL type -> store type
$page_types = array(
  "toots" => "toot",
  "replies" => "reply",
  "photos" => "photo",
  "code" => "code"
);

switch(true) {
  case !is_https() && FORCE_HTTPS:
    http_response_code(301);
    header("Location: https://" . HOST . $_SERVER['REQUEST_URI']);
    exit;
  
  case $path === "/":
    http_response_code(302);
    header("Location: " . CANONICAL . "/" . date("Y"));
    exit;

  case is_file(__DIR__ . $path) && is_builtin():
    # Serve file as-is. Only applies to the development server,
    # in production this will be handled by Apache directly.
    return false;

  # Serve RSS feeds. Again, only in development.
  case is_builtin() && route('@/rss.xml$@'): include __DIR__ . "/feeds/rss.php"; exit;
  case is_builtin() && route('@/atom.xml$@'): include __DIR__ . "/feeds/atom.php"; exit;
  case is_builtin() && route('@/feed.json$@'): include __DIR__ . "/feeds/json.php"; exit;

  case route('@' . CMS . '.*$@'):
    // Delegate to the pebble router.
    include __DIR__ . "/cms/index.php";
    exit;

  case route('@/(\d{4})$@'):
    $year = $params[1];
    $posts = \store\list_posts($year);
    
    break;

  case route('@/(\d{4})/(toots|replies|photos|code)$@'):
    $year = $params[1];
    $type = $page_types[$params[2]];
    
    $posts = \store\list_posts_by_type($year, $type);

    break;

  case route('@/(\d{4})/(\w+)$@'):
    $year = $params[1];
    $id = $params[2];

    $post = \store\get_post($year, $id);
    if(!$post) $not_found = true;

    break;

  default:
    $year = date("Y");
    $not_found = true;
    break;
}

if($not_found) {
  http_response_code(404);
} else {
  \stats\record_view($path);
}

?>
<!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
  <head>
    <?php include "partials/head.php" ?>
    <title><?= SITE_TITLE ?></title>
  </head>
  <body>
    <header>
      <?php include "partials/header.php" ?>
    </header>
    <main <?php if(isset($posts)) echo 'class="h-feed"' ?>>
      <?php

        switch(true) {
          case isset($post) && $post !== false:
            \renderer\render_post($post);
            \renderer\render_comment_section($post);
            
            break;

          case isset($posts) && count($posts) > 0:
            foreach($posts as $post) {
              \renderer\render_post($post);
            }
            
            break;

          case $not_found:
            include "partials/404.php";
            break;

          default:
            \renderer\render_info("Nothing here. (anymore?)");
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
