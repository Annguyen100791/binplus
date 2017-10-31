<?php
	
	if(!empty($chuahoanthanh)):
?>
	<div class="notification error png_bg">
        <a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
        <div>
            Bạn đang có <?php echo $this->Html->link($chuahoanthanh . ' công việc', '/congviec/duocgiao', array('title' => 'click để xem chi tiết')) ?>  trễ tiến độ.
        </div>
    </div>
<?php	
	endif;
?>
<?php
	if(!empty($dangthuchien)):
?>
	<div class="notification success png_bg">
        <a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
        <div>
            Bạn đang có <?php echo $this->Html->link($dangthuchien . ' công việc', '/congviec/duocgiao/', array('title' => 'click để xem chi tiết')) ?>  đang thực hiện.
        </div>
    </div>
<?php	
	endif;
?>
