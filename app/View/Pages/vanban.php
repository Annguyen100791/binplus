<?php
	if(empty($vanban)):
?>
	<div class="notification success png_bg">
        <a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
        <div>
            Hiện tại chưa có văn bản mới để xem.
        </div>
    </div>
<?php	
	else:
?>
	<div class="notification attention png_bg">
        <a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
        <div>
            Bạn đang có <?php echo $this->Html->link($vanban . ' văn bản', '/vanban/', array('title' => 'click để xem chi tiết')) ?>  chưa xem.
        </div>
    </div>
<?php	
	endif;
?>