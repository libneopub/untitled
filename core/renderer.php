<?php
// Renders posts to HTML.

namespace renderer;

function render_info($message) {
  render_message("info", $message);
}

function render_warn($message) {
  render_message("warn", $message);
}

function render_error($message) {
  render_message("error", $message);
}

function render_message($kind, $message) {
  ?>
    <p class="<?= $kind ?>"><?= $message ?></p>
  <?php
}

function render_post($post) {
  ?>
    <article class="h-entry">
      <?php if($post['title']) {
        echo '<h2 class="p-name">' . $post['title'] . '</h2>';
      } ?>

      <div class="p-summary e-content">
        <?php render_content($post) ?>
      </div>

      <time class="dt-published" datetime="<?= $post['published'] ?>">
        <a class="u-url" href="<?= \urls\post_url($post) ?>">
          <?= date("Y-m-d", strtotime($post['published'])) ?>
        </a>
      </time>

      <?php if(defined(AUTHOR_NAME) && defined(AUTHOR_PICTURE)) { ?>
        <div class="p-author h-card">
          <a class="u-url" href="<?= CANONICAL ?>">
            <img
              class="u-photo"
              src="<?= AUTHOR_PICTURE ?>"
              alt="<?= AUTHOR_NAME ?>"
              width="100"
            >
            <p class="p-name"><?= AUTHOR_NAME ?></p>
          </a>
        </div>
      <?php } ?>
    </article>
  <?php
}

// TODO(robin): Actually render comments, instead of just links.
function render_comment_section($post) {
  ?>
    <aside>
      <h2>Webmentions</h2>

      <ul>
        <?php foreach(\core\list_mentions($post) as $url) { ?>
          <li><a href="<?= $url ?>"><?= parse_url($url, PHP_URL_HOST) ?></a></li>
        <?php } ?>
      </ul>

      <form action="<?= WEBMENTION_ENDPOINT ?>" method="post">
        <p>
          This post accepts <a href="//indieweb.org/Webmention">Webmentions</a>. 
          Have you written a reply? Let me know the URL:
        </p>

        <input name="target" type="hidden" value="<?= \urls\post_url($post) ?>">
        <input name="source" type="url" placeholder="https://example.com/your/reply">

        <input type="submit" value="Send webmention">
    </aside>
  <?php
}

function render_content($post) {
  switch($post['type']) {
    case "photo":
      render_photo($post);
      break;

    case "code": 
      render_code($post);
      break;

    default:
      render_text($post);
      break;
  }
}

function render_photo($post) {
  $parser = new Parsedown();

  $url = $post['url'] ?? \urls\photo_url($post['path']);
  $caption = $parser->text($post['caption']);

  ?>
    <figure>
      <img 
        class="u-photo" 
        src="<?= $url ?>" 
        alt="<?= strip_tags($caption) ?>"
      />
      <figcaption><?= $caption ?></figcaption>
    </figure> 
  <?php
}

function render_code($post) {
  $code = \store\upload_contents($post['path']);
  echo '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
}

function render_text($post) {
  $parser = new Parsedown();
  $content = \store\upload_contents($post['path']);

  echo $parser->text($content);
}
