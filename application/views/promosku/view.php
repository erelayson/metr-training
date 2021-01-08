<div class="card">
  <div class="card-header">
    <div align="right">
      <!-- Link trigger modal -->
      <a type="button" class="btn btn-danger btn-sm" href="" data-toggle="modal" data-target="#<?= $promosku['keyword'] ?>Modal">
        Delete
      </a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="<?= $promosku['keyword'] . "Modal" ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $promosku['keyword'] ?>ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content text-dark">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirm delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete <?= $promosku['keyword'] ?>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a type="button" class="btn btn-danger" href="<?= site_url('promosku/delete/'.$promosku['keyword']); ?>">Delete</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-borderless">
      <tr>
        <td>Keyword:</td>
        <td><?= $promosku['keyword']; ?></td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><?= $promosku['name']; ?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><?= $promosku['description']; ?></td>
      </tr>
      <tr>
        <td>Price:</td>
        <td><?= $promosku['price'] ?></td>
      </tr>
      <tr>
        <td>Promo:</td>
        <td><?= $promosku['promo'] ?></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td>
          <?php 
            if ($promosku['status']) {
              echo "Active";
            } else {
              echo "Inactive";
            }
          ?>
        </td>
      </tr>
    </table>
  </div>
</div>
<p><a href="<?php echo site_url('promosku/'); ?>">Back to index</a></p>