<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('servicesku/create'); ?>

  <label for="service_sku" class="form-label">Service SKU</label>
  <select name="service_sku" class="form-control">
    <?php 
    foreach ($serviceskus as $servicesku) {
      echo "<option value='".$servicesku['keyword']."'>".$servicesku['keyword']."</option>";
    } 
    ?>
  </select>
  <br />

  <label for="promo_sku" class="form-label">Promo SKU</label>
  <select name="promo_sku" class="form-control">
    <?php 
    foreach ($promoskus as $promosku) {
      echo "<option value='".$promosku['keyword']."'>".$promosku['keyword']."</option>";
    } 
    ?>
  </select>
  <br />

  <input class="btn btn-primary" type="submit" name="submit" value="Create service sku" />

</form>
<p><a href="<?php echo site_url('servicesku/'); ?>">Back to index</a></p>