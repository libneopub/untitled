<?php
// RSS feed, because I <3 RSS :)

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

include __DIR__ . "/caching.php";

header("Content-Type: application/atom+rss; charset=UTF-8");
echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';

?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?= SITE_TITLE ?></title>
    <description><?= SITE_DESCRIPTION ?></description>
    <language><?= SITE_LANG ?></language>
    <link><?= CANONICAL ?>/</link>
    <lastBuildDate>
      <?php
        $last_updated = \store\last_updated();
        echo date("r", strtotime($last_updated));
      ?>
    </lastBuildDate>
    <generator>Pubb v<?= PUBB_VERSION ?></generator>
    <atom:link rel="self" href="<?= CANONICAL ?>/rss.xml" type="application/rss+xml" />

    <?php if(isset(AUTHOR_NAME) && isset(AUTHOR_EMAIL)) {
      echo "<managingEditor>AUTHOR_EMAIL (AUTHOR_NAME)</managingEditor>";
      echo "<webMaster>AUTHOR_EMAIL (AUTHOR_NAME)</webMaster>";
    } ?>

    <?php
      $posts = \store\list_posts(date("Y"));

      foreach($posts as $post) {
        ?>
          <item>
            <title><?= $post['title'] ?></title>
            <guid><?= $post['id'] ?></guid>
            <pubDate><?= date("r", strtotime($post['published'])) ?></pubDate>
            <link><?= \urls\post_url($post) ?></link>
            <content:encoded>
              <![CDATA[ <?php \renderer\render_content($post); ?> ]]>
            </content:encoded>
          </item>
        <?php
      }
    ?>
  </channel>
</rss>
