<?php
echo '<h2>'.$news_item['title'].'</h2>';
echo $news_item['text'];
?>
<p><a href="<?php echo site_url('news/'); ?>">Back to index</a></p>