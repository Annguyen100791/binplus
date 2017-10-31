<div class="ui-bar ui-bar-e">
	<div style="float:left; width:90%; margin-top:5px"><h3>Thông báo</h3></div>
    <div style="display:inline-block; margin-top:0px; text-align:right; float:right">
    	<a href="#" data-role="button" data-mini="true" data-iconpos="notext" data-icon="delete" title="Đóng" id="message-close"></a></h3>
    </div>
    <div style="clear:both"></div>
    <p><?php echo $message; ?></p>
</div>
<script>
	$(document).ready(function(){
		$('#message-close').click(function(){
			$(this).parent().parent().hide();
		});
	});
</script>