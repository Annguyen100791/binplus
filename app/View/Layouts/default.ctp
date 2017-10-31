<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
<head>
		<title><?php echo $title_for_layout ?></title>
		<meta http-equiv="content-type" content="text/html; charset=<?php echo Configure::read('App.encoding');?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php
			echo $this->Html->css(
				array(
					'bootstrap.min',
					'jquery-ui',
					'font-awesome',
					'unicorn',
					'unicorn.grey',
					'/js/libs/select2/select2',
					'/js/libs/icheck/flat/blue',
					'/js/libs/notifications/animate.min',
					'custom'
				));
			//
			echo $this->Html->script(array(	
					'libs/jquery.min',
					'libs/jquery-ui.custom',
					'libs/bootstrap.min',
					'libs/jquery.validate.min',
					'libs/jquery.form',
					'libs/jquery.blockUI',
					'/js/libs/select2/select2.min',
					'/js/libs/select2/select2_locale_vi',
					'libs/icheck/jquery.icheck.min',
					'libs/notifications/crm.notification',
					)
						//array('block' => 'scriptBottom')
					);
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
			echo $this->Layout->js_init();
		?>
      <!--[if lt IE 9]>
  <script src="/js/html5shiv.js"></script>
  <script src="/js/respond.min.js"></script>
<![endif]-->
	</head>
<body>
   <div class="ui-layout-north" id="outer-north">
      <div id="logo">
         <div style="float:left"><img src="/img/top_bg.jpg" /></div>
         <div style="clear:both"></div>
      </div>
      <nav class="navbar navbar-inverse" role="navigation">
         <?php echo $this->element('menu'); ?>
      </nav>
   </div>
   
   
   <div class="ui-layout-center" id="content-container">
      <?php echo $content_for_layout ?>
      
   </div>
   <footer class="bs-docs-footer" role="contentinfo">
   		<div class="container">
        	<p>&copy; Bản quyền thuộc Trung tâm Tin học - Viễn thông Đà Nẵng - 2014</p>
        </div>
   </footer>
   <div class="white-backdrop"></div>
</body>
</html>