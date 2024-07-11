<header class="bar">
  <h2>Toots</h2>

  <a href="<?= CANONICAL.CMS. "/new" ?>?type=toot" class="button">New Toot</a>
</header>

<?php 
  $volume = $_GET['volume'] ?? \store\current_volume();
  $posts = \store\list_posts_by_type($volume, "toot");
?>

<ul class="pages-list">
  <?php foreach($posts as $post) {      
    $query = http_build_query(["id" => $post['id'], "volume" => $volume]);
    ?>
      <li>
        <a href="<?= CANONICAL.CMS . "/edit" ?>?<?= $query ?>">
          <?= $post['title'] ?? $post['published'] ?>
        </a>
      </li>
    <?php            
  } ?>
</ul>

<?php if(count($posts) < 1) {
  ?>
    <p class="placeholder-text">No toots yet.</p>
  <?php
} ?>
