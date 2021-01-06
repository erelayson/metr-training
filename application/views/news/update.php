<h2><?php echo $title; ?></h2>

<?php echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>

<?php echo form_open('news/edit/'.$news_item['id']); ?>
	
  <input type="hidden" name="id" value="<?php echo $news_item['id'] ?>"/>

  <label for="title">Title</label>
  <input type="text" name="title" value="<?php echo $news_item['title'] ?>"/><br />

  <label for="text">Text</label>
  <textarea name="text"><?php echo $news_item['text'] ?></textarea><br />

  <input type="submit" name="submit" value="Update news item" />

</form>
<p><a href="<?php echo site_url('news/'); ?>">Back to index</a></p>