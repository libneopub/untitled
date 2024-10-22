<form action="" method="post">
  <h3>Site details</h3>

  <p>
    <label for="site.title">Site title</label>
    <input 
      type="text" 
      name="site.title" 
      placeholder="@dreamwastaken"
      value="<?= canonical_value("site.title") ?>"
      required
    >
  </p>

  <p>
    <label for="site.description">Biography</label>
    <input 
      type="text" 
      name="site.description"
      placeholder="Verified (€15/year for the domain)"
      value="<?= canonical_value("site.description") ?>"
      required
    >
  </p>

  <p>
    <label for="site.lang">Language</label>

    <select name="site.lang">
      <?php foreach(LANGUAGE_CODES as $code => $language) { ?>
        <option 
          value="<?= $code ?>" 
          <?php if($code == SITE_LANG) echo "selected" ?>
        >
          <?= $language ?>
        </option>
      <?php } ?>
    </select>
  </p>

  <h3>Personal information</h3>

  <p>
    <label for="author.name">Name</label>
    <input 
      type="text" 
      name="author.name"
      placeholder="Your name"
      value="<?= canonical_value("author.name") ?>"
    >
  </p>

  <p>
    <label for="author.email">Email address</label>
    <span>The public email address that will be printed on your profile, in feeds and in your contact details.</span>

    <input 
      type="email" 
      name="author.email"
      placeholder="you@example.com"
      value="<?= canonical_value("author.email") ?>"
    >
  </p>

  <p>
    <label for="author.main-site">Primary site</label>
    <span>The website that will be printed on your profile, in feeds and in your contact details. (leave empty to use this site)</span>

    <input 
      type="text" 
      name="author.main-site"
      placeholder="https://example.com"
      value="<?= canonical_value("author.main_site") ?>"
    >
  </p>

  <h3>Notifications</h3>

  <p>
    <label for="notifications.admin">Receiving address</label>
    <span>
      The email address to which notifiations will be sent.
      (leave empty to use your public email address)
    </span>

    <input 
      type="email" 
      name="notifications.admin"
      placeholder="you@example.com"
      value="<?= canonical_value("notifications.admin") ?>"
    >
  </p>

  <p>
    <label for="notifications.sender">Sending address</label>
    <span>The email address from which email notifications should be sent.</span>

    <input 
      type="email" 
      name="notifications.sender"
      placeholder="noreply@example.com"
      value="<?= canonical_value("notifications.sender") ?>"
    >
  </p>

  <p>
    <label>
      <input 
        type="checkbox"
        name="notifications.webmention"
        <?php if(NOTIFICATIONS_WEBMENTION) echo "checked" ?>
      >
      <span>Send me an email when someone comments on one of my toots.</span>
    </label>
  </p>

  <h3>Security</h3>

  <p>
    <label for="passphrase">Passphrase</label>
    <span>If you want to change the passphrase, please enter a new one and then confirm it by typing it again. (leave empty to keep current passphrase)</span>

    <input 
      type="password" 
      name="passphrase"
      placeholder="Enter a passphrase..."
    >

    <input 
      type="password" 
      name="confirm"
      placeholder="Confirm passphrase..."
    >
  </p>

  <input type="submit" name="save" value="Save">
</form>
