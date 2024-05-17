<header>
  <h1><a href="<?= CANONICAL.CMS ?>">Pebble</a></h1>

  <?php if(isset($_SESSION['access_token'])) { ?>
    <a href="<?= CANONICAL.CMS . "/settings" ?>">Settings</a>
  <?php } ?>
</header>
