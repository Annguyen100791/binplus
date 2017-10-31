<?php
	echo $this->Html->css(array(
				'/js/libs/dynatree/skin/ui.dynatree',
				'/js/libs/dynatree/skin-custom/custom.css'
		));
	echo $this->Html->script(array(
		'libs/dynatree/jquery.dynatree',
		));
	$nv = Set::extract($this->data, '/GroupNhanvien/nhanvien_id');		
	echo $this->Form->create('Group', array('id' => 'form-group-add', 'url' => '/groups/edit'));
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(",", $nv)));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_nhom', array('label'	=>	'Tên nhóm <span style="color:red">*</span>',
														  'id'		=>	'ten_nhom',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
		?>
    </p>
    <label>Chọn thành viên trong nhóm <span style="color:red">*</span></label>
    <div style="overflow:auto; height:200px;  max-height:200px" id="tinnhan_rightcontent">
        <div id="nhanvien-ds">
        </div>	
    </div>
    
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
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
					url:	BIN.baseURL + 'nhanvien/listnv/NhanSu.group'
				}
			
		});
		
		$('#form-group-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Group][ten_nhom]'	:	'required'
			},
			messages:{
				'data[Group][ten_nhom]'	:	'Vui lòng nhập vào tên Tên loại văn bản'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$(form).attr('action'),
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-group-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="groups/index" data-target="groups-list">');
							BIN.doListing();
						}else
							alert(result.message);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
				return false;
			}
		});	
	});
</script>