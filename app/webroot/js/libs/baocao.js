var browserField;

var f_FileUploading = false;

function	resetForm()

{

	$('#so_hieu').val('');

	

	if($('#chieu_di').val() == 1)	// den

	{

		setMaxSovanbanden();

	}

	

	$('#ngay_nhan').val('');

	$('#ngay_chuyen').val('');

	$('#ngay_phathanh').val('');

	$('#noi_gui').val('');

	$('#nguoi_ky').val('');

	$('#trich_yeu').val('');

	$('#file_list').html('');

	$('#nguoi_duyet').val('');

	$('#noidung_duyet').val('');

	

	//$('input[type=checkbox]', '#nhanvien-list').attr('checked', false);

	//uncheck all on tree

	

	$("#nhanvien-ds").dynatree("getRoot").visit(function(node){

		node.select(false);

	});

	

	$('#so_hieu').focus();

}

function	setMaxSovanbanden()

{

	$.ajax({

			type:		'GET',

			url:		BIN.baseURL + 'vanban/get_max_sohieuden',

			cache:		false,

			async:		false,

			dataType:	'json',

			success:	function(result)

			{

				if(result.success)

				{

					$('#so_hieu_den').val(result.max);

				}else

					alert(result.message);

			},

			error:		function(result)

			{

				alert(result.message);

			}

		});	

}

function	chieudiChange(obj)

{

	if(obj.value == 1)				// văn bản đến

	{	

		$('#noidung_vb_den').show();

		$('#noidung_vb_di').hide();

		setMaxSovanbanden();

		

	}

	else

	{

		$('#noidung_vb_di').show();

		$('#noidung_vb_den').hide();

	}

}

function randomString(length) {

	/*var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');

	

	if (! length) {

		length = Math.floor(Math.random() * chars.length);

	}

	

	var str = '';

	for (var i = 0; i < length; i++) {

		str += chars[Math.floor(Math.random() * chars.length)];

	}

	*/

	

	return Math.floor(Math.random()*100000000);

}

function	remove_message(obj)

{

	$('#row' + obj.rel).fadeTo(400, 0, function () { // Links with the class "close" will close parent

		$('#row' + obj.rel).slideUp(400);

	});

}

function	remove_file(obj)

{

	$.ajax({

			type:		'POST',

			url:		BIN.baseURL + 'vanban/remove_attach',

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

					//$('#btn-attachfile').show();

				}else

					alert(result.message);

			},

			error:		function(result)

			{

				alert(result.message);

			}

		});	

}

var Baocao = {

	index:	function(){

		

		$('#vanban-box ul.content-box-tabs li a').click( //When a tab is clicked...

			function() { 

				

				window.location.hash = '#' + $(this).attr('rel');

				return false; 



			}

		);							

		

		$(window).hashchange( function(){

			Baocao.updateTabContent();

		})

		$(window).hashchange();

		

		$('.tu_ngay').datepicker({dateFormat: "dd-mm-yy"});

		$('.den_ngay').datepicker({dateFormat: "dd-mm-yy"});

		

		$('.follow').live('click', function(e){

			e.preventDefault();

			var href = $(this).attr('href');

			BIN.doModal($('<a href="' + href + '" title="Đánh dấu theo dõi văn bản" data-width="500px"></a>'));

			

		});

		

		$('#form-unread-search').submit(function(){

			$('#unread-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/unread',

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

		

		

		$('#form-vbdi-search').submit(function(){

			if($(this).attr('action') == BIN.baseURL + 'vanban/di')

			{

				$('#vbdi-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

					$.ajax({

						type:		'POST',

						url:		BIN.baseURL + 'vanban/di',

						data:		$('#form-vbdi-search').serialize(),

						success:	function(result)

						{

							$('#vbdi-list-content').unblock();

							$('#vbdi-list-content').html(result);

						},

						error:		function(result)

						{

							alert('Error');

						}

					});	

					return false;

			}

			return true;

		});

		

		$('#form-noibo-search').submit(function(){

			if($(this).attr('action') == BIN.baseURL + 'vanban/noibo')

			{

				$('#noibo-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

				$.ajax({

					type:		'POST',

					url:		BIN.baseURL + 'vanban/noibo',

					data:		$('#form-noibo-search').serialize(),

					success:	function(result)

					{

						$('#noibo-list-content').unblock();

						$('#noibo-list-content').html(result);

					},

					error:		function(result)

					{

						alert('Error');

					}

				});	

				return false;

			}

			return true;

		});

		

		$('#form-theodoi-search').submit(function(){

			$('#theodoi-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/theodoi',

				cache:		false,

				async:		false,

				data:		$('#form-theodoi-search').serialize(),

				success:	function(result)

				{

					$('#theodoi-list-content').unblock();

					$('#theodoi-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		//////////////////////////
		
		$('#form-vtdn-all-search').submit(function(){

			$('#all-vtdn-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/vtdn_all',

				data:		$('#form-vtdn-all-search').serialize(),

				success:	function(result)

				{

					$('#all-vtdn-list-content').unblock();

					$('#all-vtdn-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-vpth-all-search').submit(function(){

			$('#all-vpth-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/vpth_all',

				data:		$('#form-vpth-all-search').serialize(),

				success:	function(result)

				{

					$('#all-vpth-list-content').unblock();

					$('#all-vpth-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-tcld-all-search').submit(function(){

			$('#all-tcld-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/tcld_all',

				data:		$('#form-tcld-all-search').serialize(),

				success:	function(result)

				{

					$('#all-tcld-list-content').unblock();

					$('#all-tcld-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-khkd-all-search').submit(function(){

			$('#all-khkd-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/khkd_all',

				data:		$('#form-khkd-all-search').serialize(),

				success:	function(result)

				{

					$('#all-khkd-list-content').unblock();

					$('#all-khkd-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-dt-all-search').submit(function(){

			$('#all-dt-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/dt_all',

				data:		$('#form-khkd-all-search').serialize(),

				success:	function(result)

				{

					$('#all-dt-list-content').unblock();

					$('#all-dt-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-mdv-all-search').submit(function(){

			$('#all-mdv-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/mdv_all',

				data:		$('#form-mdv-all-search').serialize(),

				success:	function(result)

				{

					$('#all-mdv-list-content').unblock();

					$('#all-mdv-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-tckt-all-search').submit(function(){

			$('#all-tckt-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/tckt_all',

				data:		$('#form-tckt-all-search').serialize(),

				success:	function(result)

				{

					$('#all-tckt-list-content').unblock();

					$('#all-tckt-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttcntt-all-search').submit(function(){

			$('#all-ttcntt-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttcntt_all',

				data:		$('#form-ttcntt-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttcntt-list-content').unblock();

					$('#all-ttcntt-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttkd-all-search').submit(function(){

			$('#all-ttkd-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttkd_all',

				data:		$('#form-ttkd-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttkd-list-content').unblock();

					$('#all-ttkd-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttdh-all-search').submit(function(){

			$('#all-ttdh-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttdh_all',

				data:		$('#form-ttdh-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttdh-list-content').unblock();

					$('#all-ttdh-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttvt1-all-search').submit(function(){

			$('#all-ttvt1-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttvt1_all',

				data:		$('#form-ttvt1-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttvt1-list-content').unblock();

					$('#all-ttvt1-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttvt2-all-search').submit(function(){

			$('#all-ttvt2-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttvt2_all',

				data:		$('#form-ttvt2-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttvt2-list-content').unblock();

					$('#all-ttvt2-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttvt3-all-search').submit(function(){

			$('#all-ttvt3-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttvt3_all',

				data:		$('#form-ttvt3-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttvt3-list-content').unblock();

					$('#all-ttvt3-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttvt4-all-search').submit(function(){

			$('#all-ttvt4-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttvt4_all',

				data:		$('#form-ttvt4-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttvt4-list-content').unblock();

					$('#all-ttvt4-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-ttvt5-all-search').submit(function(){

			$('#all-ttvt5-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/ttvt5_all',

				data:		$('#form-ttvt5-all-search').serialize(),

				success:	function(result)

				{

					$('#all-ttvt5-list-content').unblock();

					$('#all-ttvt5-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-vtdn-theodoi-search').submit(function(){

			$('#vtdn-theodoi-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/vtdn_theodoi',

				cache:		false,

				async:		false,

				data:		$('#form-vtdn-theodoi-search').serialize(),

				success:	function(result)

				{

					$('#vtdn-theodoi-list-content').unblock();

					$('#vtdn-theodoi-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		
		///////////////////

		$('#form-all-search').submit(function(){

			$('#all-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/all',

				data:		$('#form-all-search').serialize(),

				success:	function(result)

				{

					$('#all-list-content').unblock();

					$('#all-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-vtdn-all-search').submit(function(){

			$('#all-vtdn-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'baocao/vtdn_all',

				data:		$('#form-vtdn-all-search').serialize(),

				success:	function(result)

				{

					$('#all-vtdn-list-content').unblock();

					$('#all-vtdn-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		

		$('#form-den-search').submit(function(){

			$('#den-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/den',

				data:		$('#form-den-search').serialize(),

				success:	function(result)

				{

					$('#den-list-content').unblock();

					$('#den-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});

		$('#form-vbden-search').submit(function(){

			$('#vbden-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/den',

				data:		$('#form-vbden-search').serialize(),

				success:	function(result)

				{

					$('#vbden-list-content').unblock();

					$('#vbden-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});

		$('#form-vanbandi-search').submit(function(){

			$('#vanbandi-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/di',

				data:		$('#form-vanbandi-search').serialize(),

				success:	function(result)

				{

					$('#vanbandi-list-content').unblock();

					$('#vanbandi-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});

		

		$('#btn-expexcel-den').live('click', function(e){

			e.preventDefault();

			$('#form-vbden-search').attr('action', BIN.baseURL + 'vanban/excel_den');

			$('#form-vbden-search').submit();

			$('#form-vbden-search').attr('action', BIN.baseURL + 'vanban/den');

			return false;

			

		});

		$('#btn-expexcel-di').live('click', function(e){

			e.preventDefault();

			$('#form-vanbandi-search').attr('action', BIN.baseURL + 'vanban/excel_di');

			$('#form-vanbandi-search').submit();

			$('#form-vanbandi-search').attr('action', BIN.baseURL + 'vanban/di');

			return false;

		});

		$('#btn-expexcel-noibo').live('click', function(e){

			e.preventDefault();

			$('#form-noibo-search').attr('action', BIN.baseURL + 'vanban/excel_noibo');

			$('#form-noibo-search').submit();

			$('#form-noibo-search').attr('action', BIN.baseURL + 'vanban/noibo');

			return false;

		});

	},

	

	updateTabContent:	function(){

		var currentTab = window.location.hash;

		if(currentTab == '')

			currentTab = '#vtdn-all';

		switch(currentTab)

		{
			case '#vtdn-all':

				BIN.doUpdate('<a href="baocao/vtdn_all" data-target="all-vtdn-list-content"></a>');

				break;
			case '#vpth-all':

				BIN.doUpdate('<a href="baocao/vpth_all" data-target="all-vpth-list-content"></a>');

				break;
			case '#tcld-all':

				BIN.doUpdate('<a href="baocao/tcld_all" data-target="all-tcld-list-content"></a>');

				break;
			case '#khkd-all':

				BIN.doUpdate('<a href="baocao/khkd_all" data-target="all-khkd-list-content"></a>');

				break;
			case '#dt-all':

				BIN.doUpdate('<a href="baocao/dt_all" data-target="all-dt-list-content"></a>');

				break;
			case '#mdv-all':

				BIN.doUpdate('<a href="baocao/mdv_all" data-target="all-mdv-list-content"></a>');

				break;
			case '#tckt-all':

				BIN.doUpdate('<a href="baocao/tckt_all" data-target="all-tckt-list-content"></a>');

				break;
			
			case '#ttcntt-all':

				BIN.doUpdate('<a href="baocao/ttcntt_all" data-target="all-ttcntt-list-content"></a>');

				break;
			case '#ttkd-all':

				BIN.doUpdate('<a href="baocao/ttkd_all" data-target="all-ttkd-list-content"></a>');

				break;
			case '#ttdh-all':

				BIN.doUpdate('<a href="baocao/ttdh_all" data-target="all-ttdh-list-content"></a>');

				break;
			case '#ttvt1-all':

				BIN.doUpdate('<a href="baocao/ttvt1_all" data-target="all-ttvt1-list-content"></a>');

				break;
			case '#ttvt2-all':

			BIN.doUpdate('<a href="baocao/ttvt2_all" data-target="all-ttvt2-list-content"></a>');

				break;
			case '#ttvt3-all':

				BIN.doUpdate('<a href="baocao/ttvt3_all" data-target="all-ttvt3-list-content"></a>');

				break;
			case '#ttvt4-all':

				BIN.doUpdate('<a href="baocao/ttvt4_all" data-target="all-ttvt4-list-content"></a>');

				break;
			case '#ttvt5-all':

				BIN.doUpdate('<a href="baocao/ttvt5_all" data-target="all-ttvt5-list-content"></a>');

				break;
			
			////////////				
			default:

				return false;

				break;

		}
		$(currentTab + '-tab').parent().siblings().find("a").removeClass('current');

		$(currentTab + '-tab').addClass('current'); 

		$(currentTab).siblings().hide(); 

		

		$(currentTab).show();

	},
	add:	function(){

		

		$('#ngay_phathanh').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_nhan').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_chuyen').datepicker({dateFormat: "dd-mm-yy"});

		

		

		$('#chieu_di').val(0);

		$('#noidung_vb_den').hide();

		

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		

		var row = 1;

		// init ajax upload

		new AjaxUpload($('#btn-attachfile'), {

				action: BIN.baseURL + 'vanban/attachfile',

				name: 'data[Vanban][file]',

				onSubmit: function(file, ext){

					 if (! (ext && /^(doc|docx|xls|xlsx|pdf|tif|tiff|ptt|pptx)$/.test(ext))){ 

						alert('Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, PPT, PPTX');

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

						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanban]['+row+'][path] class="uploaded" value="' + jsonObj.path + '"><input type="hidden" name=data[Filevanban]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanban]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');

						/*$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanban]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanban]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');*/

						row++;

						//$('#btn-attachfile').hide();

					} else{

						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');

						row++;

					}

					

					f_FileUploading = false;

				}

			});

		

		

		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][so_hieu]'		:	'required',

				'data[Vanban][trich_yeu]'	:	'required',

				'data[Vanban][ngay_phathanh]'	:	'required',

				'data[Vanban][ngay_nhan]'	:	'required',

				'data[Vanban][noi_gui]'		:	'required',

				'data[Vanban][nguoi_ky]'	:	'required'

			},

			messages:{

				'data[Vanban][so_hieu]'		:	'Vui lòng nhập vào Số hiệu văn bản',

				'data[Vanban][trich_yeu]'	:	'Vui lòng nhập vào Trích yếu',

				'data[Vanban][ngay_phathanh]'	:	'Vui lòng nhập vào Ngày phát hành',

				'data[Vanban][ngay_nhan]'	:	'Vui lòng nhập vào Ngày nhận',

				'data[Vanban][noi_gui]'		:	'Vui lòng nhập vào Nơi gửi văn bản',

				'data[Vanban][nguoi_ky]'	:	'Vui lòng nhập vào Người ký'

			},

			submitHandler: function(form){

				if($('input[class=uploaded]').length < 1)

				{

					alert('Vui lòng đính kèm file văn bản.');

					return false;

				}

				

				if(f_FileUploading)

				{

					alert('Vui lòng chờ upload xong file văn bản.');

					return false;

				}

				

				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn người nhận');

					return false;

				}

				$.ajax({

					type:		'POST',

					url:		BIN.baseURL + 'vanban/add',

					dataType:	'json',

					data:		$('#form-vanban-compose').serialize(),

					success:	function(result)

					{

						if(result.success)

						{

							alert('Văn bản đã được nhập thành công.');

							resetForm();

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

		

		

		//update

		BIN.doUpdate('<a href="nhanvien/nhanviennhan/VanBan.gui" data-target="vanban_nhanvanban">');		

		

		$('#nguoi_ky').select2({

		   minimumInputLength:1,

		   allowClear: true,

		   ajax: {

				url: BIN.baseURL + 'vanban/autocomplete/nguoi_ky',

				dataType: 'json',

				quietMillis: 100,

				data: function (term, page) { // page is the one-based page number tracked by Select2

				 return {

					q: term, //search term

					page_limit: 10, // page size

					page: page, // page number

				 };

				},

				results: function (data, page) {

				   return {results: data.results};

				 }

			 },

		   id: function(object) { 

             return object.text; 

           }, 

		   createSearchChoice:function(term, data) {

			 if ( $(data).filter(function() { return this.text.localeCompare(term)===0;}).length===0) {

			   

			   return {id:term, text:term};

			 }

		   }

		  });

		$('#nguoi_duyet').select2({

		   minimumInputLength:1,

		   allowClear: true,

		   ajax: {

				url: BIN.baseURL + 'vanban/autocomplete/nguoi_duyet',

				dataType: 'json',

				quietMillis: 100,

				data: function (term, page) { // page is the one-based page number tracked by Select2

				 return {

					q: term, //search term

					page_limit: 10, // page size

					page: page, // page number

				 };

				},

				results: function (data, page) {

				   return {results: data.results};

				 }

			 },

		   id: function(object) { 

             return object.text; 

           }, 

		   createSearchChoice:function(term, data) {

			 if ( $(data).filter(function() { return this.text.localeCompare(term)===0;}).length===0) {

			   

			   return {id:term, text:term};

			 }

		   }

		  });

		 

		$('#noi_gui').select2({

		   minimumInputLength:1,

		   allowClear: true,

		   ajax: {

				url: BIN.baseURL + 'vanban/autocomplete/noi_gui',

				dataType: 'json',

				quietMillis: 100,

				data: function (term, page) { // page is the one-based page number tracked by Select2

				 return {

					q: term, //search term

					page_limit: 10, // page size

					page: page, // page number

				 };

				},

				results: function (data, page) {

				   return {results: data.results};

				 }

			 },

		   id: function(object) { 

             return object.text; 

           }, 

		   createSearchChoice:function(term, data) {

			 if ( $(data).filter(function() { return this.text.localeCompare(term)===0;}).length===0) {

			   

			   return {id:term, text:term};

			 }

		   }

		  });

		 

		$('#noi_luu').select2();

		

	},

	

	edit:	function(){

		$('#ngay_phathanh').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_nhan').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_chuyen').datepicker({dateFormat: "dd-mm-yy"});

		

		if($('#chieu_di').val() == 0)

		{

			$('#noidung_vb_den').hide();

		}else

			$('#noidung_vb_den').show();

		

		

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		var row = 1;

		// init ajax upload

		new AjaxUpload($('#btn-attachfile'), {

				action: BIN.baseURL + 'vanban/attachfile',

				name: 'data[Vanban][file]',

				onSubmit: function(file, ext){

					 if (! (ext && /^(doc|docx|xls|xlsx|pdf|tif|tiff|ppt|pptx)$/.test(ext))){ 

						// extension is not allowed 

						alert('Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF');

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

					

					if(jsonObj.success)

					/*{

						row = randomString();

						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.path + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.path + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanban]['+row+'] value="' + jsonObj.path + '"><input type="hidden" name=data[Filevanban]['+row+'] value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanban]['+row+'] value="' + jsonObj.ten_moi + '"></div>');

					} else{

						row = randomString();

						$('#file_list').append('<div class="err_item" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.path + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');

					}*/

					{

						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanban]['+row+'][path] class="uploaded" value="' + jsonObj.path + '"><input type="hidden" name=data[Filevanban]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanban]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');

						row++;

						//$('#btn-attachfile').hide();

					} else{

						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');

						row++;

					}

					

					f_FileUploading = false;

				}

				

			});

		// files

		

		// file tin nhắn

		

		if($('#attach_files').val() != '')

		{

			var files = $.parseJSON($('#attach_files').val());

			$.each(files, function(index, item){

				$('#file_list').append('<div class="uploaded_item" id="row' + item.id + '"><div style="float:left">' + item.path + '</div><div style="float:right"><a href="javascript:void(0)" id="' + item.path + '" onClick="remove_file(this)" rel="'+ item.id +'"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanban][' + item.id + '] value="' + item.path + '"></div>');

				row++;

			});

			

			

		}

		

		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][so_hieu]'		:	'required',

				'data[Vanban][trich_yeu]'	:	'required',

				'data[Vanban][ngay_phathanh]'	:	'required',

				'data[Vanban][ngay_nhan]'	:	'required',

				'data[Vanban][noi_gui]'		:	'required',

				'data[Vanban][nguoi_ky]'	:	'required'

			},

			messages:{

				'data[Vanban][so_hieu]'		:	'Vui lòng nhập vào Số hiệu văn bản',

				'data[Vanban][trich_yeu]'	:	'Vui lòng nhập vào Trích yếu',

				'data[Vanban][ngay_phathanh]'	:	'Vui lòng nhập vào Ngày phát hành',

				'data[Vanban][ngay_nhan]'	:	'Vui lòng nhập vào Ngày nhận',

				'data[Vanban][noi_gui]'		:	'Vui lòng nhập vào Nơi gửi văn bản',

				'data[Vanban][nguoi_ky]'	:	'Vui lòng nhập vào Người ký'

			},

			submitHandler: function(form){

				

				if($('input[class=uploaded]').length < 1 && $('#file_list input[type=hidden]').length < 1)

				{

					alert('Vui lòng đính kèm file văn bản.');

					return false;

				}

				

				if(f_FileUploading)

				{

					alert('Vui lòng chờ upload xong file văn bản.');

					return false;

				}

				

				form.submit();

			}

		});	

		

		//update

		BIN.doUpdate('<a href="nhanvien/nhanviennhan/VanBan.gui" data-target="vanban_nhanvanban">');		

		var selection = $.parseJSON($('#ds_noi_luu').val());

		

		$('#noi_luu').select2();

		$('#noi_luu').select2('val', selection);

	},

	

	view:	function(){

		$('#vanban-box div.tab-content').hide(); // Hide the content divs

		$('#vanban-box div.default-tab').show(); // Show the div with class "default-tab"

		$('#vanban-box li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"

		

		$('#vanban-box ul.content-box-tabs li a').click( // When a tab is clicked...

			function() { 

				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs

				$(this).addClass('current'); // Add class "current" to clicked tab

				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab

				$(currentTab).siblings().hide(); // Hide all content divs

				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab

				return false; 

			}

		);

		

		$('#nhanvien-data tr:odd').addClass("alt-row"); // Add class "alt-row" to even table rows

		

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

		

		$('#form-xuly-vanban').validate({

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Xulyvanban][noi_dung]'		:	'required'

			},

			messages:{

				'data[Xulyvanban][noi_dung]'		:	'Vui lòng nhập vào nội dung xử lý'

			},

			submitHandler: function(form){

				$.ajax({

					type:		'POST',

					url:		$('#form-xuly-vanban').attr('action'),

					dataType:	'json',

					data:	$('#form-xuly-vanban').serialize(),

					success:	function(result)

					{

						if(result.success)

						{

							$('#noi_dung').val('');

							BIN.doUpdate('<a href="vanban/xuly_ajax/' + $('#vanban_id').val() + '" data-target="xuly-container">');

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

		

		$('.btn-theodoi').live('click', function(e){

			e.preventDefault();

			var href = $(this).attr('href');

			BIN.doModal($('<a href="' + href + '" title="Đánh dấu theo dõi văn bản" data-width="500px"></a>'));

		});	

	},

	

	sua:	function()

	{

		$('#btn-vanban-sua').click(function(){

			$('#vanban-sua-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/sua',

				data:		$('#form-vanban-sua').serialize(),

				success:	function(result)

				{

					$('#vanban-sua-content').unblock();

					$('#vanban-sua-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});

	},

	

	search:	function(){

		$('#btn-vanban-search').click(function(){
			

			if($('#form-vanban-search').attr('action') == BIN.baseURL + 'vanban/search')

			{

				$('#vanban-search-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

				$.ajax({

					type:		'POST',

					url:		BIN.baseURL + 'vanban/search',

					data:		$('#form-vanban-search').serialize(),

					success:	function(result)

					{

						$('#vanban-search-content').unblock();

						$('#vanban-search-content').html(result);

					},

					error:		function(result)

					{

						alert('Error');

					}

				});	

				return false;

			}

			return true;

		});

		

		$('.tu_ngay').datepicker({dateFormat: "dd-mm-yy"});

		$('.den_ngay').datepicker({dateFormat: "dd-mm-yy"});

		//$('.datepicker').datepicker({dateFormat: "dd-mm-yy"});

		

		$('#btn-expexcel-search').live('click', function(e){

			e.preventDefault();

			$('#form-vanban-search').attr('action', BIN.baseURL + 'vanban/excel_search');

			$('#form-vanban-search').submit();

			$('#form-vanban-search').attr('action', BIN.baseURL + 'vanban/search');

			return false;

		});

		

		$('#noi_gui').select2({

		   minimumInputLength:1,

		   allowClear: true,

		   ajax: {

				url: BIN.baseURL + 'vanban/autocomplete/noi_gui',

				allowClear: true,

				dataType: 'json',

				quietMillis: 100,

				data: function (term, page) { // page is the one-based page number tracked by Select2

				 return {

					q: term, //search term

					page_limit: 10, // page size

					page: page, // page number

				 };

				},

				results: function (data, page) {

				   return {results: data.results};

				 }

			 },

		   id: function(object) { 

             return object.text; 

           }, 

		   createSearchChoice:function(term, data) {

			 if ( $(data).filter(function() { return this.text.localeCompare(term)===0;}).length===0) {

			   

			   return {id:term, text:term};

			 }

		   }

		  });

	}

};





$(document).ready(function(){

	BIN.executeFunctionByName(Baocao, params.action, null);

});