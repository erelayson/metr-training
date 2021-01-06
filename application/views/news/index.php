<h2><?php echo $title; ?></h2>

<p><a href="<?php echo site_url('news/create/'); ?>">Create article</a></p>

<?php foreach ($news as $news_item): ?>

  <h3><?php echo $news_item['title']; ?></h3>
  <div class="main">
    <?php echo $news_item['text']; ?>
  </div>
  <p><a href="<?php echo site_url('news/'.$news_item['id']); ?>">View article</a></p>
  <p><a href="<?php echo site_url('news/edit/'.$news_item['id']); ?>">Edit article</a></p>
  <!-- Link trigger modal -->
  <p><a href="" data-toggle="modal" data-target="#exampleModal">
    Delete article
  </a></p>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this article?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a type="button" class="btn btn-primary" href="<?php echo site_url('news/delete/'.$news_item['id']); ?>">Delete</a>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>