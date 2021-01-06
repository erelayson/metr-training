<h2><?php echo $title; ?></h2>

<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('promo/create'); ?>

  <label for="keyword">Keyword</label>
  <input type="text" name="keyword" value="<?= set_value('keyword') ?>" oninput="this.value = this.value.toUpperCase()" /><br />

  <label for="name">Name</label>
  <input type="text" name="name" value="<?= set_value('name') ?>" /><br />

  <label for="description">Description</label>
  <textarea name="description"><?= set_value('description') ?></textarea><br />

  <label for="expiry">Expiry</label>
  <input type="datetime-local" name="expiry" value="<?= set_value('expiry') ?>" /><br />

  <label for="renewal">Renewal</label>
  <input type="text" name="renewal" value="<?= set_value('renewal') ?>" /><br />

  <input type="submit" name="submit" value="Create promo" />

</form>