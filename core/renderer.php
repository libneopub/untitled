<?php
// Renders posts to HTML.

namespace renderer;

function render_post($post) {
  ?>
    <article class="h-entry">
      <?php if($post["title"]) {
        echo '<h1 class="p-name">' . $post["title"] . '</h1>';
      } ?>

      <section class="p-summary e-content">
        <?php render_content($post) ?>
      </section>
    </article>
  <?php
}

function render_content() {
  global $BASE;

  // Render a photo.
  if($post["type"] == "photo") {
    $url = $post["url"] ?? \urls\photo_url($post["path"]);
    ?>
      <figure>
        <img src="<?= $url ?>" alt="<?= $caption ?>">
        <figcaption><?= $caption ?></figcaption>
      </figure> 
    <?php
  } 

  // Render a code snippet
  else if($post["type"] == "code") {
    $code = file_get_contents($post["path"]);
    echo '<pre><code>' . $code . '</code></pre>';
  } 

  // Render other content.
  else {
    $parser = new Parsedown();
    $content = file_get_contents($post["path"]);

    echo $parser->text($content);
  }
}
