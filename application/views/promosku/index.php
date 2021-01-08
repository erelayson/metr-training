<?php foreach ($promoskus as $promosku): ?>

  <div class="card">
    <div class="card-header" align="right">
      <?php
        if (!$promosku['status']) {
          echo "<a type='button' class='btn btn-danger btn-sm' href='' data-toggle='modal' data-target='#".$promosku['keyword']."Modal'>Delete</a>";
        }
      ?>
    </div>
    <div class="card-body">
      <h5 class="card-title"><a href="<?= site_url('promosku/'.$promosku['keyword']); ?>"><?= $promosku['name']; ?></a></h5>
      <p class="card-text"><?= $promosku['description']; ?></p>
      <p class="card-text">
        <?php 
          if ($promosku['status']) {
            echo "Active";
          } else {
            echo "Inactive";
          }
        ?> 
      </p>
    </div>
  </div>
  <br />

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
          Are you sure you want to delete <?= $promosku['name'] ?>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a type="button" class="btn btn-danger" href="<?= site_url('promosku/delete/'.$promosku['keyword']); ?>">Delete</a>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>