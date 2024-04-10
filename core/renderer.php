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

function render_content($post) {
  global $BASE;

  // Render a photo.
  if($post["type"] == "photo") {
    $parser = new Parsedown();

    $url = $post["url"] ?? \urls\photo_url($post["path"]);
    $caption = $parser->text($post["caption"]);

    ?>
      <figure>
        <img 
          class="u-photo" 
          src="<?= $url ?>" 
          alt="<?= strip_tags($post["caption"]) ?>"
        />
        <figcaption><?= $caption ?></figcaption>
      </figure> 
    <?php
  } 

  // Render a code snippet
  else if($post["type"] == "code") {
    $code = file_get_contents($post["path"]);
    echo '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
  } 

  // Render other content.
  else {
    $parser = new Parsedown();
    $content = file_get_contents($post["path"]);

    echo $parser->text($content);
  }
}
