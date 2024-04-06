<?php
// Public facing rendering engine.

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/core.php";

/*
  Routes (via Rewrite Magic™):

  / -> /:year     redirects to current year
  /:year          listing of all posts        $_GET['year']
  /:year/:type    listing of a type           $_GET['year'] + $_GET['type']
  /:year/:id      permalink to a post         $_GET['year'] + $_GET['id']

  Post types:

  - toots
  - replies
  - photos
  - code

  Other URLs (served from Apache):

  /raw/:id       permalinks to raw files in `data`
*/

if(!isset($_GET["year"])) {
  header($_SERVER["SERVER_PROTOCOL"] . " 302 Found");
  header("Location: $CANONICAL/" . date("Y"));
  exit;
}
else {
  $year = $_GET["year"];
}

// Maps URL type -> store type
$page_types = array(
  "toots" => "toot",
  "replies" => "reply",
  "photos" => "photo",
  "code" => "code"
);

if(isset($_GET["id"])) {
  $post = \store\get_post($year, $_GET["id"]);
} 
else if(isset($_GET["type"])) {
  $type = $page_types[$_GET["type"]];
  $posts = \store\list_posts_by_type($year, $type);
} 
else {
  $posts = \store\list_posts($year);
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
        
        // Render a single post.
        if(isset($post)) {
          \renderer\render_post($post);
          // TODO(robin): render comment section.
        } 
        
        // Render listing.
        else {
          foreach($posts as $post) {
            \renderer\render_post($post);
          }
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
