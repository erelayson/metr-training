<?php foreach ($promos as $promo): ?>

  <div class="card">
    <div class="card-header" align="right">
      <a type="button" class="btn btn-secondary btn-sm" href="<?= site_url('promo/toggle/'.$promo['keyword']); ?>">Toggle status</a>
      <a type="button" class="btn btn-secondary btn-sm" href="<?= site_url('promo/edit/'.$promo['keyword']); ?>">Edit</a>
      <!-- Link trigger modal -->
      <?php
        if (!$promo['activated']) {
          echo "<a type='button' class='btn btn-danger btn-sm' href='' data-toggle='modal' data-target='#".$promo['keyword']."Modal'>Delete</a>";
        }
      ?>
    </div>
    <div class="card-body">
      <h5 class="card-title"><a href="<?= site_url('promo/'.$promo['keyword']); ?>"><?= $promo['name']; ?></a></h5>
      <p class="card-text"><?= $promo['description']; ?></p>
      <p class="card-text">
        <?php 
          if ($promo['status']) {
            echo "Active";
          } else {
            echo "Inactive";
          }
        ?></p>
    </div>
  </div>
  <br />

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
          Are you sure you want to delete <?= $promo['name'] ?>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a type="button" class="btn btn-danger" href="<?= site_url('promo/delete/'.$promo['keyword']); ?>">Delete</a>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>