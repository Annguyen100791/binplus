<div class="content-box" id="congviec-box"><!-- Start Content Box -->
    
    <div class="content-box-header">
					
        <h3><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc đã giao</h3>
        
        <ul class="content-box-tabs">
            <li><a href="#congviec-all" class="default-tab" rel="congviec-all"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Tất cả</a></li>
            <li><a href="#congviec-unfinished" rel="congviec-unfinished"><?php echo $this->Html->image('icons/note_error.png', array('align' => 'left')) ?>&nbsp; Chưa hoàn thành</a></li>
            <li><a href="#congviec-finished" rel="congviec-finished"><?php echo $this->Html->image('icons/note_go.png', array('align' => 'left')) ?>&nbsp; Đã hoàn thành</a></li>
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="congviec-all">
        	<img src="/img/circle_ball.gif" />
        </div>
    	<div class="tab-content" id="congviec-unfinished">
        	<img src="/img/circle_ball.gif" />
    	</div>
    	<div class="tab-content" id="congviec-finished">
        	<img src="/img/circle_ball.gif" />
    	</div>
	</div>

</div>
<script>
	$(document).ready(function(){
							   
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				
				var currentTab = $(this).attr('rel'); // Set variable "currentTab" to the value of href of clicked tab
				switch(currentTab)
				{
					case 'congviec-all':
						updateContent(root + 'congviec/dagiao_ajax/all', null, 'congviec-all');
						break;
					case 'congviec-unfinished':
						updateContent(root + 'congviec/dagiao_ajax/unfisnished', null, 'congviec-unfisnished');
						break;
					case 'congviec-finished':
						updateContent(root + 'congviec/dagiao_ajax/finished', null, 'congviec-finished');
						break;
				}
				
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				
				$('#' + currentTab).siblings().hide(); // Hide all content divs
				
				$('#' + currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				
				return false; 
			}
		);							
		
		updateContent(root + 'congviec/dagiao_ajax/all', null, 'congviec-all');
	});
</script>