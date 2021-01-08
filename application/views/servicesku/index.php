<?php foreach ($serviceskus as $servicesku): ?>

  <div class="card">
    <div class="card-header" align="right">
      <!-- Link trigger modal -->
      <a type="button" class="btn btn-danger btn-sm" href="" data-toggle="modal" data-target="#<?= $servicesku['service_sku'] . $servicesku['promo_sku'] ?>Modal">
        Delete
      </a>
    </div>
    <div class="card-body">
      <h5 class="card-title"><?= $servicesku['service_sku']; ?></h5>
      <p class="card-text"><?= $servicesku['promo_sku']; ?></p>
    </div>
  </div>
  <br />

  <!-- Modal -->
  <div class="modal fade" id="<?= $servicesku['service_sku'] . $servicesku['promo_sku'] . "Modal" ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $servicesku['service_sku'] . $servicesku['promo_sku'] ?>ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content text-dark">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <?= $servicesku['service_sku'] . "(" . $servicesku['promo_sku'] . ")" ?>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <?php echo form_open('servicesku/delete'); ?>
          <input type="hidden" name="service_sku" value="<?=$servicesku['service_sku']?>" />
          <input type="hidden" name="promo_sku" value="<?=$servicesku['promo_sku']?>" />
          <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>