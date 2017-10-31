// JavaScript Document
var browserField;
var f_FileUploading = false;

function	editNguoinhanviec(id, nguoinhan_id, title, start, end, description)
{
	if(end == '')
		end = start; // ???
	var params = {id:	id, 
				  nguoinhan_id: nguoinhan_id,	
				  title: title,
				  start: start,
				  end:	end,
				  description: description};
	var layer = 1;
	var dialog_id = 'dialog-id-layer-' + layer;
		
	var options = BIN.dlgOptions;
	options.width = "500";
	
	$.ajax({
		type:	'POST',
		url:	BIN.baseURL + 'congviec/edit_nguoinhanviec/' + id,
		dataType: 'html',
		data:	params,
		success:	function(data){
			$(document.body).append('<div id="' + dialog_id + '" class="dialog"></div>');
			
			options.close = function(event, ui){
				$('#' + dialog_id).dialog('destroy').remove();
			};
			options.title = 'Hiệu chỉnh người nhận việc';
			options.position = 'center';
			
			
			$('#' + dialog_id).dialog(options);
			$('#' + dialog_id).html(data);
			
			
			$('#' + dialog_id).parent().css("z-index", 2001 + layer);
			$('#' + dialog_id).dialog('open');
			$('#' + dialog_id).parent().parent().prev().css("z-index", 2000 + layer);
			
		}
	});
}

function	unselect()
{
	$('#congviec-vanban').html('');
	$('#chonvanban-container').show();
}

function	remove_file(obj)
{
	$.ajax({
			type:		'POST',
			url:		BIN.baseURL + 'congviec/remove_attach_baocao',
			cache:		false,
			async:		false,
			dataType:	'json',
			data:		{key: obj.id},
			success:	function(result)
			{
				if(result.success)
				{
					$('#row' + obj.rel).fadeTo(400, 0, function () { // Links with the class "close" will close parent
						$('#row' + obj.rel).slideUp(400);
						$('#row' + obj.rel).remove();
					});
				}else
					alert(result.message);
			},
			error:		function(result)
			{
				alert(result.message);
			}
		});	
}

var Congviec = {
	add:	function(){
		
		$('#ngay_batdau').datepicker({
			dateFormat: "dd-mm-yy",
			onSelect:	function(){
				var newDate = $(this).datepicker('getDate');
			    if (newDate) { // Not null
					$('#ngay_ketthuc').datepicker('setDate', newDate).datepicker('option', 'minDate', newDate);
			    }
			}
		});
		
		$('#ngay_ketthuc').datepicker({
			dateFormat: "dd-mm-yy",
			minDate: $('#ngay_batdau').datepicker('getDate')
		});
		
		$("#nguoinhan_id").select2();
		
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				
				var currentTab = $(this).attr('rel'); 
				if(currentTab == 'congviec-nhan')
				{
					if($('#nguoinhan_id').val() == '')
					{
						alert('Vui lòng chọn người chịu trách nhiệm chính.');
						$('#nv_chiutrachnhiem').focus();
						return false;
					}	
				}
				$(this).parent().siblings().find("a").removeClass('current'); 
				$(this).addClass('current'); 
				
				$('#' + currentTab).siblings().hide();
				$('#' + currentTab).show(); 
				
				if(currentTab == 'congviec-nhan')
				{
					var opts = {
						container: '#calendar', 
						events: BIN.baseURL + 'congviec/ds_nguoinhanviec',
						eventClick: function(calEvent, jsEvent, view) {
							editNguoinhanviec(calEvent.id, calEvent.nguoinhan_id, calEvent.title, $.fullCalendar.formatDate(calEvent.start, 'yyyy-MM-dd'), $.fullCalendar.formatDate(calEvent.end, 'yyyy-MM-dd'), calEvent.description);
							
						}
						};
					BIN.init_calendar(opts);
				}
				
				return false; 
			}
		);	
		
		
		$('.btn-congviec-add').click(function(){
			$('#form-congviec-add').submit();
		});
		
		// tinymce
		
		tinymce.init({
			selector: '#noi_dung',
			height: 400,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser,
			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '12px';
				});
			}
		});
		
		$('#form-congviec-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Congviec][ten_congviec]'	:	'required',
				'data[Congviec][ngay_batdau]'	:	'required',
				'data[Congviec][ngay_ketthuc]'	:	'required',
				'data[Congviec][nguoinhan_id]':	'required',
				'data[Congviec][loaicongviec_id]':	'required'
			},
			messages:{
				'data[Congviec][ten_congviec]'	:	'Vui lòng nhập vào Tên công việc',
				'data[Congviec][ngay_batdau]'	:	'Vui lòng nhập vào Ngày bắt đầu',
				'data[Congviec][ngay_ketthuc]'	:	'Vui lòng nhập vào Ngày kết thúc',
				'data[Congviec][nguoinhan_id]':	'Vui lòng chọn Nhân viên chịu trách nhiệm chính',
				'data[Congviec][loaicongviec_id]':	'Vui lòng chọn Phân loại công việc'
			}
		});	
		
		$('#ten_congviec').focus();
	},
	/////////
	/*update_progress:	function(){

		alert('BAC');
		$('#btn-update-progress').click(function(){

			$('#form-chitietcongviec').submit()

			return false;

		});

		var row = 1;

		// init ajax upload
		new AjaxUpload($('#btn-attachfile'), {
				action: BIN.baseURL + 'congviec/attach_file',
				name: 'data[Congviec][file]',
				onSubmit: function(file, ext){
					if (! (ext && /^(tiff|tif|jpg|png|jpeg|gif|swf|flv|avi|wmv|mp3|zip|rar|doc|docx|xls|xlsx|ppt|pptx|pdf|odt|ott|odm|html|oth|ods|ots|odg|otg|odp|otp|odf|odb|oxt)$/.test(ext))){ 
					alert('Chỉ được phép upload các file có phần mở rộng là TIFF, TIF, JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT.');
						return false;
					}
					$('#upload_status').html('<img src="/img/circle_ball.gif">');
					f_FileUploading = true;
				},
				onComplete: function(file, response){
					//On completion clear the status
					$('#upload_status').text('');
					//Add uploaded file to list
					var jsonObj = eval('(' + response + ')');
					//alert(jsonObj);
					if(jsonObj.success)
					{
						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filecongviec]['+row+'][path] class="uploaded" value="' + jsonObj.path + '"><input type="hidden" name=data[Filecongviec]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filecongviec]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');
						row++;
					} else{

						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');
						row++;
					}
					f_FileUploading = false;
				}
			});

	},*/
	
	/////////
	edit:	function(){
		
		createCookie("nn_ngay_bat_dau",$('#ngay_batdau').val(),1);
		createCookie("nn_ngay_ket_thuc",$("#ngay_ketthuc").val(),1);	
		
		$('#ngay_batdau').datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(){
				createCookie("nn_ngay_bat_dau",$('#ngay_batdau').val(),1);
			}
			});
		$('#ngay_ketthuc').datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(){
				createCookie("nn_ngay_ket_thuc",$("#ngay_ketthuc").val(),1);	
			}
			
			});
		
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				
				var currentTab = $(this).attr('rel'); 
				if(currentTab == 'congviec-nhan')
				{
					if($('#nguoinhan_id').val() == '')
					{
						alert('Vui lòng chọn người chịu trách nhiệm chính.');
						$('#nv_chiutrachnhiem').focus();
						return false;
					}	
				}
				$(this).parent().siblings().find("a").removeClass('current'); 
				$(this).addClass('current'); 
				
				$('#' + currentTab).siblings().hide();
				$('#' + currentTab).show(); 
				
				if(currentTab == 'congviec-nhan')
				{
					var opts = {
						container: '#calendar', 
						events: BIN.baseURL + 'congviec/ds_nguoinhanviec/' + $('#congviec_id').val(),
						eventClick: function(calEvent, jsEvent, view) {
							editNguoinhanviec(calEvent.id, calEvent.nguoinhan_id, calEvent.title, $.fullCalendar.formatDate(calEvent.start, 'yyyy-MM-dd'), $.fullCalendar.formatDate(calEvent.end, 'yyyy-MM-dd'), calEvent.description);
							
						}
						};
					BIN.init_calendar(opts);
				}
				
				return false; 
			}
		);	
		
		tinymce.init({
			selector: '#noi_dung',
			height: 400,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser,
			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '12px';
				});
			}
		});
		
		$("#nguoinhan_id").select2();
		
		$('.btn-congviec-edit').click(function(){
			$('#form-congviec-edit').submit();
		});
		
		
		$('#form-congviec-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Congviec][ten_congviec]'	:	'required',
				'data[Congviec][ngay_batdau]'	:	'required',
				'data[Congviec][ngay_ketthuc]'	:	'required',
				'data[Congviec][nguoinhan_id]':	'required'
			},
			messages:{
				'data[Congviec][ten_congviec]'	:	'Vui lòng nhập vào Tên công việc',
				'data[Congviec][ngay_batdau]'	:	'Vui lòng nhập vào Ngày bắt đầu',
				'data[Congviec][ngay_ketthuc]'	:	'Vui lòng nhập vào Ngày kết thúc',
				'data[Congviec][nguoinhan_id]':	'Vui lòng chọn Nhân viên chịu trách nhiệm chính'
			}
		});	
	},
	
	dagiao:	function(){
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				window.location.hash = '#' + $(this).attr('rel');
				
				return false; 
			}
		);		
		
		$(window).hashchange( function(){
			Congviec.dagiao_updateTab();
		})
		
		$(window).hashchange();
	},
	
	dagiao_updateTab:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#congviec-all';
		switch(currentTab)
		{
			case '#congviec-all':
				BIN.doUpdate('<a href="congviec/dagiao/opt:all" data-target="congviec-all"></a>');
				break;
			case '#congviec-instant':
				BIN.doUpdate('<a href="congviec/dagiao/opt:instant" data-target="congviec-instant"></a>');
				break;
			case '#congviec-baocao':
				BIN.doUpdate('<a href="congviec/dagiao/opt:baocao" data-target="congviec-baocao"></a>');
				break;
			case '#congviec-progressing':
				BIN.doUpdate('<a href="congviec/dagiao/opt:progressing" data-target="congviec-progressing"></a>');
				break;
			case '#congviec-unfinished':
				BIN.doUpdate('<a href="congviec/dagiao/opt:unfinished" data-target="congviec-unfinished"></a>');
				break;
			case '#congviec-finished':
				BIN.doUpdate('<a href="congviec/dagiao/opt:finished" data-target="congviec-finished"></a>');
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
	
	duocgiao:	function(){
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);							
		
		$(window).hashchange( function(){
			Congviec.duocgiao_updateTab();
		})
		
		$(window).hashchange();
	},
	duocgiao_updateTab:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#congviec-all';
		switch(currentTab)
		{
			case '#congviec-all':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:all" data-target="congviec-all"></a>');
				break;
			case '#congviec-instant':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:instant" data-target="congviec-instant"></a>');	
				break;
			case '#congviec-baocao':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:baocao" data-target="congviec-baocao"></a>');
				break;		
			case '#congviec-progressing':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:progressing" data-target="congviec-progressing"></a>');
				break;
			case '#congviec-unfinished':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:unfinished" data-target="congviec-unfinished"></a>');
				break;
			case '#congviec-finished':
				BIN.doUpdate('<a href="congviec/duocgiao/opt:finished" data-target="congviec-finished"></a>');
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
	view:	function(){
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);							
		
		$(window).hashchange( function(){
			Congviec.view_updateTab();
		})
		
		$(window).hashchange();
		
		
		$('input').attr('disabled', 'disabled');	
		
				
		$('.delete-ds_congviec').click(function(){
			if(confirm('Bạn có muốn xóa giao việc này không ?'))
			{
				var rel = $(this).attr('rel');
				var congviecchinh = $(this).attr('data-congviecchinh');
				$.ajax({
					type:		'POST',
					url:		'/congviec/del_process/',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:		{id: rel},
					success:	function(result)
					{
						if(result.success)
						{
							if(!congviecchinh)
								location.reload();
							else
								location = '/congviec/dagiao';
						}else
							alert(result.message);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
			}
		});
		
		tinymce.init({
			selector: '#noi_dung',
			height: 100,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser,
			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '12px';
				});
			}
		});
		
		$('#btn-submit-comment').click(function(){
			var content = $.trim(tinyMCE.get('noi_dung').getContent());
			if(content == '')
			{
				console.log($('congviec_id').val());
				alert('Vui lòng nhập vào nội dung.');
				return false;
			}else
			{
				$.ajax({
					type:		'POST',
					url:		'/congviec/add_comment/',
					dataType:	'json',
					data:		{congviec_id: $('#congviec_id').val(), noi_dung: content},
					success:	function(result)
					{
						if(result.success)
						{
							BIN.doUpdate('<a href="congviec/comments/' + $('#congviec_id').val() +'" data-target="congviec-comments"></a>');
							tinyMCE.get('noi_dung').setContent('');
						}else
							alert(result.message);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
			}
		});
	},
	
	view_updateTab:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#congviec-comment';
		switch(currentTab)
		{
			case '#congviec-comment':
				BIN.doUpdate('<a href="congviec/comments/' + $('#congviec_id').val() +'" data-target="congviec-comments"></a>');
				break;
			case '#congviec-progressing':
				BIN.doUpdate('<a href="congviec/nhatky/' + $('#congviec_id').val() + '" data-target="congviec-progressing"></a>');
				break;
			case '#congviec-flow':
				BIN.doUpdate('<a href="congviec/luongcongviec/' + $('#congviec_id').val() + '" data-target="congviec-flow"></a>');
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
	
	search:	function(){
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
		  function() { 
				
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);						
		
		$('.datepicker').datepicker({dateFormat: "dd-mm-yy"});

		$('#btn-expexcel-search').live('click', function(e){
			e.preventDefault();
			$('#form-congviec-search').attr('action', BIN.baseURL + 'congviec/excel_search');
			$('#form-congviec-search').submit();
			$('#form-congviec-search').attr('action', BIN.baseURL + 'congviec/search');
			return false;
		});

		$('#btn-duocgiao-search').click(function(){
			$('#cvduocgiao-list-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'congviec/search/duocgiao',
				data:		$('#form-duocgiao-search').serialize(),
				success:	function(result)
				{
					$('#cvduocgiao-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		
		$('#btn-dagiao-search').click(function(){
			$('#cvdagiao-list-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'congviec/search/dagiao',
				data:		$('#form-dagiao-search').serialize(),
				success:	function(result)
				{
					$('#cvdagiao-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		
		$(window).hashchange( function(){
			Congviec.search_updateTabContent();
		})
		
		$(window).hashchange();
	},
	search_kq:	function(){
		$('#congviec-box div.tab-content').hide(); // Hide the content divs
		$('#congviec-box div.default-tab').show(); // Show the div with class "default-tab"
		$('#congviec-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#congviec-box ul.content-box-tabs li a').click( //When a tab is clicked...
		  function() { 
				
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);						
		
		$('.datepicker').datepicker({dateFormat: "dd-mm-yy"});

		$('#btn-expexcel-search').live('click', function(e){
			e.preventDefault();
			$('#form-congviec-search').attr('action', BIN.baseURL + 'congviec/excel_search');
			$('#form-congviec-search').submit();
			$('#form-congviec-search').attr('action', BIN.baseURL + 'congviec/search');
			return false;
		});

		$('#btn-vtdn-search').click(function(){
			$('#cvvtdngiao-list-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'congviec/search_kq/duocgiao',
				data:		$('#form-vtdn-search').serialize(),
				success:	function(result)
				{
					$('#cvvtdngiao-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		
		$('#btn-donvi-search').click(function(){
			$('#cvdonvigiao-list-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'congviec/search_kq/dagiao',
				data:		$('#form-donvi-search').serialize(),
				success:	function(result)
				{
					$('#cvdonvigiao-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		
		$(window).hashchange( function(){
			Congviec.searchkq_updateTabContent();
		})
		
		$(window).hashchange();
	},
	searchkq_updateTabContent:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#congviec-vtdn';
		
		switch(currentTab)
		{
			case '#congviec-vtdn':
				//updateContent(root + 'congviec/duocgiao_ajax/all', null, 'congviec-all');
				$('#btn-vtdn-search').click();
				break;
			case '#congviec-donvi':
				//updateContent(root + 'congviec/duocgiao_ajax/progressing', null, 'congviec-progressing');
				$('#btn-donvi-search').click();
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
	search_updateTabContent:	function(){
		var currentTab = window.location.hash;
		if(currentTab == '')
			currentTab = '#congviec-duocgiao';
		
		switch(currentTab)
		{
			case '#congviec-duocgiao':
				//updateContent(root + 'congviec/duocgiao_ajax/all', null, 'congviec-all');
				$('#btn-duocgiao-search').click();
				break;
			case '#congviec-dagiao':
				//updateContent(root + 'congviec/duocgiao_ajax/progressing', null, 'congviec-progressing');
				$('#btn-dagiao-search').click();
				break;
			default:
				return false;
				break;
		}
		
		$(currentTab + '-tab').parent().siblings().find("a").removeClass('current'); 
		$(currentTab + '-tab').addClass('current'); 
		
		$(currentTab).siblings().hide(); 
		
		$(currentTab).show(); 
	}
};

$(document).ready(function(){
	BIN.executeFunctionByName(Congviec, params.action, null);
});


var createCookie = function(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

var getCookie = function(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}