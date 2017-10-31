<?php
	if($success == 1)
	{
		if($status == 0)
			echo $this->Html->image('icons/cross_circle.png', array('title' => 'Click để cho phép hiển thị', 'rel' => $id, 'class' => 'cat-toggle'));
		else
			echo $this->Html->image('icons/tick_circle.png', array('title' => 'Click để không hiển thị trên trang chủ', 'rel' => $id, 'class' => 'cat-toggle'));
	}else
		echo 'error';
?>