<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
<title><?php echo $title_for_layout ?></title>
<?php 
	echo $this->Html->css(array(
			'jquery.mobile',
			'jqm-icon-pack',
			'mobile'
	));
	
	echo $this->Html->script(
		array(
			  'jquery',
			  'mobile',
			  'jquery.mobile'
			  )
	);
	echo $scripts_for_layout;
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head> 
<body> 
<?php
	echo $content_for_layout;
?>
</body>
</html>
