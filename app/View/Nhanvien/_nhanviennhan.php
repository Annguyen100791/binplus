<?php
	echo $this->Html->css(array(
				'/js/libs/dynatree/skin/ui.dynatree',
				'/js/libs/dynatree/skin-custom/custom.css'
		));
	echo $this->Html->script(array(
		'libs/dynatree/jquery.dynatree',
		));
?>
<div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->
    <div style="float:left">
        <h3><?php echo $this->Html->image('icons/user.png', array('align' => 'left')) ?>&nbsp; Người nhận</h3>
    </div>
    <div style="float:right;">
        <ul class="shortcut-buttons-set" style="padding:6px 0px!important">
        	<li><a class="shortcut-button" href="javascript:void(0)" title="Gửi cho nhóm nhân sự" id="nhanvien-group"><img src="/img/icons/group.png"/ title="Gửi cho nhóm nhân sự đã tạo sẵn"></a></li>
        	<li><a class="shortcut-button" href="javascript:void(0)" title="Gửi cho cán bộ quản lý" id="nhanvien-nguoiquanlynhan"><img src="/img/icons/user_suit.png" title="Gửi cho người quản lý" /></a></li>
            <li><a class="shortcut-button" href="javascript:void(0)" title="Gửi cho từng người" id="nhanvien-nhanviennhan"><img src="/img/icons/user_go.png"/ title="Gửi đến từng cán bộ / nhân viên"></a></li>
            <li><a class="shortcut-button" href="javascript:void(0)" title="Tìm kiếm nhanh trong danh sách" id="nhanvien-search"><img src="/img/icons/zoom.png"/></a></li>
            <li><a class="shortcut-button" href="javascript:void(0)" title="CLick để mở rộng danh sách" id="nhanvien-un-select"><img src="/img/icons/arrow_out.png"/></a></li>
            
        </ul>
        <div style="clear:both"></div>
    </div>
    <div style="clear:both"></div>
</div>

<div class="content-box-content">
	
      <div class="tab-content default-tab" style="overflow:auto" id="tinnhan_rightcontent">
      		<div id="search-form" style="padding-bottom:10px">
                <input type="text" class="text-input" id="search-keyword" style="width:100%" />
            </div>
            
            <div id="group-form" style="padding-bottom:10px">
            	<select class="text-input" id="group-keyword" style="width:100%">
            	<?php 
					if(!empty($groups))
					{
						printf("<option value=''> -- Chọn nhóm người nhận-- </option>");
						foreach($groups as $k => $v) 
							printf("<option value='%s'>%s</option>", $k, $v);
					}
					else
						printf("<option value='' style='font-style:italic'>Hiện tại chưa thiết lập nhóm nào</option>");
				?>
                </select>
            </div>
            
            <div id="nhanvien-ds">
            </div>	

      </div>
</div>  
<script>
	$('#search-form').hide();
	$('#group-form').hide();
	$(document).ready(function(){
		$("#nhanvien-ds").dynatree({
			minExpandLevel:	2,
			imagePath: BIN.baseURL + "js/libs/dynatree/skin-custom/",
			checkbox: true,
			selectMode: 3,
			debugLevel: 0,
			autoFocus: false,
			noLink:	false,
			fx: { height: "toggle", duration: 200 },
			strings: {
				loading: "đang load dữ liệu …",
				loadError: "load dữ liệu bị lỗi!"
			},
			onPostInit:	function(isReloading, isError){
				if($('#nv_selected').val() != '')
				{
					var arr = $('#nv_selected').val().split(',');
					for(var i = 0; i < arr.length; i++)
					{
						if($("#nhanvien-ds").dynatree("getTree").getNodeByKey('nv-' + arr[i]))
							$("#nhanvien-ds").dynatree("getTree").getNodeByKey('nv-' + arr[i]).select();
						$("#nhanvien-ds").dynatree("getTree").activateKey('nv-' + arr[i]);
					}
				}
			},
			onSelect: function(select, node) {
				var selKeys = $.map(node.tree.getSelectedNodes(), function(node){
					if(!node.data.isFolder)
					{
						var arr = (node.data.key).split('-');
						return arr[1];
					}
					else
						return null;
				});
				$('#nv_selected').val(selKeys.join(","));
			},
			onClick: function(node, event) {
				if( node.getEventTargetType(event) == "title" )
				{
					if(!node.data.isFolder)
					{
						node.toggleSelect();
					}
				}
			},
			onKeydown: function(node, event) {
				if( event.which == 32 ) {
					node.toggleSelect();
					return false;
				}
			},
			// The following options are only required, if we have more than one tree on one page:
//				initId: "treeData",
//			cookieId: "dynatree-Cb3",
//			idPrefix: "dynatree-Cb3-",
			initAjax:	{
					url:	BIN.baseURL + 'nhanvien/listnv/<?php echo $action ?>'
				}
			
		});
		
		$('#nhanvien-group').click(function(){
			$('#group-form').slideToggle("slow");
			$("#group-keyword").focus();
		});
		
		$('#group-keyword').change(function(){
			var group_id = $(this).val();
			if(group_id)
			{
				$.ajax({
					type:	'GET',
					dataType: 'json',
					url:	BIN.baseURL + 'groups/get_group/' + group_id,
					cache:	false,
					success: function(data){
						$("#nhanvien-ds").dynatree("getRoot").visit(function(node){
							var key = (node.data.key).split('-');
							if($.inArray(key[1], data) >= 0)
							{
								$(node.li).css('display', '');
								node.data.hideCheckbox = false;
								node.data.unselectable = false;
								node.data.tooltip = 'Click để chọn / bỏ chọn';
								node.select(true);
								node.activate();
							}else
							{
								node.data.hideCheckbox = true;
								node.data.unselectable = true;
								//$(node.li).css('display', 'none');
								//node.render(true);
								if(!node.data.isFolder)
									$(node.li).css('display', 'none');
							}
						});
						$("#nhanvien-ds").dynatree("getTree").redraw();
					}
				});
			}
		});
		
		$('#nhanvien-nguoiquanlynhan').click(function(){
			$('#group-form').hide();
			 $("#nhanvien-ds").dynatree("getRoot").visit(function(node){
					if(node.data.nguoi_quanly == 0)
					{
						node.data.hideCheckbox = true;
						node.data.unselectable = true;
						$(node.li).css('display', 'none');
						//node.render(true);
					}else
					{
						$(node.li).css('display', '');
						node.data.hideCheckbox = false;
						node.data.unselectable = false;
						node.expand();
					}
				});
			$("#nhanvien-ds").dynatree("getTree").redraw();
			return false;
		});
		
		$('#nhanvien-nhanviennhan').click(function(){
			$('#group-form').hide();
			$("#nhanvien-ds").dynatree("getRoot").visit(function(node){
				node.data.hideCheckbox = false;
				node.data.unselectable = false;
				$(node.li).css('display', '');
				node.data.tooltip = 'Click để chọn/ bỏ chọn';
			});
			$("#nhanvien-ds").dynatree("getTree").redraw();
			return false;
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
		
		$('#nhanvien-search').click(function(){
			$('#search-form').slideToggle("slow");
			$("#search-keyword").focus();
		});
		if($('#tinnhan_leftcontent').length > 0)
			$('.dynatree-container').css("height", $('#tinnhan_leftcontent').height());
		else if($('#vanban_leftcontent').length > 0)
			$('.dynatree-container').css("height", $('#vanban_leftcontent').height());
		
		
		BIN.tooltip();
		
		$("#search-keyword").keypress(function(e){
			 var k=e.keyCode || e.which;
			 if(k==13){
				 e.preventDefault();
			 }
		 });
		 
		//$("#search-keyword").autocomplete("/nhanvien/autocomplete", {"delay": 10, autoFill:false, minChars:1, onItemSelect:selectItem,"maxItemsToShow": 20}); 
		
		$('#group-keyword').select2();
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
					page: page, // page number
				 };
				},
				results: function (data, page) {
				   return {results: data.results};
				}
			 }
		  }).on('change', function(e){
			 
			$("#nhanvien-ds").dynatree("getTree").getNodeByKey(e.added.id).select();
			$("#nhanvien-ds").dynatree("getTree").activateKey(e.added.id);
			$("#search-keyword").select2('data', null)
			$("#search-keyword").focus();
			
			//console.log(e.added.id);
		});
	});
	
	function selectItem(li) {
		if(li == null)	return alert('Không tìm thấy Nhân viên này.');
		
	}

</script>