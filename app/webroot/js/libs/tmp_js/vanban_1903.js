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



function	VBGapChange(obj)

{


	if ($("#vb_gap").is(":checked")) {  

    	$('#vbgap_thoigian').show();	

	} else {

		// checkbox is not checked 

		$('#vbgap_thoigian').hide();

	}	

	

}
function	NgayHoanThanh(obj)

{
		$('#vbgap_ngayhoanthanh').datepicker({

			dateFormat: "dd-mm-yy",

			minDate: $('#vbgap_ngayhoanthanh').datepicker('getDate')

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

function	chuyenChange(obj)

{

	//alert($(obj).is(":checked"));	

	if($(obj).is(":checked") == true)				// chuyển VB đến cho PGĐ duyệt

	{	

		$('#nhanvien_duyetvb').show();
		//$('#vbtime').hide();

	}

	else

	{

		$('#nhanvien_duyetvb').hide();
		//$('#vbtime').show();

	}

}







function	loaivbChange(obj)

{

	//alert(obj.value);

	if(obj.value == 4)				// loại văn bản là báo cáo

	{	

		$('#noidung_loaivb').show();

	}

	else

	{

		$('#noidung_loaivb').hide();

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



var Vanban = {
	index:	function(){
		$('#vanban-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);							
		$(window).hashchange( function(){



			Vanban.updateTabContent();



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



		/*$('#form-vbden-search').submit(function(){



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



		});*/



		$('#form-di-search').submit(function(){



			$('#di-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/di',



				data:		$('#form-di-search').serialize(),



				success:	function(result)



				{



					$('#di-list-content').unblock();



					$('#di-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		$('#form-chotrinh-search').submit(function(){



			$('#chotrinh-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/chotrinh',



				data:		$('#form-chotrinh-search').serialize(),



				success:	function(result)



				{



					$('#chotrinh-list-content').unblock();



					$('#chotrinh-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		$('#form-datrinh-search').submit(function(){



			$('#datrinh-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/vbden_datrinh',



				data:		$('#form-datrinh-search').serialize(),



				success:	function(result)



				{



					$('#datrinh-list-content').unblock();



					$('#datrinh-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		$('#form-gap-search').submit(function(){
			$('#gap-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'gap/vbden_gap',
				data:		$('#form-gap-search').serialize(),
				success:	function(result)
				{
					$('#gap-list-content').unblock();
					$('#gap-list-content').html(result);
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



			currentTab = '#vanban-unread';



		switch(currentTab)



		{

			case '#vanban-all':

				BIN.doUpdate('<a href="vanban/all" data-target="all-list-content"></a>');

				break;

			case '#vanban-unread':



				BIN.doUpdate('<a href="vanban/unread" data-target="unread-list-content"></a>');



				break;



			case '#vanban-den':



				BIN.doUpdate('<a href="vanban/den" data-target="den-list-content"></a>');



				break;



			case '#vanban-di':



				BIN.doUpdate('<a href="vanban/di" data-target="di-list-content"></a>');



				break;



			case '#vanban-noibo':



				BIN.doUpdate('<a href="vanban/noibo" data-target="noibo-list-content"></a>');



				break;



			case '#vanban-theodoi':



				BIN.doUpdate('<a href="vanban/theodoi" data-target="theodoi-list-content"></a>');



				break;

			case '#vanban-chotrinh':

				BIN.doUpdate('<a href="vanban/chotrinh" data-target="chotrinh-list-content"></a>');

				break;

			case '#vanban-datrinh':

				BIN.doUpdate('<a href="vanban/vbden_datrinh" data-target="datrinh-list-content"></a>');

				break;

			

			case '#vanban-gap':

				BIN.doUpdate('<a href="vanban/vbden_gap" data-target="gap-list-content"></a>');

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



index_vanbanden:	function(){
		$('#vanban-box ul.content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				window.location.hash = '#' + $(this).attr('rel');
				return false; 
			}
		);							
		$(window).hashchange( function(){
			Vanban.updateTabContentVBDEN();
		})
		$(window).hashchange();
		$('.tu_ngay').datepicker({dateFormat: "dd-mm-yy"});
		$('.den_ngay').datepicker({dateFormat: "dd-mm-yy"});
		$('.follow').live('click', function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			BIN.doModal($('<a href="' + href + '" title="Đánh dấu theo dõi văn bản" data-width="500px"></a>'));
		});

		$('#form-quantrong-search').submit(function(){
			$('#quantrong-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'vanban/quantrong',
				data:		$('#form-quantrong-search').serialize(),
				success:	function(result)
				{
					$('#quantrong-list-content').unblock();
					$('#quantrong-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		$('#form-chotrinh-search').submit(function(){
			$('#chotrinh-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'vanban/chotrinh',
				data:		$('#form-chotrinh-search').serialize(),
				success:	function(result)
				{
					$('#chotrinh-list-content').unblock();
					$('#chotrinh-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});

		$('#form-datrinh-search').submit(function(){



			$('#datrinh-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/vbden_datrinh',



				data:		$('#form-datrinh-search').serialize(),



				success:	function(result)



				{



					$('#datrinh-list-content').unblock();



					$('#datrinh-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		

		$('#form-thongbao-search').submit(function(){



			$('#thongbao-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/thongbao',



				data:		$('#form-thongbao-search').serialize(),



				success:	function(result)



				{



					$('#thongbao-list-content').unblock();



					$('#thongbao-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		

		$('#form-choduyet-search').submit(function(){



			$('#choduyet-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/choduyet',



				data:		$('#form-choduyet-search').serialize(),



				success:	function(result)



				{



					$('#choduyet-list-content').unblock();



					$('#choduyet-list-content').html(result);



				},



				error:		function(result)



				{



					alert('Error');



				}



			});	



			return false;



		});

		

		$('#form-daduyet-search').submit(function(){

			$('#daduyet-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});

			$.ajax({

				type:		'POST',

				url:		BIN.baseURL + 'vanban/vbden_daduyet',

				data:		$('#form-daduyet-search').serialize(),

				success:	function(result)

				{

					$('#daduyet-list-content').unblock();

					$('#daduyet-list-content').html(result);

				},

				error:		function(result)

				{

					alert('Error');

				}

			});	

			return false;

		});
		$('#form-dachuyen-search').submit(function(){
			$('#dachuyen-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'vanban/vbden_dachuyen',
				data:		$('#form-dachuyen-search').serialize(),
				success:	function(result)
				{
					$('#dachuyen-list-content').unblock();
					$('#dachuyen-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});

		$('#form-capnhatketqua-search').submit(function(){



			$('#capnhatketqua-list-content').block({message: BIN.loadingMessage, css: BIN.blockUI});



			$.ajax({



				type:		'POST',



				url:		BIN.baseURL + 'vanban/ketquaxuly',



				data:		$('#form-capnhatketqua-search').serialize(),



				success:	function(result)



				{



					$('#capnhatketqua-list-content').unblock();



					$('#capnhatketqua-list-content').html(result);



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

	updateTabContentVBDEN:	function(){
		var currentTab = window.location.hash;
		if($('#duyet_vb').val() == true && currentTab == '')
			currentTab = '#vanban-choduyet';
		if($('#trinh_vb').val() == true && currentTab == '')
			currentTab = '#vanban-chotrinh';
			
		switch(currentTab)
		{

			
			case '#vanban-chotrinh':

				BIN.doUpdate('<a href="vanban/chotrinh" data-target="chotrinh-list-content"></a>');

				break;

			case '#vanban-datrinh':

				BIN.doUpdate('<a href="vanban/vbden_datrinh" data-target="datrinh-list-content"></a>');

				break;

			case '#vanban-choduyet':

				BIN.doUpdate('<a href="vanban/choduyet" data-target="choduyet-list-content"></a>');

				break;

			case '#vanban-daduyet':

				BIN.doUpdate('<a href="vanban/vbden_daduyet" data-target="daduyet-list-content"></a>');

				break;
			case '#vanban-dachuyen':

				BIN.doUpdate('<a href="vanban/vbden_dachuyen" data-target="dachuyen-list-content"></a>');

				break;

			case '#vanban-quantrong':

				BIN.doUpdate('<a href="vanban/quantrong" data-target="quantrong-list-content"></a>');

				break;
			case '#vanban-thongbao':

				BIN.doUpdate('<a href="vanban/thongbao" data-target="thongbao-list-content"></a>');

				break;
			case '#vanban-capnhatketqua':

				BIN.doUpdate('<a href="vanban/ketquaxuly" data-target="capnhatketqua-list-content"></a>');

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



	



	add:	function(){

		$('#ngay_phathanh').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_nhan').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_chuyen').datepicker({dateFormat: "dd-mm-yy"});

		$('#chieu_di').val(0);

		$('#noidung_vb_den').hide();

		$('#noidung_loaivb').hide();

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

					 if (! (ext && /^(doc|docx|xls|xlsx|pdf|tif|tiff|ptt|pptx|odt|ott|odm|html|oth|ods|ots|odg|otg|odp|otp|odf|odb|oxt)$/.test(ext))){ 
						alert('Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, PPT, PPTX, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT');
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

	//////////////

	trinh_vanban:	function(){

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

					if (! (ext && /^(tiff|tif|jpg|png|jpeg|gif|swf|flv|avi|wmv|mp3|zip|rar|doc|docx|xls|xlsx|ppt|pptx|pdf|odt|ott|odm|html|oth|ods|ots|odg|otg|odp|otp|odf|odb|oxt)$/.test(ext))){ 
					alert('Chỉ được phép upload các file có phần mở rộng là TIFF, TIF, JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT.');
					// if (! (ext && /^(doc|docx|xls|xlsx|pdf|tif|tiff|ptt|pptx)$/.test(ext))){ 
						//alert('Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, PPT, PPTX');

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

						row++;

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
				'data[Vanban][nguoi_duyet_id]'		:	'required'
			},

			messages:{
				'data[Vanban][nguoi_duyet_id]'		:	'Vui lòng Chọn người nhận văn bản'

			},

			submitHandler: function(form){
				/*var editorContent = tinymce.get('noidung_trinh').getContent();
				if($.trim(editorContent) == '')
				{
				   alert('Vui lòng nhập vào Nội dung trình văn bản');

					return false;
				}*/
					
				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng định hướng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	

		//update

		BIN.doUpdate('<a href="nhanvien/dinhhuong_nhanvb/VanBan.trinh" data-target="vanban_nhanvanban">');		

	},

	edit_vanbanchuyen:	function(){

		$('#nhanvien_duyetvb').hide();

		var c = $('#chuyen_nguoiduyet').is(":checked");

		if(c == false)

		{

			$('#nhanvien_duyetvb').hide();

		}else

			$('#nhanvien_duyetvb').show();

			

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		$('#btn-vanban-chuyen').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][noidung_duyet]'	:	'required'

			},

			messages:{

				'data[Vanban][noidung_duyet]'		:	'Vui lòng nhập vào Nội dung duyệt văn bản'

			},



			submitHandler: function(form){

				/*var editorContent = tinymce.get('noidung_duyet').getContent();
				if($.trim(editorContent) == '')
				{
				   alert('Vui lòng nhập vào Nội dung duyệt văn bản');

					return false;
				}*/
				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	
		

		//update

		BIN.doUpdate('<a href="nhanvien/phanluong_nhanvb/VanBan.suavbdachuyen" data-target="vanban_nhanvanban">');		

	},

	edit_vanbantrinh:	function(){



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

					{



						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanbanduyet]['+row+'][path] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanbanduyet]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filevanbanduyet]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');

						row++;

					} else{



						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');

						row++;

					}

					f_FileUploading = false;

				}

			});

		//files đã đính kèm

		if($('#attach_files').val() != '')

		{

			var files = $.parseJSON($('#attach_files').val());

			$.each(files, function(index, item){

				$('#file_list').append('<div class="uploaded_item" id="row' + item.id + '"><div style="float:left">' + item.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + item.ten_cu + '" onClick="remove_file(this)" rel="'+ item.id +'"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filevanbanduyet][' + item.id + '] value="' + item.ten_cu + '"></div>');

				row++;

			});

		}

		// validate



		$('#form-vanban-compose').validate({

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][noidung_trinh]'		:	'required',

				'data[Vanban][nguoi_duyet_id]'		:	'required'

			},

			messages:{

				'data[Vanban][noidung_trinh]'		:	'Vui lòng nhập vào Nội dung trình văn bản',

				'data[Vanban][nguoi_duyet_id]'		:	'Vui lòng Chọn người nhận văn bản'

			},

			submitHandler: function(form){

				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng định hướng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	

		//update

		BIN.doUpdate('<a href="nhanvien/dinhhuong_nhanvb/VanBan.trinh" data-target="vanban_nhanvanban">');		

	},

	

	duyet_vanban:	function(){

		$('#nhanvien_duyetvb').hide();

		var c = $('#chuyen_nguoiduyet').is(":checked");

		if(c == false)

		{

			$('#nhanvien_duyetvb').hide();

		}else

			$('#nhanvien_duyetvb').show();

			

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		$('#btn-vanban-chuyen').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][noidung_duyet]'	:	'required'

			},

			messages:{

				'data[Vanban][noidung_duyet]'		:	'Vui lòng nhập vào Nội dung duyệt văn bản'

			},



			submitHandler: function(form){

				/*var editorContent = tinymce.get('noidung_duyet').getContent();
				if($.trim(editorContent) == '')
				{
				   alert('Vui lòng nhập vào Nội dung duyệt văn bản');

					return false;
				}*/
				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	

		

		//update

		BIN.doUpdate('<a href="nhanvien/phanluong_nhanvb/VanBan.duyet" data-target="vanban_nhanvanban">');		

	},

	edit_vanbanduyet:	function(){

		$('#nhanvien_duyetvb').hide();

		var c = $('#chuyen_nguoiduyet').is(":checked");

		if(c == false)

		{

			$('#nhanvien_duyetvb').hide();

		}else

			$('#nhanvien_duyetvb').show();

			

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		$('#btn-vanban-chuyen').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});

		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][noidung_duyet]'	:	'required'

			},

			messages:{

				'data[Vanban][noidung_duyet]'		:	'Vui lòng nhập vào Nội dung duyệt văn bản'

			},



			submitHandler: function(form){

				/*var editorContent = tinymce.get('noidung_duyet').getContent();
				if($.trim(editorContent) == '')
				{
				   alert('Vui lòng nhập vào Nội dung duyệt văn bản');

					return false;
				}*/
				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	

		

		//update

		BIN.doUpdate('<a href="nhanvien/phanluong_nhanvb/VanBan.duyet" data-target="vanban_nhanvanban">');		

	},


	

	up_result:	function(){

		$('#btn-vanban-add').click(function(){

			$('#form-vanban-compose').submit()

			return false;

		});
		////
		var row = 1;

		// init ajax upload

		new AjaxUpload($('#btn-attachfile'), {

				action: BIN.baseURL + 'vanban/attachfile',

				name: 'data[Vanban][file]',

				onSubmit: function(file, ext){

					if (! (ext && /^(tiff|tif|jpg|png|jpeg|gif|swf|flv|avi|wmv|mp3|zip|rar|doc|docx|xls|xlsx|ppt|pptx|pdf|odt|ott|odm|html|oth|ods|ots|odg|otg|odp|otp|odf|odb|oxt)$/.test(ext))){ 
					alert('Chỉ được phép upload các file có phần mở rộng là TIFF, TIF, JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT.');
					// if (! (ext && /^(doc|docx|xls|xlsx|pdf|tif|tiff|ptt|pptx)$/.test(ext))){ 
						//alert('Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, PPT, PPTX');

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

						row++;

					} else{

						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');

						row++;

					}

					

					f_FileUploading = false;

				}

			});
		
		
		////
		
		// validate

		$('#form-vanban-compose').validate({

			

			errorElement: 	"em",

			errorClass:		"warning",

			rules:{

				'data[Vanban][noidung_capnhat]'	:	'required'

			},

			messages:{

				'data[Vanban][noidung_capnhat]'		:	'Vui lòng nhập vào Nội dung cần cập nhật'

			},

			submitHandler: function(form){

				

				if($('#nv_selected').val() == '')

				{

					alert('Vui lòng chọn đối tượng giao văn bản');

					return false;

				}

				form.submit();

			}

		});	

		

		//update

		BIN.doUpdate('<a href="nhanvien/nhanviennhan/VanBan.capnhatkq" data-target="vanban_nhanvanban">');		

	},

	//////////////

	

	add_baocao:	function(){

		$('#ngay_phathanh').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_nhan').datepicker({dateFormat: "dd-mm-yy"});

		$('#ngay_chuyen').datepicker({dateFormat: "dd-mm-yy"});

		$('#chieu_di').val(0);

		$('#noidung_vb_den').hide();

		$('#noidung_loaivb').hide();

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

					url:		BIN.baseURL + 'vanban/add_baocao',

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

		BIN.doUpdate('<a href="nhanvien/nhanviennhan/BaoCao.gui" data-target="vanban_nhanvanban">');		

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

	

	//////////////

	

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



	},
	search_kq:	function(){



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

	BIN.executeFunctionByName(Vanban, params.action, null);

	$(document).on("click","#print-button",function(e){
		e.preventDefault();
		PrintElem("#vanban-info-head");	
	});

});

function PrintElem(elem)
{
	jQuery(elem).css("position","static");
	var data = jQuery(elem)[0].outerHTML;
    Popup(data);
}

function Popup(data) 
{	
    var oldPage = document.body.innerHTML;
	document.body.innerHTML = 
          "<html><head><title>Thong tin</title><link rel='stylesheet' href='/css/screen.css' type='text/css' /><link rel='stylesheet' href='/css/reset.css' type='text/css' /></head><body>" + 
          data + "</body>";
    //Restore orignal HTML
    setTimeout(function(){
    	window.print();
    	document.body.innerHTML = oldPage;
    },100);
    
    return true;
}
