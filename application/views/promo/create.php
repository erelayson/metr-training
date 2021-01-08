<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('promo/create'); ?>

  <label for="keyword" class="form-label">Keyword</label>
  <input type="text" name="keyword" value="<?= set_value('keyword') ?>" oninput="this.value = this.value.toUpperCase()" class="form-control" maxlength="30" /><br />

  <label for="name" class="form-label">Name</label>
  <input type="text" name="name" value="<?= set_value('name') ?>" class="form-control" maxlength="30"/><br />

  <label for="description" class="form-label">Description</label>
  <textarea name="description" class="form-control"><?= set_value('description') ?></textarea><br />

  <label for="expiry" class="form-label">Expiry</label>
  <input type="number" name="expiry" value="<?php set_value('expiry') ?>" class="form-control" min="1"/>

  <select name="expiry_unit" value="<?php set_value('expiry_unit') ?>" class="form-control">
    <option value="hour">Hour(s)</option>
    <option value="day">Day(s)</option>
    <option value="month">Month(s)</option>
  </select>
  <br />

  <label for="renewal" class="form-label">Renewal</label>
  <input type="number" name="renewal" value="<?= set_value('renewal') ?>" class="form-control" min="0" /><br />

  <input class="btn btn-primary" type="submit" name="submit" value="Create promo" />

</form>
<p><a href="<?php echo site_url('promo/'); ?>">Back to index</a></p>

  <script type="text/javascript">
    document.getElementById("createPromo").classList.add("disabled");
  </script>