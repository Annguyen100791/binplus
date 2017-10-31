﻿<style>
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
			$id_vanban = $this->data['Vanban']['id_vanban'];
			foreach($this->data['Filevanban'] as $item):
			$id = $item['id'];
			//pr($id);die();
		?>
    	<div class="tab-content" id="file-vanban-<?php echo $i."/".$id_vanban ?>">
        	<div align="right" style="padding:5px">
                <a target="_blank" href="/vanban/get_files/<?php echo $id ?>" class="button"><?php echo $this->Html->image('icons/disk.png', array('align' => 'left')) ?>&nbsp; Download file</a>&nbsp;
            </div>
        	<div style="width:100%; min-width:600px" id="viewer_container-<?php echo $i ?>">
            </div>
            
			<script>
			$(document).ready(function(){
				var file = "/vanban/get_files/<?php echo $id."/".$id_vanban ?>";
				var w = $('#vanban-box').width() - 20;
				var h = Math.floor(w*11.2/8);
				var h_viewport = $(window).height() - 60;
					
				if(detecttiffviewer())
				{
					
					/*$('#viewer_container-<?php echo $i ?>').attr('width', w);
					$('#viewer_container-<?php echo $i ?>').attr('height', h);
					*/
					$('#viewer_container-<?php echo $i ?>').html('<object width="100%" height="' + h_viewport + 'px" classid="CLSID:106E49CF-797A-11D2-81A2-00E02C015623" codebase="/soft/alternatiff.cab#version=2,0,2,1"><param name="mousemode" value="pan"><param name="src" value="' + file + '"><param name="negative" value="no"><embed width="100%" height="' + h_viewport + 'px" src="' + file + '" type="image/tiff" negative=no mousemode="pan">');
				}else
				{
					var ua = window.navigator.userAgent;
					var msie = ua.indexOf('MSIE ');
					var trident = ua.indexOf('Trident/');
				
					if (msie > 0 || trident > 0) {
						if (navigator.userAgent.indexOf("WOW64") != -1 || 
							navigator.userAgent.indexOf("Win64") != -1 ){
						  	 $('#viewer_container-0').html('<div style="font-weight; color: read">Vui lòng click vào <a href="http://www.alternatiff.com/install-ie64/">link</a> sau để cài đặt chương trình xem TIFF.</div>');
						} else {
						   $('#viewer_container-0').html('<div style="font-weight; color: read">Vui lòng click vào <a href="http://www.alternatiff.com/install-ie/">link</a> sau để cài đặt chương trình xem TIFF.</div>');
						}
					}
				
					// other browser
					$('#viewer_container-0').html('<div style="font-weight; color: read">Vui lòng click vào <a href="http://www.alternatiff.com/distribution/alternatiff-pl-w32-2.0.6-chrome.exe">link</a> sau để cài đặt chương trình xem TIFF.</div>');
				}
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
	
	function detecttiffviewer(){
		if (navigator.plugins != null && navigator.plugins.length > 0){
			return navigator.plugins["AlternaTIFF (QuickTime compatible)"] && true;
		}
		if(~navigator.userAgent.toLowerCase().indexOf("webtv")){
			return true;
		}
		
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf('MSIE ');
		var trident = ua.indexOf('Trident/');
		if (msie > 0 || trident > 0) {
			try{
//				alert(new ActiveXObject("Alttiff.AlttiffCtl"));
				return new ActiveXObject("Alttiff.AlttiffCtl") && true;
			} catch(e){
				alert('Lỗi');
			}
		}
		return false;
	}
</script>