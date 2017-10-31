<?php
	if(!empty($ds))
		foreach($ds as $item):
	?>
	<div class="notification success png_bg">
		<a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
		<div>
			<?php  
				echo "<b>" . $item['Nguoixuly']['full_name'] . "</b> đã viết vào lúc " . $this->Time->format("d-m-Y H:i:s", $item['Xulyvanban']['ngay_xuly']) . ":<BR>";
				echo $item['Xulyvanban']['noi_dung'];
			?>
		</div>
	</div>
	<?php
		endforeach;
?>
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
	});
</script>