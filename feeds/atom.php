<?php
// Atom feed (which is RSS but different).

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

// TODO(robin): use proper caching like Last-Modified or ETags.

header("Content-Type: application/atom+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';

?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?= $SITE_TITLE ?></title>
  <subtitle><?= $SITE_DESCRIPTION ?></subtitle>
  <language><?= $SITE_LANG ?></language>
  <id><?= $CANONICAL ?></id>
  <updated><?= \store\last_updated() ?>Z</updated>
  <link rel="self" href="<?= $CANONICAL ?>/atom.xml" type="application/rss+xml" />

  <author>
    <name><?= $AUTHOR_NAME ?></name>
    <uri><?= $CANONICAL ?></uri>
    <?php if(isset($AUTHOR_EMAIL)) echo "<email>$AUTHOR_EMAIL</email>"; ?>
  </author>

  <generator uri="https://dupunkto.org/pubb" version="<?= $PUBB_VERSION ?>">
    Pubb
  </generator>

  <?php
    $posts = \store\list_posts(date("Y"));

    foreach($posts as $post) {
      ?>
        <entry>
          <title><?= $post["title"] ?></title>
          <id><?= $post["id"] ?></id>
          <updated><?= $post["published"] ?>Z</updated>
          <link rel="alternate" href="<?= \urls\post_url($post) ?>"/>
          <content>
            <![CDATA[ <?php \renderer\render_content($post); ?> ]]>
          </content>
        </entry>
      <?php
    }
  ?>
</feed>