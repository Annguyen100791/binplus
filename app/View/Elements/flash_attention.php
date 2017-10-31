<div class="notification attention png_bg">
    <a href="#" class="close"><?php echo $this->Html->image('icons/cross_grey_small.png', array('title' => 'Ðóng')) ?></a>
    <div>
        <?php echo $message ?> 
    </div>
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
	});
</script>