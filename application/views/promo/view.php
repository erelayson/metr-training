<?php
echo '<h2>'.$promo['keyword'].'</h2>';
echo $promo['name'];
echo $promo['description'];
echo $promo['expiry'];
echo $promo['renewal'];
echo $promo['status'];
echo $promo['activated'];
?>
<p><a href="<?php echo site_url('promo/'); ?>">Back to index</a></p>