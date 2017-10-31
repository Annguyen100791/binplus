
<style>
#file-list .number.current {
    background: url("/img/shared/bg-button-green.gif") repeat-x scroll left top #469400 !important;
    border-color: #459300 !important;
    color: #FFFFFF !important;
}
#file-list .number {
    border: 1px solid #DDDDDD;
    margin: 0 5px 0 0;
    padding: 5px 6px;
}
#file-list .number{
    border-radius: 4px 4px 4px 4px;
}
</style>
<?php
	$files = count($this->data['Filevanban']);
	if( $files > 0):
?>
	<div id="file-list">
	<div class="content-box-header" style="padding-left:5px">
					
        <ul class="content-box-tabs" style="float:left!important;">
        <?php
			for($i = 0; $i < $files; $i++)
				printf('<li><a href="#file-vanban-%s" rel="#file-vanban-%s" class="number">File %s</a></li>', $i, $i, $i+1);
		?>
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
    	<?php
			$i = 0;
			foreach($this->data['Filevanban'] as $item):
			$ext = end(explode('.', $item['path']));
			$file = 'http://' . env("HTTP_HOST") . Configure::read('VanBan.attach_path') . $item['path'];
		?>
    	<div class="tab-content" id="file-vanban-<?php echo $i ?>">
            <iframe style="border-style: none;" class="viewer_container" id="viewer_container-<?php echo $i ?>" src="http://online.verypdf.com/app/reader/?url=<?php echo $file ?>"></iframe>
            </div>
            <div align="right" style="padding:5px">
                <a target="_blank" href="<?php echo Helper::webroot(Configure::read('VanBan.attach_path') . $item['path']) ?>" class="button"><?php echo $this->Html->image('icons/disk.png', array('align' => 'left')) ?>&nbsp; Download file</a>&nbsp;
                <?php if($this->Layout->check_permission('VanBan.theodoi')): ?>
                <a href="#" class="button btn-theodoi" rel="<?php echo $this->data['Vanban']['id'] ?>"><?php echo $this->Html->image('icons/page_white_star.png', array('align' => 'left')) ?>&nbsp; Theo dõi văn bản này</a>
                <?php endif;?>
                
                <?php if($this->Layout->check_permission('CongViec.khoitao')): ?>
                <a href="/congviec/add/<?php echo $this->data['Vanban']['id'] ?>" class="button" ><?php echo $this->Html->image('icons/congviec_add.png', array('align' => 'left')) ?>&nbsp; Giao việc theo văn bản</a>
                <?php endif;?>
                
            </div>
			<script>
			$(document).ready(function(){
				var w = $('#vanban-box').width() - 20;
				var h = Math.floor(w*11.2/8);
				var h_viewport = $(window).height() - 60;
				
				$('#viewer_container-<?php echo $i?>').attr('width', w);
				$('#viewer_container-<?php echo $i?>').attr('height', h);
			});
		</script>
        </div>
        <?php
			$i++;
			endforeach;
		?>
    </div>
    
    </div>
<?php

	endif;
?>
<script>
	$(document).ready(function(){
		$('.btn-theodoi').click(function(){
			$.ajax({
					type:		'POST',
					url:		root + 'vanban/danhdau_theodoi',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:		{vanban_id: $(this).attr('rel')},
					success:	function(result)
					{
						if(result.success)
						{
							alert('Văn bản đã được theo dõi.');
						}else
							alert(result.message);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
			return false;
		});
		
		$('.content-box-tabs li a', '#file-list').first().addClass('default-tab');
		$('#file-vanban-0').addClass('default-tab');
		
		$('#file-list div.tab-content').hide(); // Hide the content divs
		$('#file-list div.default-tab').show(); // Show the div with class "default-tab"
		$('.content-box-tabs li a', '#file-list').first().addClass('current'); // Set the class of the default tab link to "current"
		
		
		
		$('#file-list ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);
	});
</script>