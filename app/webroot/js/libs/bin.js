// JavaScript Document
var start_time ;
var time ;
var BIN = {
	baseURL: params.basePath,
	loadingMessage:	'đang xử lý, vui lòng đợi trong giây lát ..',
	layer:	0,
	dlgOptions:	{
		autoOpen:	false,
		title:	'',
		modal:	true,
		bgiframe: true,
		resizable: false,
		width: 'auto',
		height: 'auto',
		minHeight:100,
		minWidth: 300,
		show: {
			effect: "drop",
			duration: 400,
			direction: "up"
		},
		hide: {
			effect: "drop",
			duration: 400,
			direction: "up"
		}
	},
	
	validationOptions:	{
		errorElement: 	"span",
		errorClass:		"invalid help-inline"
	},
	
	blockUI : {
		border: 'none', 
		'border-radius': '4px',
		padding: '15px 15px 15px 50px', 
		background: "#fff url('/img/c_loader.gif') 10px center no-repeat",
		opacity: .5, 
		color: '#000',
		width: 'auto'
	},
	
	editorPlugins: ["advlist autolink link image lists print hr anchor pagebreak textcolor",
		         "searchreplace wordcount fullscreen insertdatetime media nonbreaking",
         		"table contextmenu emoticons paste"],
				
	editorToolbar: "fontselect fontsizeselect | undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor emoticons | link image media fullscreen",
	
	editorFileBrowser:	function(field_name, url, type, win){
		browserField = $("#" + field_name);
		browserWin = win;
		window.open(params.basePath + 'file_managers/browse', 'browserWindow', 'modal,width=650,height=400,scrollbars=yes');
	},
	editorMenu: { // this is the complete default configuration
        edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
        insert : {title : 'Insert', items : 'image link media'},
        view   : {title : 'View'  , items : 'visualaid | fullscreen'},
        format : {title : 'Format', items : 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
        table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'}
    },
	
	initLayout:	function(){
		
		$.ui.dialog.prototype._allowInteraction = function(e) {
			return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-drop').length;
		};

		$(document).on("click",".nogo", function(){
			$('.current').attr('class', 'select');
			$(this).parent().parent().attr('class', 'current');
			$('.select_sub', $(this).parent().parent()).addClass('show');
			
			if($('.sub a', $(this).parent().parent()).first().attr('href') != undefined)
			{
				location = $('.sub a', $(this).parent().parent()).first().attr('href');
			}
		});
		
		if(params.controller == 'pages') 
		{
			mnu_selected = 'mnu-home-dashboard';
		}
		else
		{
			mnu_selected = $('#menu-bar a[href^="' + params.basePath + params.controller + '/' + params.action + '"]').first().find('span').first().attr('id');
		}
		
		
		var parent = $('#' + mnu_selected).parent().parent().parent();
		if(parent.hasClass('sub'))
		{
			$('#' + mnu_selected).parent().parent().attr('class', 'sub_show');
			parent.parent().addClass('show');
			parent.parent().parent().parent().attr('class', 'current');
		}else if(parent.hasClass('select'))
		{
			parent.attr('class', 'current');
		}
		
		$(".showhide-account").click(function () {
			$(".account-content").slideToggle("slow");
			//$(this).toggleClass("active");
			$('#acc-logout').show();
			return false;
		});
		
		$(document).bind("click", function (e) {
			if (e.target.id != $(".showhide-account").attr("class")) $(".account-content").slideUp();
		});
		
		
		// datetimepicker
		
		$.datepicker.setDefaults( $.datepicker.regional["vi"] );
		
		$('.check-all').live('click', function(){
			var container = $(this).parentsUntil('.data');
			$('input[type=checkbox]', container).attr('checked', this.checked);
		});
		
		
		this.doListing();
	},
	
	initAction:	function(){
		$('a[data-mode=ajax], button[data-mode=ajax]').live('click', function(e){
			e.preventDefault();
			var action = $(this).attr('data-action');
			switch(action)
			{
				case 'dialog':
					BIN.doModal($(this));
					break;
				case 'update':
					BIN.doUpdate($(this));
					break;
				case 'delete-all':
					BIN.doDeleteAll($(this));
					break;
				case 'delete-item':
					BIN.doDeleteItem($(this));
					break;
				case 'toggle':
					BIN.doToggle($(this));
					break;
				case 'paging':
					BIN.doPaging($(this));
					break;
					
			}
			
		});
		
		$('.btn-close').live('click', function(){
			var dialog_id = $(this).parentsUntil('.ui-dialog-content').parent().attr('id');
			$('#' + dialog_id).dialog('close');
		});
		
		$('a[data-action=ajax-paging]').live('click', function(e){
			
		});
		
		$('.data-container .check-all').live('click', function(){
			var checked = this.checked;											 
			$('.data-container input[type=checkbox]').each(function(){
				this.checked = checked;
			});
		});
	},
	doModal:	function(obj){
		var $this = this;
		var layer = $this.layer++;
		var dialog_id = 'dialog-id-layer-' + layer;
		
		var width = $(obj).attr('data-width');
		if(!width)
			width = 600;
		var href = $(obj).attr('data-href');
		var title = $(obj).attr('title');
		if(!title)
			title = $(obj).attr('data-original-title');
		if(!title)
			title = $this.name;
		if(!href)
			href = $(obj).attr('href');
		if(href.indexOf("/") > 0)
			href = BIN.baseURL + href;
		var options = BIN.dlgOptions;
		options.width = width;
		
		$.ajax({
			type:	'GET',
			url:	href,
			dataType: 'html',
			cache:	false,
			success:	function(data){
				$(document.body).append('<div id="' + dialog_id + '" class="dialog"></div>');
				
				options.close = function(event, ui){
					$('#' + dialog_id).dialog('destroy').remove();
					$this.layer--;
				};
				options.title = title;
				options.position = 'center';
				
				
				$('#' + dialog_id).dialog(options);
				$('#' + dialog_id).html(data);
				
				
				$('#' + dialog_id).parent().css("z-index", 2001 + $this.layer);
				$('#' + dialog_id).dialog('open');
				$('#' + dialog_id).parent().parent().prev().css("z-index", 2000 + $this.layer);
				
			}
		});
		
			
	    return false;
	},
	
	doUpdate:	function(obj){
		//var id = obj.attr('id');
		var href = $(obj).attr('href');
		if(!href)
			href = $(obj).attr('data-href');
		if(href.indexOf('/') != 0)
			href = BIN.baseURL + href;
		var target = $(obj).attr('data-target');
		
		target = '#' + target;	
		$(target).html('<img src="/img/circle_ball.gif">');
		
		$.ajax({
			type:	'GET',
			dataType: 'html',
			url:	href,
			cache:	false,
			success: function(data){
				
				//$(target).unblock();
				$(target).html(data);
			}
		});
		return false;
	},
	
	doDeleteAll:	function(obj){
		var target = $(obj).attr('data-target');
		var arr = $.map($('#' + target + ' input[type=checkbox]').not('.check-all'), function(item){
				if(item.checked)
					return item.value;
			});
		var ids = arr.join(',');
		
		if(ids == '')
		{
			alert(obj.attr('data-msg'));
			return false;
		}else
		{
			var msg = obj.attr('data-confirm');
			var href = obj.attr('href');
			if(!href)
				href = obj.attr('data-href');
			
			if (confirm(msg)){
				
				$('#' + target).block({message: BIN.loadingMessage, css: BIN.blockUI});
				$.post(href, {v_id: ids}, function(data){
					$('#' + target).unblock();
					$('#' + target).html(data);
				});
			}
		}
	},
	
	doDeleteItem:	function(obj){
		var href = obj.attr('href');
		var title = obj.attr('data-confirm');
		var target = $(obj).attr('data-target');
		if (confirm(title)){
			$('#' + target).block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.post(href, {v_id: $(obj).attr('data-id')}, function(data){
				$('#' + target).unblock();
				if(target)
					$('#' + target).html(data);
				else
					location.reload();
			});
		}
	},
	
	doToggle:	function(obj){
		var href = obj.attr('href');
		$('img', obj).attr('src', BIN.baseURL + 'img/circle_ball.gif');
		$.ajax({
			type:		'GET',
			url:		href,
			cache:		false,
			dataType:	'json',
			success:	function(result)
			{
				if(result.success)
				{
					if(result.status == 1)
					{
						$('img', obj).attr('src', BIN.baseURL + 'img/icons/tick_circle.png');
						obj.attr('title', 'Click để không hiển thị trên trang chủ');
					}
					else
					{
						$('img', obj).attr('src', BIN.baseURL + 'img/icons/cross_circle.png');
						obj.attr('title', 'Click để cho phép hiển thị');
					}
				}
			},
			error:		function(result)
			{
				alert(result.message);
			}
		});	 // end of ajax
	},
	
	doPaging:	function(obj){
		var href = $(obj).attr('href');
		var target = $(obj).attr('data-target');
		$('#' + target).block({message: BIN.loadingMessage, css: BIN.blockUI});
		$.get(href, function(data){
			$('#' + target).unblock();
			$('#' + target).html(data);
			BIN.doListing();
		});
	},
		
	doListing:	function(){
		$('.data tr:even').addClass("alt-row");
		this.tooltip();
	},
	
	tooltip:	function(){
		$('a,input').each(function(){
			var title = $(this).attr('title');
			if(title)		
			{		
				var position	 = $(this).attr('tip-position');
				if(!position)
					position = 'top center';
				$(this).qtip({
					 show: 'mouseover',
					 hide: 'mouseout',
					 //position:	{at: 'top center', my: 'bottom center'},
					 
					 position:	{
									at: position, 
									my: 'bottom center',
									//target: 'mouse', 
									viewport: $(window), 
									adjust: {
										x: 0,  y: -5
									}
					},
					 style:	{classes: 'qtip-rounded qtip-shadow'}
					 });
			}
				
		});
	},
	
	
	
	
	executeFunctionByName:	function(fnamespace, fname, fopt){
		if (typeof fnamespace === 'object') 
		{
			if(typeof fnamespace[fname] == 'function')
				fnamespace[fname](fopt);
		}
		else
		{
			if(typeof fname == 'function')
			 {
				fname(fopt);
			 }
		}
	},
	
	notify: function(type, htmlMessage){
		$.notification({type:type,width:"auto",content:htmlMessage,html:true,autoClose:true,timeOut:"50500",position:"topRight",position:"topRight",effect:"fade",animate:"fadeUp",easing:"easeOutQuad",duration:"200"});
	},
	hidetimelabel:	function(filename){
		var pos = filename.indexOf('_');
	
		if(pos < 0)	return filename;
		
		return filename.substring(pos + 1, filename.length);
	},
	init_calendar:	function(opts){
		var options = {
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				buttonText: {
					today:    'Hôm nay',
					month:    'Xem theo tháng',
					week:     'Xem theo tuần',
					day:      'Xem theo ngày'
				},
				monthNames:['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
				monthNamesShort:['T01', 'Th02', 'Th03', 'T04', 'T05', 'T06', 'T07', '0T8', 'T09', 'T10', 'T11', 'T12'],
				dayNames:['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
				dayNamesShort:['CN', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'],
				columnFormat:{
					week: 'ddd - dd/MM',
					day: 'dddd - dd/MM' 
					},
				timeFormat: {
					agenda: 'HH:mm{ - HH:mm}', // 05:00 - 06:30
					'': 'HH:mm{ - HH:mm}'
					},
				firstDay: 1,	// Ngày đầu tuần là thứ 2
				selectHelper: 	true,
				allDaySlot: 	true,
				editable: 	true,
				disableDragging:	true,
				disableResizing:	true,
				eventClick: function(calEvent, jsEvent, view) {
					if(calEvent.editable)
					{
						BIN.doModal($('<a href="calendars/edit_lichlamviec/' + calEvent.id + '" title="Chỉnh sửa lịch công tác" data-width="500">'));
					}else if(calEvent.viewable)
					{
						BIN.doModal($('<a href="calendars/view_lichlamviec/' + calEvent.id + '" title="Xem chi tiết lịch công tác" data-width="500">'));
					}
				},
				eventRender: function(event, element) {
					element.qtip({
						content: event.description,
						position: {at: 'top center', my: 'bottom center'},
						style:	{classes:"qtip-rounded qtip-shadow"}
					});
				}
			};
		for (var i in opts) {
			options[i] = opts[i];
		}
		
		var calendar = $(opts['container']).fullCalendar(options);
	}
};
/////

function	refreshTinnhan()
	{
		var current_time = new Date();
		current_time = current_time.getTime();
		//console.log((current_time - start_time)/1000); 
		time = (current_time - start_time)/1000;
		if ((5400 - time) < 300 ) //80p
			alert("Bạn sắp hết thời gian đăng nhập!. Đề nghị lưu lại thông tin(nếu cần)");
		
		//BIN.doUpdate($('<a href="pages/check_time" data-target="related-act-inner"></a>'));
	}

$(document).ready(function(){
	start_time = new Date();
	start_time = start_time.getTime();
	//console.log(start_time.getTime());
	$.ajaxSetup({
		complete: function () {
			start_time = new Date();
			start_time = start_time.getTime();
			//console.log(start_time);

		}
	}); 
	BIN.initLayout();
	BIN.initAction();
	//refresh form gửi tin nhắn để kiểm tra time 
	//refreshTinnhan();
	setInterval('refreshTinnhan()', 300000);	// 5 minutes
});
