<h2><?php echo $title; ?></h2>

<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('news/create'); ?>

  <label for="title">Title</label>
  <input type="text" name="title" value="<?= set_value('title') ?>" /><br />

  <label for="text">Text</label>
  <textarea name="text"><?= set_value('text') ?></textarea><br />

  <input type="submit" name="submit" value="Create news item" />

</form>