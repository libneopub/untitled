<header class="bar">
  <h2>Code snippets</h2>

  <a href="<?= CANONICAL.CMS. "/new" ?>?type=code" class="button">New Snippet</a>
</header>

<?php 
  $year = $_GET['year'] ?? date("Y");
  $posts = \store\list_posts_by_type($year, "code");
?>

<ul class="pages-list">
  <?php foreach($posts as $post) {      
      ?>
        <li>
          <a href="<?= CANONICAL.CMS . "/edit" ?>?id=<?= $post['id'] ?>&year=<?= $year ?>">
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