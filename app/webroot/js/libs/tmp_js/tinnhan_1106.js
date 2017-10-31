var browserField;
function randomString(length) {
	return Math.floor(Math.random()*100000000);
}	
function	remove_file(obj)
{
	$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'tinnhan/remove_attach',
				cache:		false,
				async:		false,
				dataType:	'json',
				data:		{key: obj.id},
				success:	function(result)
				{
					if(result.success)
					{
						$('#row' + obj.rel).remove();
					}else
						alert(result.message);
				},
				error:		function(result)
				{
					alert(result.message);
				}
			});	
}
var Tinnhan = {
	index:	function(){
		$('#tinnhan-box div.tab-content').hide(); // Hide the content divs
		$('#tinnhan-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);							
		
		$(window).hashchange( function(){
			Tinnhan.updateTabContent();
		})
		
		$(window).hashchange();
		
		$('.tu_ngay').datepicker({dateFormat: "dd-mm-yy"});
		$('.den_ngay').datepicker({dateFormat: "dd-mm-yy"});
		
		$('.users').select2();
		
		$('#form-unread-search').submit(function(){
			$('#unread-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tinnhan/unread',
					data:		$('#form-unread-search').serialize(),
					success:	function(result)
					{
						$('#unread-list-content').unblock();
						$('#unread-list-content').html(result);
					},
					error:		function(result)
					{
						alert('Error');
					}
				});	
			return false;
		});
		
		$('#form-read-search').submit(function(){
			$('#read-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tinnhan/read',
					data:		$('#form-read-search').serialize(),
					success:	function(result)
					{
						$('#read-list-content').unblock();
						$('#read-list-content').html(result);
					},
					error:		function(result)
					{
						alert('Error');
					}
				});	
			return false;
		});
		$('#form-sent-search').submit(function(){
			$('#sent-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tinnhan/sent',
					cache:		false,
					async:		false,
					data:		$('#form-sent-search').serialize(),
					success:	function(result)
					{
						$('#sent-list-content').unblock();
						$('#sent-list-content').html(result);
					},
					error:		function(result)
					{
						alert('Error');
					}
				});	
			return false;
		});
	},
	
	updateTabContent:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#message-unread';
		switch(currentTab)
		{
			case '#message-unread':
				BIN.doUpdate('<a href="tinnhan/unread" data-target="unread-list-content"></a>');
				break;
			case '#message-read':
				BIN.doUpdate('<a href="tinnhan/read" data-target="read-list-content"></a>');
				break;
			case '#message-sent':
				BIN.doUpdate('<a href="tinnhan/sent" data-target="sent-list-content"></a>');
				break;
			default:
				return false;
				break;
		}
		
		$(currentTab + '-tab').parent().siblings().find("a").removeClass('current');
		$(currentTab + '-tab').addClass('current'); 
		
		$(currentTab).siblings().hide();
		
		$(currentTab).show();
	},
	
	compose:	function(){
		tinymce.init({
			selector: '#noi_dung',
			height: 250,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			menu: BIN.editorMenu,
			toolbar_items_size: 'small',
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			image_advtab: true,
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser
		});
		
		
		$('#btn-tinnhan-add').click(function(){
			$('#form-tinnhan-compose').submit()
			return false;
		});
		var row = 1;
		// init ajax upload
		
		new AjaxUpload($('#btn-attachfile'), {
			action: BIN.baseURL + 'tinnhan/attachfile',
			name: 'data[FileManager][file]',
			onSubmit: function(file, ext){
				 if (! (ext && /^(tiff|tif|jpg|png|jpeg|gif|swf|flv|avi|wmv|mp3|zip|rar|doc|docx|xls|xlsx|ppt|pptx|pdf)$/.test(ext))){ 
					// extension is not allowed 
					alert('Chỉ được phép upload các file có phần mở rộng là TIFF, TIF, JPG, JPEG, PNG, GIF, SWF, FLV, AVI, WMV, MP3, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF.');
					return false;
				}
				$('#upload_status').html('<img src="/img/circle_ball.gif">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				$('#upload_status').text('');
				//Add uploaded file to list
				
				var jsonObj = eval('(' + response + ')');
				
				if(jsonObj.success)
				{
					//row = randomString();
					row = 'file' + randomString();
					$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + BIN.hidetimelabel(jsonObj.filename) + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.filename + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove file"></a></div><div style="float:right; padding-right:20px">' + jsonObj.filesize  + '</div><div style="clear:both"></div><input type="hidden" name=data[File][' + row + '] value="' + jsonObj.filename + '"></div>');
				} else{
					alert(jsonObj.message);
				}
			}
		});
		
		// file tin nhắn
		if($('#attach_files').val() != '')
		{
			var files = $.parseJSON($('#attach_files').val());
			$.each(files, function(index, item){
				$('#file_list').append('<div class="uploaded_item" id="row' + item.id + '"><div style="float:left">' + BIN.hidetimelabel(item.file_path) + '</div><div style="float:right"><a href="javascript:void(0)" id="' + item.file_path + '" onClick="remove_file(this)" rel="' + item.id + '"><img src="/img/closelabel.png" title="Remove file"></a></div><div style="clear:both"></div><input type="hidden" name=data[File][' + item.id + '] value="' + item.file_path + '"></div>');
			});
		}
		
		$('.btn-submit').click(function(){
			$('#form-tinnhan-compose').submit();
		});
		
		// validate
		$('#form-tinnhan-compose').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Tinnhan][tieu_de]':	'required'
			},
			messages:{
				'data[Tinnhan][tieu_de]'	:	'Vui lòng nhập vào Tiêu đề tin nhắn'
			},
			submitHandler: function(form){
				if($('#nv_selected').val() == '')
					alert('Vui lòng chọn người nhận');
				else
				{
					form.submit();
					//return false;
				}
			}
		});	
		//update
		BIN.doUpdate('<a href="nhanvien/nhanviennhan/TinNhan.soanthao" data-target="tinnhan_nhantinnhan"></a>');		
		
		//$('#tinnhan_nhantinnhan').css("height", $('#tinnhan_leftcontent').height() - 5);
		$('#tieu_de').focus();
	},
	
	view:	function(){
		$('#btn-tinnhan-delete').click(function(){
			if(confirm('Bạn có muốn xóa Tin nhắn này không?'))
			{
				var id = $(this).attr('data-id');
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tinnhan/read_delete',
					cache:		false,
					async:		false,
					data:	{v_id: id},
					success:	function(result)
					{
						alert('Đã xóa thành công.');
						location = '/tinnhan/index#message-read'
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
				return false;
			}
			return false;
		});
	},
	
	view_sent:	function(){
		$('#btn-tinnhan-delete').click(function(){
			if(confirm('Bạn có muốn xóa Tin nhắn này không?'))
			{
				var id = $(this).attr('data-id');
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tinnhan/sent_delete',
					data:	{v_id: id},
					success:	function(result)
					{
						alert('Đã xóa thành công.');
						location = '/tinnhan/index#message-sent'
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
				return false;
			}
			return false;
		});
	}
};


$(document).ready(function(){
	BIN.executeFunctionByName(Tinnhan, params.action, null);
});