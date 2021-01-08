<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('promosku/create'); ?>

  <label for="keyword" class="form-label">Keyword</label>
  <input type="text" name="keyword" value="<?= set_value('keyword') ?>" oninput="this.value = this.value.toUpperCase()" class="form-control" maxlength="30" /><br />

  <label for="name" class="form-label">Name</label>
  <input type="text" name="name" value="<?= set_value('name') ?>" class="form-control" maxlength="30" /><br />

  <label for="description" class="form-label">Description</label>
  <textarea name="description" class="form-control"><?= set_value('description') ?></textarea><br />

  <label for="price" class="form-label">Price</label>
  <input type="number" name="price" value="<?php set_value('price') ?>" class="form-control" min="0"/><br />

  <label for="promo" class="form-label">Promo</label>
  <select name="promo" class="form-control">
    <?php 
    foreach ($promos as $promo) {
      echo "<option value='".$promo['keyword']."'>".$promo['keyword']."</option>";
    } 
    ?>
  </select>
  <br />

  <input class="btn btn-primary" type="submit" name="submit" value="Create promo sku" />

</form>
<p><a href="<?php echo site_url('promo/'); ?>">Back to index</a></p>