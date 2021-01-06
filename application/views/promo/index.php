<p><a href="<?php echo site_url('promo/create/'); ?>">Create a promo</a></p>

<?php foreach ($promos as $promo): ?>

  <h3><?php echo $promo['keyword']; ?></h3>
  <div class="main">
    <?php echo $promo['name']; ?>
  </div>
  <p><a href="<?php echo site_url('promo/'.$promo['keyword']); ?>">View promo</a></p>
  <p><a href="<?php echo site_url('promo/edit/'.$promo['keyword']); ?>">Edit promo</a></p>
  <!-- Link trigger modal -->
  <p><a href="" data-toggle="modal" data-target="#<?= $promo['keyword'] ?>Modal">
    Delete promo
  </a></p>

  <!-- Modal -->
  <div class="modal fade" id="<?= $promo['keyword'] . "Modal" ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $promo['keyword'] ?>ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this promo?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a type="button" class="btn btn-primary" href="<?php echo site_url('promo/delete/'.$promo['keyword']); ?>">Delete</a>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>