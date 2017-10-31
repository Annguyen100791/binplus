<?php
	echo $this->Form->create('Loaivanban', array('id' => 'form-loaivanban-add', 'url' => '/loaivanban/add'));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_loaivanban', array('label'	=>	'Tên loại văn bản <span style="color:red">*</span>',
														  'id'		=>	'ten_loaivanban',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
		?>
    </p>
    <p>
    	<?php
			echo $this->Form->input('mo_ta', array('label'	=>	'Mô tả',
												  'id'		=>	'mo_ta',
												  'class'	=>	'text-input',
												  'rows'	=>	2));
		?>
    </p>
    
    <div>
    	<div style="float:left; width:50%">
            <?php
				echo $this->Form->input('chieu_di', array('label'	=>	'Chiều đi <span style="color:red">*</span>',
															  'id'		=>	'chieu_di',
															  'class'	=>	'text-input',
															  'options'	=>	$chieu_di,
															  ));
			?>
        </div>
        <div style="float:right; width:50%">
            <?php
				echo $this->Form->input('enabled', array('label'	=>	'Tình trạng hiển thị <span style="color:red">*</span>',
															  'id'		=>	'enabled',
															  'class'	=>	'text-input',
															  'options'	=>	array('1'	=>	'Có', '0'	=>	'Không'),
															  ));
			?>
        </div>
        <div style="clear:both"></div>
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
		$('#form-loaivanban-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Loaivanban][ten_loaivanban]'	:	'required',
				'data[Loaivanban][thu_tu]'		:	{required	:	true, number: 	true}
			},
			messages:{
				'data[Loaivanban][ten_loaivanban]'	:	'Vui lòng nhập vào tên Tên loại văn bản',
				'data[Loaivanban][thu_tu]' 		: 	'Giá trị không hợp lệ'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$(form).attr('action'),
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-loaivanban-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="tintuc/index" data-target="tintuc-list">');
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