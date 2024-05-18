<header>
  <h1><a href="<?= CANONICAL.CMS ?>">Pebble</a></h1>

  <?php if(is_authenticated()) { ?>
    <a href="<?= CANONICAL.CMS . "/settings" ?>">Settings</a>
  <?php } ?>
</header>
