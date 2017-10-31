<div class="content-box"><!-- Start Content Box -->
    <div class="content-box-header">
        <div style="float:left"><h3><img src="/img/icons/world.png" align="left"/> &nbsp;&nbsp;Thông báo về website</h3></div>
        <div style="float:right; padding:12px 15px 10px"><a href="#" class="btn-refresh" rel="notice" title="Refresh"><img src="/img/icons/arrow_refresh.png"/></a></div>
    </div>
    <div class="content-box-content" id="thongbao-box-content">
        <img src="/img/circle_ball.gif" />
    </div>
</div>

<div class="content-box column-left">
    <div class="content-box-header">
        <div style="float:left"><h3><img src="/img/icons/page_white_text.png" align="left"/> &nbsp;&nbsp;Thông báo về văn bản</h3></div>
        <div style="float:right; padding:12px 15px 10px"><a href="#" class="btn-refresh" rel="vanban" title="Kiểm tra văn bản"><img src="/img/icons/arrow_refresh.png"/></a></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="vanban-box-content">
        	<img src="/img/circle_ball.gif" />
        </div> <!-- End #tab3 -->        
    </div> <!-- End .content-box-content -->
    
    <div class="content-box-header">
        <div style="float:left"><h3><img src="/img/icons/page_white_text.png" align="left"/> &nbsp;&nbsp;Thông báo về tin nhắn</h3></div>
        <div style="float:right; padding:12px 15px 10px"><a href="#" class="btn-refresh" rel="tinnhan" title="Kiểm tra tin nhắn"><img src="/img/icons/arrow_refresh.png"/></a></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="tinnhan-box-content">
        	<img src="/img/circle_ball.gif" />
        </div> <!-- End #tab3 -->        
    </div>
    
</div> <!-- End .content-box -->

<div class="content-box column-right">
	<div class="content-box-header">
        <div style="float:left"><h3><img src="/img/icons/note.png" align="left"/> &nbsp;&nbsp;Thông báo về công việc</h3></div>
        <div style="float:right; padding:12px 15px 10px"><a href="#" class="btn-refresh" rel="tinnhan" title="Kiểm tra công việc"><img src="/img/icons/arrow_refresh.png"/></a></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="congviec-box-content">
        <img src="/img/circle_ball.gif" />
        </div> <!-- End #tab3 -->        
    </div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->

<div class="clear"></div>


<script>
	$(document).ready(function(){
		mnu_selected = 'mnu-dashboard';
		/*
		$('a[title]').qtip({
						   position:	{at:	'top center', 'my': 'bottom center'}
						   });
		*/
		updateContent(root + 'pages/thongtin', null, 'thongbao-box-content');
		updateContent(root + 'pages/vanban', null, 'vanban-box-content');
		updateContent(root + 'pages/congviec', null, 'congviec-box-content');
		updateContent(root + 'pages/tinnhan', null, 'tinnhan-box-content');
		
		$('.btn-refresh').click(function(){
			switch($(this).attr('rel'))
			{
				case	'notice':
					updateContent(root + 'pages/thongtin', null, 'thongbao-box-content');
					break;
				case 	'vanban':
					updateContent(root + 'pages/vanban', null, 'vanban-box-content');
					break;
				case	'tinnhan':
					updateContent(root + 'pages/tinnhan', null, 'tinnhan-box-content');
					break;
				case	'congviec':
					updateContent(root + 'pages/congviec', null, 'congviec-box-content');
					break;
			}
		});
		
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