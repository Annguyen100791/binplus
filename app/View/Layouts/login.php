<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title_for_layout; ?></title>
<?php 
	
	echo $this->Html->css(array(
			'reset',
			'screen',
			'invalid',
			'validate.form',
			'jquery.qtip.min',
			'/js/libs/select2/select2',
			'custom',
			'login'
	));
	
	echo $this->Html->script(
		array(
			  'libs/jquery/jquery.min',
			  'jquery.pngFix.pack.js'
			  )
	);
	echo $scripts_for_layout;
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).pngFix( );
		
		$('#username').focus();
		$(".forgot-pwd").click(function () {
			$("#loginbox").hide();
			$("#forgotbox").show();
			return false;
		});
		
		$(".back-login").click(function () {
			$("#loginbox").show();
			$("#forgotbox").hide();
			$('#username').focus();
			return false;
		});
});
</script>
</head>
<body id="login-bg"> 
 
<!-- Start: login-holder -->
<div id="login-holder">

	<!-- start logo -->
	<div id="logo-login">
		<img alt="" src="/img/login/logobin.png" width="486" height="192" />
	</div>
	<!-- end logo -->
	
	<div class="clear"></div>
	
	<?php echo $content_for_layout; ?>

</div>
<!-- End: login-holder -->
</body>
</html>