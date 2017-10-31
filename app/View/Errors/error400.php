<h2>Internal Error Exception: 500</h2>

<div style='padding:20px 0'>
    <div class="notification error png_bg">
        <a href="#" class="close"><?php echo $this->Html->image('icons/cross_grey_small.png', array('title' => 'Ðóng')) ?></a>
        <div>
            <strong>Đã phát sinh lỗi : </strong>
            <?php 
			echo "Địa chỉ này không tìm thấy. <BR>  <strong>$url</strong>";
			?>
        </div>
    </div>
	<?php
		if (Configure::read('debug') > 0 ):
			echo $this->element('exception_stack_trace');
		endif;
	?>

</div>



<div>
	<button class='button' type='button' id='btn-goback'>Quay lại</button>
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