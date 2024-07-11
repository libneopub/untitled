<header class="bar">
  <h2>Code snippets</h2>

  <a href="<?= CANONICAL.CMS. "/new" ?>?type=code" class="button">New Snippet</a>
</header>

<?php 
  $volume = $_GET['volume'] ?? \store\current_volume();
  $posts = \store\list_posts_by_type($volume, "code");
?>

<ul class="pages-list">
  <?php foreach($posts as $post) {     
    $query = http_build_query(["id" => $post['id'], "volume" => $volume]); 
    ?>
      <li>
        <a href="<?= CANONICAL.CMS . "/edit" ?>?<?= $query ?>">
          // TODO(robin): determine code snippet store format.
        </a>
      </li>
    <?php
  } ?>
</ul>

<?php if(count($posts) < 1) {
  ?>
    <p class="placeholder-text">No code snippets yet.</p>
  <?php
} ?>