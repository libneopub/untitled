<form method="post">
  <header>
    <div class="bar">
      <input required placeholder="Title" name="title" value="<?= $data[0]->title ?>">

      <p class="button-group">
        <?php if(isset($id)) { ?>
          <input name="id" value="<?= $id ?>" type="hidden">
          <a href="<?= CANONICAL.CMS . "/delete" ?>?id=<?= $id ?>" class="button">Delete</a>
        <?php } ?>

        <input name="save" type="submit" value="Save">
        <input name="publish" type="submit" value="Publish">
      </p>
    </div>
  </header>

  <textarea 
    autofocus 
    required 
    placeholder="Write anything. Write everything." 
    name="content"><?= $data[1] ?></textarea>
</form>