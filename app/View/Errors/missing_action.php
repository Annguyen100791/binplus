<h2><?php echo __d('cake_dev', 'Missing Method in %s', $controller); ?></h2>

<div style='padding:20px 0'>
    <div class="notification error png_bg">
        <a href="#" class="close"><?php echo $this->Html->image('icons/cross_grey_small.png', array('title' => 'Ðóng')) ?></a>
        <div>
            <strong>Đã phát sinh lỗi : </strong>
	<?php echo __d('cake_dev', 'Action %1$s chưa được định nghĩa trong controller %2$s', '<em>' . $action . '</em>', '<em>' . $controller . '</em>'); ?>
        </div>
    </div>
	<?php
		if (Configure::read('debug') > 0 ):
			echo $this->element('exception_stack_trace');
		endif;
	?>

</div>



<div>
	<button class='button' type='button' id="btn-goback">Quay lại</button>
</div>

<script>
	$(document).ready(function(){
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(600);
				});
				return false;
			}
		);
		$('#btn-goback').click(function(){
			history.back();
		});
	});
</script>