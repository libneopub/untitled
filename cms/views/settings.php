<style>
  input[type="text"], 
  input[type="email"],
  select {
    width: 100%;
    padding: 0.2em 0.4em;
    font-size: 1em;
    margin-top: 0.4em;
    margin-bottom: 0.2em;
  }

  h3 {
    border-bottom: 1px solid #ccc;
    margin-top: 2em;
  }

  label:not(:has(input)) {
    font-weight: bold;
  }

  p label + span {
    display: block;
    font-size: 0.9em;
    color: #777;
  }

  input[type="submit"] {
    float: right;
    margin-bottom: 3em;
  }
</style>

<form action="<?= CANONICAL.CMS . "/save-settings" ?>" method="post">
  <h3>Site details</h3>

  <p>
    <label for="site.title">Site title</label>
    <input 
      type="text" 
      name="site.title" 
      placeholder="@dreamwastaken"
      required
    >
  </p>

  <p>
    <label for="site.description">Biography</label>
    <input 
      type="text" 
      name="site.description"
      placeholder="Verified (€15/year for the domain)"
      required
    >
  </p>

  <p>
    <label for="site.lang">Language</label>

    <select name="site.lang">
      <?php foreach(LANGUAGE_CODES as $code => $language) { ?>
        <option 
          value="<?= $code ?>" 
          <?php if($code === SITE_LANG) echo "selected" ?>
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
    >
  </p>

  <p>
    <label for="author.email">Email address</label>
    <span>The public email address that will be printed on your profile, in feeds and in your contact details.</span>

    <input 
      type="email" 
      name="author.name"
      placeholder="you@example.com"
    >
  </p>

  <p>
    <label for="author.main-site">Primary site</label>
    <span>The website that will be printed on your profile, in feeds and in your contact details. (leave empty to use this site)</span>

    <input 
      type="text" 
      name="author.main-site"
      placeholder="https://example.com"
    >
  </p>

  <h3>Notifications</h3>

  <p>
    <label for="notifications.sender">Sending address</label>
    <span>The email address from which email notifications should be sent.</span>

    <input 
      type="email" 
      name="notifications.sender"
      placeholder="noreply@example.com"
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

  <input type="submit" value="Save">
</form>