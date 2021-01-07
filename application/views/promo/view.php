<div class="card">
  <div class="card-header">
    <div align="right">
      <a type="button" class="btn btn-secondary btn-sm" href="<?= site_url('promo/edit/'.$promo['keyword']); ?>">Edit</a>
      <!-- Link trigger modal -->
      <a type="button" class="btn btn-danger btn-sm" href="" data-toggle="modal" data-target="#<?= $promo['keyword'] ?>Modal">
        Delete
      </a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="<?= $promo['keyword'] . "Modal" ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $promo['keyword'] ?>ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content text-dark">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirm delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete <?= $promo['keyword'] ?>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a type="button" class="btn btn-danger" href="<?= site_url('promo/delete/'.$promo['keyword']); ?>">Delete</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-borderless">
      <tr>
        <td>Keyword:</td>
        <td><?= $promo['keyword']; ?></td>
      </tr>
      <tr>
        <td>Name:</td>
        <td><?= $promo['name']; ?></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><?= $promo['description']; ?></td>
      </tr>
      <tr>
        <td>Expiry:</td>
        <td><?= date("F j, Y, g:i a", strtotime($promo['expiry'])); ?></td>
      </tr>
      <tr>
        <td>Status:</td>
        <td>
          <?php 
            if ($promo['status']) {
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
<p><a href="<?php echo site_url('promo/'); ?>">Back to index</a></p>