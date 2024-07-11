<header class="bar">
  <h2>Comments</h2>
</header>

<?php 
  $volume = $_GET['volume'] ?? \store\current_volume();
  $mentions = \store\list_all_mentions($volume);
?>

<ul class="pages-list">
  <?php foreach($mentions as [$post, $for_post]) {
      ?>
      <h3>
        Comments on 
        <a href="<?= \urls\post_url($post) ?>">
          <cite>
            <?= $post['title'] ?? $post['published'] ?>
          </cite>
        </a>
      </h3>

      <?php foreach($for_post as $url) { ?>
        <li>
          <a href="<?= $url ?>"><?= $url ?></a>
          <span class="actions">
            <a href="<?= CANONICAL.CMS . "/new" ?>?type=reply&to=<?= urlencode($url) ?>">
              Reply
            </a>
          </span>
        </li>
      <?php  }
  } ?>
</ul>

<?php if(count($mentions) < 1) {
  ?>
    <p class="placeholder-text">No comments yet.</p>
  <?php
} ?>