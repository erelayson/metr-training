<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('promo/edit/'.$promo['keyword']); ?>

  <input type="hidden" name="keyword" value="<?php echo $promo['keyword'] ?>"/>

  <label for="name" class="form-label">Name</label>
  <input type="text" name="name" value="<?php echo $promo['name'] ?>" class="form-control" maxlength="30" /><br />

  <label for="description" class="form-label">Description</label>
  <textarea name="description" class="form-control"><?php echo $promo['description'] ?></textarea><br />

  <label for="expiry" class="form-label">Expiry</label>
  <input type="number" name="expiry" value="<?php echo $promo['expiry'] ?>" class="form-control" />

  <select name="expiry_unit" value="<?php echo $promo['expiry_unit'] ?>" class="form-control">
    <option value="hour">Hour(s)</option>
    <option value="day">Day(s)</option>
    <option value="month">Month(s)</option>
  </select>
  <br />

  <label for="renewal" class="form-label">Renewal</label>
  <input type="number" name="renewal" value="<?php echo $promo['renewal'] ?>" class="form-control" /><br />

  <input class="btn btn-primary" type="submit" name="submit" value="Update promo" />

</form>
<p><a href="<?php echo site_url('promo/'); ?>">Back to index</a></p>