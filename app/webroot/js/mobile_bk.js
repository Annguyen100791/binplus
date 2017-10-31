// JavaScript Document
$(document).bind("mobileinit", function() {
// We change default values
	$.mobile.defaultPageTransition = "fade";
	/*
	$.mobile.page.prototype.options.addBackBtn = true;
	$.mobile.page.prototype.options.backBtnText = "Quay lại";
	$.mobile.page.prototype.options.backBtnTheme = "e";
	*/
	$.mobile.page.prototype.options.headerTheme = "b";
	$.mobile.page.prototype.options.footerTheme = "b";
	
	$.mobile.loadPage.defaults.domCache = false;
	
	$.mobile.loader.prototype.options.text = "đang lấy dữ liệu";
	$.mobile.loader.prototype.options.textVisible = true;
	$.mobile.loader.prototype.options.theme = "e";
	$.mobile.loader.prototype.options.html = "";
	
	$.mobile.ignoreContentEnabled = true;
	
	$('#vanban').live('pageshow', function(){
		
	});
	
	$('#vanban-chuadoc').live('pageshow', function(){
		doOnCallBack = function(){
			$('#chuadoc-list').listview('refresh');
			
		};
		ajaxGet('/mobile/vanban/chuadoc', $('#chuadoc-list'), doOnCallBack);
		$('#chuadoc-next').click(function(){
			ajaxGet($(this).attr('href'), $('#chuadoc-list'), doOnCallBack);
		});
		$('#chuadoc-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#chuadoc-list'), doOnCallBack);
		});
	});
	
	$('#vanban-tatca').live('pageshow', function(){
		doOnCallBack = function(){
			$('#tatca-list').listview('refresh');
		};
		ajaxGet('/mobile/vanban/tatca', $('#tatca-list'), doOnCallBack);
		$('#next').click(function(){
			ajaxGet($(this).attr('href'), $('#tatca-list'), doOnCallBack);
		});
		$('#prev').click(function(){
			ajaxGet($(this).attr('href'), $('#tatca-list'), doOnCallBack);
		});
	});
	
	$('#vanban-di').live('pageshow', function(){
		doOnCallBack = function(){
			$('#di-list').listview('refresh');
		};
		ajaxGet('/mobile/vanban/di', $('#di-list'), doOnCallBack);
		$('#di-next').click(function(){
			ajaxGet($(this).attr('href'), $('#di-list'), doOnCallBack);
		});
		$('#di-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#di-list'), doOnCallBack);
		});
	});
	
	$('#vanban-den').live('pageshow', function(){
		doOnCallBack = function(){
			$('#den-list').listview('refresh');
		};
		ajaxGet('/mobile/vanban/den', $('#den-list'), doOnCallBack);
		$('#den-next').click(function(){
			ajaxGet($(this).attr('href'), $('#den-list'), doOnCallBack);
		});
		$('#den-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#den-list'), doOnCallBack);
		});
	});
	
	$('#vanban-theodoi').live('pageshow', function(){
		doOnCallBack = function(){
			$('#theodoi-list').listview('refresh');
		};
		ajaxGet('/mobile/vanban/theodoi', $('#theodoi-list'), doOnCallBack);
		$('#theodoi-next').click(function(){
			ajaxGet($(this).attr('href'), $('#theodoi-list'), doOnCallBack);
		});
		$('#theodoi-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#theodoi-list'), doOnCallBack);
		});
	});
	
	
	$('#tinnhan-chuadoc').live('pageshow', function(){
		doOnCallBack = function(){
			$('#chuadoc-list').listview('refresh');
			
		};
		ajaxGet('/mobile/tinnhan/chuadoc', $('#chuadoc-list'), doOnCallBack);
		$('#chuadoc-next').click(function(){
			ajaxGet($(this).attr('href'), $('#chuadoc-list'), doOnCallBack);
		});
		$('#chuadoc-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#chuadoc-list'), doOnCallBack);
		});
	});
	
	$('#tinnhan-tatca').live('pageshow', function(){
		doOnCallBack = function(){
			$('#tatca-list').listview('refresh');
			
		};
		ajaxGet('/mobile/tinnhan/tatca', $('#tatca-list'), doOnCallBack);
		$('#tatca-next').click(function(){
			ajaxGet($(this).attr('href'), $('#tatca-list'), doOnCallBack);
		});
		$('#tatca-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#tatca-list'), doOnCallBack);
		});
	});
	
	$('#tinnhan-dagui').live('pageshow', function(){
		doOnCallBack = function(){
			$('#dagui-list').listview('refresh');
			
		};
		ajaxGet('/mobile/tinnhan/dagui', $('#dagui-list'), doOnCallBack);
		$('#dagui-next').click(function(){
			ajaxGet($(this).attr('href'), $('#dagui-list'), doOnCallBack);
		});
		$('#dagui-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#dagui-list'), doOnCallBack);
		});
	});
	
	$('#tinnhan-detail').live('pageshow', function(){
		
		$('#tinnhan-detail-delete').unbind();
		$('#tinnhan-detail-delete').click(function(){
			if(delTinnhan($('#tinnhan-detail').attr('data-id')))
				location = '/mobile/tinnhan';
			return false;
		});
		
	});
	
	$('#tinnhan-sent').live('pageshow', function(){
		
		$('#tinnhan-sent-delete').unbind();
		$('#tinnhan-sent-delete').click(function(){
			if(deleteTinnhanDagui($('#tinnhan-sent').attr('data-id')))
				location = '/mobile/tinnhan';
			return false;
		});
		
	});
	
	$('#tinnhan-compose').live('pageshow', function(){
		$('#tinnhan-compose .check-all').click(function(){
			var checked = $(this).attr('checked') == undefined ? false : true;
			var parent = $(this).parent().parent().parent(); 	
			$('input[type=checkbox]', parent).attr('checked', checked).checkboxradio("refresh");
		});
		
		$('#checkall').click(function(){
			var checked = $(this).attr('checked') == undefined ? false : true;
			$('input[type=checkbox]').attr('checked', checked).checkboxradio("refresh");
		});
		
		var checkboxs = $('#tinnhan-compose input[type=checkbox]').filter(':checked');
		checkboxs.each(function(){
			$(this).parent().parent().parent().parent().parent().trigger( "expand" );
		});
			
		
		$('#tinnhan-submit').click(function(){
			
			if(validateForm('#tinnhan-compose-form'))
			{
	
				$.ajax({
					type:	'POST',
					url: '/mobile/tinnhan/compose',
					dataType:	'json',
					data:	$('#tinnhan-compose-form').serialize(),
					error:function(x,e){
						messageBox('Đã phát sinh lỗi.');
						},
					beforeSend:function(){ $.mobile.loading( 'show' );},
					complete:function(){
						$.mobile.loading( 'hide' );
					},
					success:function(data, textStatus, jqXHR){
						messageBox('Tin nhắn đã được gửi.');
					}
				});
			}
		});
	});
	
	
	$('#congviec-dagiao').live('pageshow', function(){
		var loai = $('#congviec-dagiao').attr('data-loai');
		
		doOnCallBack = function(){
			$('#congviec-dagiao-list').listview('refresh');
		};
		ajaxGet('/mobile/congviec/dagiao_ajax/' + loai, $('#congviec-dagiao-list'), doOnCallBack);
		$('#congviec-dagiao-next').click(function(){
			ajaxGet($(this).attr('href'), $('#congviec-dagiao-list'), doOnCallBack);
		});
		$('#congviec-dagiao-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#congviec-dagiao-list'), doOnCallBack);
		});
	});
	
	$('#congviec-duocgiao').live('pageshow', function(){
		var loai = $('#congviec-duocgiao').attr('data-loai');
		
		doOnCallBack = function(){
			$('#congviec-duocgiao-list').listview('refresh');
		};
		ajaxGet('/mobile/congviec/duocgiao_ajax/' + loai, $('#congviec-duocgiao-list'), doOnCallBack);
		$('#congviec-duocgiao-next').click(function(){
			ajaxGet($(this).attr('href'), $('#congviec-duocgiao-list'), doOnCallBack);
		});
		$('#congviec-duocgiao-prev').click(function(){
			ajaxGet($(this).attr('href'), $('#congviec-duocgiao-list'), doOnCallBack);
		});
	});
});

function ajaxGet(url, target, doOnCallBack){
    $.ajax({
        url: url,
        error:function(x,e){handleAjaxError(x,e);},
        beforeSend:function(){ $.mobile.loading( 'show' );},
        complete:function(){$.mobile.loading( 'hide' );doOnCallBack();},
        success:function(data, textStatus, jqXHR){target.html(data);}
    });
}

function	viewVanban(id)
{
	$.ajax({
        url: '/mobile/vanban/view/' + id,
		dataType:	'json',
        error:function(x,e){
				messageBox(e);
			},
        beforeSend:function(){ $.mobile.loading('show');},
        complete:function(){
			$.mobile.loading('hide');
		},
        success:function(data, textStatus, jqXHR){
			var info = '';
			info += '<p><strong>' + data.Tinhchatvanban.ten_tinhchat + ' : ' +  data.Vanban.so_hieu + ' <BR>' + data.Vanban.trich_yeu + '</strong></p>';
			info += '<p>Ngày phát hành: ' +  data.Vanban.ngay_phathanh + ' - Nơi phát hành: ' + data.Vanban.noi_gui + '</p>';
			if(data.Vanban.chieu_di == 0)
				info += '<p>Ngày gửi: ' +  data.Vanban.ngay_gui + '</p>';
			else
				info += '<p>Ngày văn bản đến: ' +  data.Vanban.ngay_nhan + '</p>';
				
			info += '<p>Người ký: ' +  data.Vanban.nguoi_ky + '</p>';
			$('#vanban-info').html(info);
			
			var w = $(window).width() - 60;
			var h = Math.floor(w*11/8);
			
			var files = '';
			for(var i = 0; i < data.Filevanban.length; i++)
			{
				if(i == 0)
					files += '<div data-role="collapsible" data-collapsed="false">';
				else
					files += '<div data-role="collapsible">';
				files += '<h3>File ' + (i+1) + '</h3>';
				files += '<iframe style="border-style: none;" src="https://docs.google.com/viewer?url=' + data.file_path + data.Filevanban[i].path + '&embedded=true" width="' + w + '" height="' + h + '"></iframe>'
				files += '</div>';
			}
			$('#vanban-files').html(files);
			$('#vanban-files').collapsibleset( "refresh" );
			/*
			$('#vanban-content').css('height', h);
			$('#vanban-content').css('width', w);
			$('#vanban-content').html('<iframe id="viewer_container" style="border-style: none;" src="https://docs.google.com/viewer?url=' + data.file + '&embedded=true" width="' + w + '" height="' + h + '"></iframe>');
			*/
		}
    });
	$.mobile.changePage($("#vanban-detail"));
}

function	viewTinnhan(id)
{
	$.ajax({
        url: '/mobile/tinnhan/view/' + id,
		dataType:	'json',
        error:function(x,e){
				messageBox(e);
			},
        beforeSend:function(){ 
			//$.mobile.loading( 'show' );
		},
        complete:function(){
			//$.mobile.loading('hide');
		},
        success:function(data, textStatus, jqXHR){
			$.mobile.loading('show');
			$('#tinnhan-detail').attr('data-id', data.id);
			$('#tinnhan-detail-reply').attr('href', '/mobile/tinnhan/compose/replyto:' + data.id);
			$('#tinnhan-detail-forward').attr('href', '/mobile/tinnhan/compose/forwardto:' + data.id);
			$('#tinnhan-title').html(data.tieu_de);
			$('#tinnhan-nguoigui').html(data.nguoi_gui);
			$('#tinnhan-ngaygui').html(data.ngay_gui);
			$('#tinnhan-noidung').html(data.noi_dung);
			var html = '';
			
			for(var  i = 0; i < data.files.length; i++)
			{
				html += '<li><a data-ajax="false" title="Click để download file này" href="' + data.file_path + data.files[i] + '">' + data.files[i] + '</a>';
			}
			
			if(html == '')
				$('#attach-container').remove();	
			else
			{
				$('#tinnhan-attach').html(html);
				$('#tinnhan-attach').listview('refresh');
			}
			$.mobile.loading('hide');
		}
    });
	$.mobile.changePage($("#tinnhan-detail"));
}

function	viewTinnhanDagui(id)
{
	$.ajax({
        url: '/mobile/tinnhan/view_sent/' + id,
		dataType:	'json',
        error:function(x,e){
				messageBox(e);
			},
        beforeSend:function(){ 
			//$.mobile.loading( 'show' );
		},
        complete:function(){
			//$.mobile.loading('hide');
		},
        success:function(data, textStatus, jqXHR){
			$.mobile.loading('show');
			$('#tinnhan-sent').attr('data-id', data.id);
			$('#tinnhan-sent-title').html(data.tieu_de);
			$('#tinnhan-sent-ngaygui').html(data.ngay_gui);
			$('#tinnhan-sent-noidung').html(data.noi_dung);
			var html = '';
			
			for(var  i = 0; i < data.files.length; i++)
			{
				html += '<li><a data-ajax="false" title="Click để download file này" href="' + data.file_path + data.files[i] + '">' + data.files[i] + '</a>';
			}
			
			if(html == '')
				$('#sent-attach-container').remove();	
			else
			{
				$('#sent-tinnhan-attach').html(html);
				$('#sent-tinnhan-attach').listview('refresh');
			}
			
			var nguoinhan = '';
			
			for(var  i = 0; i < data.nguoinhan.length; i++)
			{
				html += '<li>' + data.nguoinhan[i] + '</li>';
			}
			
			$('#sent-tinnhan-nguoinhan').html(html);
			$('#sent-tinnhan-nguoinhan').listview('refresh');
			
			$.mobile.loading('hide');
		}
    });
	$.mobile.changePage($("#tinnhan-sent"));
}


function confirmBox(text1, text2, callback) {
  	
	$("#confirm .confirm-title").text(text1);
	$("#confirm .confirm-desc").text(text2);
	
	$("#confirm .confirm-do").unbind();
	$("#confirm .confirm-do").click(callback); 
  	$.mobile.changePage("#confirm");
}

function	showTinnhanOptions(id)
{
	$('#tinnhan-options-reply').attr('href', '/mobile/tinnhan/compose/replyto:' + id);
	$('#tinnhan-options-forward').attr('href', '/mobile/tinnhan/compose/forwardto:' + id);
	$('#tinnhan-options-delete').unbind();
	$('#tinnhan-options-delete').click(function(){
		delTinnhan(id);
	});
	$.mobile.changePage("#tinnhan-options");
	
}


function	messageBox(msg)
{
	$( "<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all'><h4>" + msg +"</h4></div>" )
	  .css({ "padding": 10,"display": "block", "opacity": 0.96, "top": $(window).scrollTop() + 100 })
	  .appendTo( $.mobile.pageContainer )
	  .delay( 3200 )
	  .fadeOut( 400, function() {
		$( this ).remove();
	  });

}

function	validateForm(form)
{
	// get a collection of all empty fields
	var emptyFields = $("input[required]", form).filter(function() {
		// $.trim to prevent whitespace-only values being counted as 'filled'
		return !$.trim(this.value).length;
	});
	// if there are one or more empty fields
	if(emptyFields.length) 
	{
		// do stuff; return false prevents submission
		emptyFields.css("border", "1px solid red");   
		messageBox('Bạn cần phải nhập tất cả các field !');
		return false;
	}
	return true;
}
function delTinnhan(tinnhan_id)
{
	var ret = false;
	confirmBox('Xác nhận', 'Bạn có muốn xóa tin nhắn này không ?', function(){
		$.ajax({
			url: '/mobile/tinnhan/unread_delete/' + tinnhan_id,
			dataType:	'json',
			error:function(x,e){
				messageBox('Đã phát sinh lỗi. Vui lòng thử lại.');
			},
			beforeSend:function(){ $.mobile.loading( 'show' );},
			complete:function(){
				$.mobile.loading('hide');
			},
			success:function(data, textStatus, jqXHR){
				$('.ui-dialog').dialog('close');
				messageBox(data.message);
				ret = data.success;
			}
		});
	});
	return ret;
};

function	deleteTinnhanDagui(tinnhan_id)
{
	var ret = false;
	
	confirmBox('Xác nhận', 'Bạn có muốn xóa tin nhắn này không ?', function(){
		$.ajax({
			url: '/mobile/tinnhan/sent_delete/' + tinnhan_id,
			dataType:	'json',
			error:function(x,e){
				messageBox('Đã phát sinh lỗi. Vui lòng thử lại.');
			},
			beforeSend:function(){ $.mobile.loading( 'show' );},
			complete:function(){
				$.mobile.loading('hide');
			},
			success:function(data, textStatus, jqXHR){
				$('.ui-dialog').dialog('close');
				messageBox(data.message);
				ret = data.success;
			}
		});
	});
	return ret;
}

function	updateMainTask(id)
{
	$('#maintask-cancel').unbind();
	$('#maintask-cancel').click(function(){
		$('.ui-dialog').dialog('close');
	});
		
	$('#maintask-submit').unbind();
	$('#maintask-submit').click(function(){
		$.ajax({
			type:	'POST',
			url: '/mobile/congviec/update_maintask/' + id,
			dataType:	'json',
			data:	$('#update-maintask-form').serialize(),
			error:function(x,e){
				messageBox('Đã phát sinh lỗi. Vui lòng thử lại.');
			},
			beforeSend:function(){ $.mobile.loading( 'show' );},
			complete:function(){
				$.mobile.loading('hide');
			},
			success:function(data, textStatus, jqXHR){
				$('.ui-dialog').dialog('close');
				messageBox(data.message);
				location.reload();
			}
		});
	});
	$.mobile.changePage("#congviec-update-maintask");
	
}

function	updateTask(id, mucdo_hoanthanh)
{
	$('#congviec-update-task').page();
	$('#task-mucdohoanthanh').val(mucdo_hoanthanh);
	//console.log($('#task-mucdohoanthanh').val());
	$('#task-mucdohoanthanh').slider('refresh');
	$('#task-cancel').unbind();
	$('#task-cancel').click(function(){
		$('.ui-dialog').dialog('close');
	});
		
	$('#task-submit').unbind();
	$('#task-submit').click(function(){
		$.ajax({
			type:	'POST',
			url: '/mobile/congviec/update_task/' + id,
			dataType:	'json',
			data:	$('#update-task-form').serialize(),
			error:function(x,e){
				messageBox('Đã phát sinh lỗi. Vui lòng thử lại.');
			},
			beforeSend:function(){ $.mobile.loading( 'show' );},
			complete:function(){
				$.mobile.loading('hide');
			},
			success:function(data, textStatus, jqXHR){
				$('.ui-dialog').dialog('close');
				messageBox(data.message);
				location.reload();
			}
		});
	});
	$.mobile.changePage("#congviec-update-task");
	
}