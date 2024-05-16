<header class="bar">
  <h2>Toots</h2>

  <a href="<?= CANONICAL.CMS. "/new" ?>?type=toot" class="button">New Toot</a>
</header>

<?php 
  $year = $_GET['year'] ?? date("Y");
  $posts = \store\list_posts_by_type($year, "toot");
?>

<ul class="pages-list">
  <?php foreach($posts as $post) {      
      ?>
        <li>
          <a href="<?= CANONICAL.CMS . "/edit" ?>?id=<?= $post['id'] ?>&year=<?= $year ?>">
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