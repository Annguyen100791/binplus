var Nhanvien = {
	index:	function(){
	},
	add:	function(){
		$('#nhanvien-add div.tab-content').hide(); // Hide the content divs
		$('#nhanvien-add div.default-tab').show(); // Show the div with class "default-tab"
		$('#nhanvien-add li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#nhanvien-add .content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);
		
		// validate
		$('#form-nhanvien-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nhanvien][ho_ten]':	'required',
				'data[User][username]'	:	{
												required:	true,
												remote:	{
													url:	BIN.baseURL + 'users/check/username',
													type:	'post'
												}
											},
				'data[User][email]'		:	{
												required:	true,
												email:		true,
												remote:	{
													url:	BIN.baseURL + 'users/check/email',
													type:	'post'
												}
											},
				'data[User][password]'	:	{
												required:	true,
      											minlength: 6
											},
				'data[User][password1]'	:	{equalTo: '#password'}
			},
			messages:{
				'data[Nhanvien][ho_ten]':	'Vui lòng nhập vào Họ tên nhân viên',
				'data[User][username]'	:	'Tên đăng nhập này đã tồn tại. Vui lòng chọn Tên khác.',
				'data[User][email]'		:	'Email này đã tồn tại. Vui lòng nhập Email khác.',
				'data[User][password]'	:	'Mật khẩu ít nhất phải có 6 ký tự.',
				'data[User][password1]'	:	'Xác nhận Mật khẩu không đúng.'
			},
			submitHandler: function(form){
				if($('#username').val() == '')
				{
					alert('Vui lòng nhập vào Tên đăng nhập');
					$('#user-info').click();
					$('#username').focus();
				}else
					form.submit();
				
			}
		});	
		
		// ajax upload
		
		new AjaxUpload($('#btn-upload-image'), {
			action: BIN.baseURL + 'nhanvien/upload',
			name: 'data[Nhanvien][avatar]',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
					// extension is not allowed 
					alert('Only JPG, PNG, GIF files are allowed');
					return false;
				}
				$('#preview').html('<img src="/img/circle_ball.gif">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				$('#preview').text('');
				//Add uploaded file to list
				
				var jsonObj = eval('(' + response + ')');
				
				if(jsonObj.success)
				{
					$('#preview').html('<img src="' + $('#avatar_path').val() + jsonObj.filename + '"><input type="hidden" name=data[Nhanvien][anh_the] value="' + jsonObj.filename +'">');
				} else{
					alert(jsonObj.message);
				}
			}
		});
		
		
		$('#chucdanh_id').change(function(){
			$.ajax({
					type:		'GET',
					url:		BIN.baseURL + 'chucdanh/getnhomquyen/' + $(this).val(),
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						$('#nhomquyen_id').val(result.nhomquyen_id);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
		});
		
		$('#btn-nhanvien-ds').click(function(){
			location = BIN.baseURL + 'nhanvien';
		});
		$('#ho_ten').focus();
	},
	
	edit:	function(){
		$('#nhanvien-edit div.tab-content').hide(); // Hide the content divs
		$('#nhanvien-edit div.default-tab').show(); // Show the div with class "default-tab"
		$('#nhanvien-edit li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#nhanvien-edit .content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);
		
		// validate
		$('#form-nhanvien-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nhanvien][ho_ten]':	'required',
				'data[User][email]'		:	{
												required:	true,
												email:		true
											},
				'data[User][password2]'	:	{equalTo: '#password1'}
			},
			messages:{
				'data[Nhanvien][ho_ten]':	'Vui lòng nhập vào Họ tên nhân viên',
				'data[User][email]'		:	'Email không hợp lệ.',
				'data[User][password2]'	:	'Xác nhận Mật khẩu không đúng.'
			}
		});	
		
		
		// ajax upload
		
		new AjaxUpload($('#btn-upload-image'), {
			action: BIN.baseURL + 'nhanvien/upload',
			name: 'data[Nhanvien][avatar]',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
					// extension is not allowed 
					alert('Only JPG, PNG, GIF files are allowed');
					return false;
				}
				$('#preview').html('<img src="/img/circle_ball.gif">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				$('#preview').text('');
				//Add uploaded file to list
				
				var jsonObj = eval('(' + response + ')');
				
				if(jsonObj.success)
				{
					$('#preview').html('<img src="' + $('#avatar_path').val() + jsonObj.filename + '"><input type="hidden" name=data[Nhanvien][anh_the] value="' + jsonObj.filename +'">');
				} else{
					alert(jsonObj.message);
				}
			}
		});
		
		$('#ho_ten').focus();
	},
	
	nhansu:	function(){
		$('#search-form').hide();
		
		$("#nhanvien-ds").dynatree({
			minExpandLevel:	2,
			imagePath: BIN.baseURL + "js/libs/dynatree/skin-custom/",
			checkbox: false,
			selectMode: 1,
			noLink:	false,
			debugLevel: 0,
			fx: { height: "toggle", duration: 200 },
			strings: {
				loading: "đang load dữ liệu …",
				loadError: "load dữ liệu bị lỗi!"
			},
			onPostInit:	function(isReloading, isError){
				$("#nhanvien-ds").dynatree("getRoot").visit(function(node){
					node.expand(true);
				});
			},
			onCustomRender: function(node) {
				// Render title as columns				
				var html = "<a class='dynatree-title' href='#'><div>";
				
				if(!node.data.isFolder && node.data.ten_chucdanh != undefined)
				{
					html += '<div class="td" style="width:260px">' + node.data.title + '</div>';
					html += '<div class="td" style="width:180px">' + node.data.ten_chucdanh + '</div>';
					html += '<div class="td" style="width:150px">' + node.data.email + '</div>';
					html += '<div class="td" style="width:80px">' + node.data.dien_thoai + '</div>';
					html += '<div class="td" style="width:80px">' + node.data.dien_thoai_noi_bo + '</div>';
					
					html += '<div class="td" style="width:50px">' + node.data.so_meg + '</div>';
				}else
				{
					html += '<div class="td">' + node.data.title + '</div>';
				}
				html += '<div style="clear:both"></div></div></a>';	
				//var hoanthien = '<div><div class="td" style="width:260px">Đơn vị</div><div class="td" style="width:180px">Chức danh</div><div class="td" style="width:150px">Email</div><div class="td" style="width:80px">Đt Di động</div><div class="td" style="width:80px">Đt nội bộ</div><div class="td" style="width:50px">Số MEG</div>				'+ html +'</div>';
				return html;
			},
			
			onClick: function(node, event) {
				if(node)
				{
					if(!node.data.isFolder)
					{
//						node.toggleSelect();
						var key = node.data.key;
						location = BIN.baseURL + 'nhanvien/view/' + key.substring(3, key.length);
					}
				}
			},
			onDblClick: function(node, event) {
//				node.toggleSelect();
			},
			initAjax:	{

					url:	BIN.baseURL + 'nhanvien/listnv/NhanSu.danhsach'
				}
			
		});
		
		$('#nhanvien-search').click(function(){
			$('#search-form').slideToggle("slow");
			$("#search-keyword").focus();
		});
		
		$('#nhanvien-un-select').click(function(){
			if($('img', this).attr('src') == '/img/icons/arrow_out.png')
			{
				$('img', this).attr('src', '/img/icons/arrow_in.png');
				$(this).attr('title', 'Click để thu hẹp danh sách');
				$("#nhanvien-ds").dynatree("getRoot").visit(function(node){
					node.expand(true);
				});
			}else
			{
				$('img', this).attr('src', '/img/icons/arrow_out.png');
				$(this).attr('title', 'Click để mở rộng danh sách');
				$("#nhanvien-ds").dynatree("getRoot").visit(function(node){
					node.expand(false);
				});
			}
		});
		
		//$("#search-keyword").autocomplete("/nhanvien/autocomplete", {"delay": 10, autoFill:false, minChars:1, onItemSelect:selectItem,"maxItemsToShow": 20}); 
		$('#search-keyword').select2({
		   minimumInputLength:1,
		   allowClear: true,
		   placeholder:	'Nhập vào Tên cần tìm',
		   ajax: {
				url: BIN.baseURL + 'nhanvien/autocomplete',
				dataType: 'json',
				quietMillis: 100,
				data: function (term, page) { // page is the one-based page number tracked by Select2
				 return {
					q: term, //search term
					page_limit: 10, // page size
					page: page // page number
				 };
				},
				results: function (data, page) {
				   return {results: data.results};
				}
			 }
		  }).on('change', function(e){
			 
			var node = $("#nhanvien-ds").dynatree("getTree").getNodeByKey(e.added.id);
			
			/*
			$("#nhanvien-ds").dynatree("getTree").getNodeByKey(e.added.id).select();
			$("#nhanvien-ds").dynatree("getTree").activateKey(e.added.id);
			*/
//			node.select();

			$("#search-keyword").select2('data', null)
			
			node.makeVisible();
			$("#nhanvien-ds").dynatree("getTree").activateKey(e.added.id);
			
			$('#nhanvien-ds ul').animate({
				scrollTop: $(node.li).offset().top - $('#nhanvien-ds ul').offset().top + $('#nhanvien-ds ul').scrollTop()
				}, 'slow');
				
			
		});
	},
	view:	function(){
		$('#nhanvien-add div.tab-content').hide(); // Hide the content divs
		$('#nhanvien-add div.default-tab').show(); // Show the div with class "default-tab"
		$('#nhanvien-add li a.default-tab').addClass('current'); // Set the class of the default tab link to "current"
		
		$('#nhanvien-add .content-box-tabs li a').click( //When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('rel'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);
		$('#form-nhanvien-add input,select,textarea').attr('disabled', true);
	}
	/*,
	selectItem:	function(li) {
		if(li == null)	return alert('Không tìm thấy Nhân viên này.');
		//$("#nhanvien-ds").dynatree("getTree").getNodeByKey(li.data).select();
		$("#nhanvien-ds").dynatree("getTree").activateKey(li.data);
		$("#search-keyword").val('');
		$("#search-keyword").focus();
	}
	*/
};

$(document).ready(function(){
	BIN.executeFunctionByName(Nhanvien, params.action, null);
});