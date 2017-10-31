<?php
	echo $this->Form->create('Quyen', array('id' => 'form-quyen-add'));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_quyen', array('label'	=>	'Tên Quyền hạn <span style="color:red">*</span>',
														  'id'		=>	'ten_quyen',
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
    <p>
        <?php
			echo $this->Form->input('tu_khoa', array('label'	=>	'Từ khóa <span style="color:red">*</span>',
													  'id'		=>	'tu_khoa',
													  'style'	=>	'width:97.5%',
													  'class'	=>	'text-input'));
		?>
    </p>
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
		$('#form-quyen-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Quyen][ten_quyen]'	:	'required',
				'data[Quyen][tu_khoa]'		:	{
													required:	true,
													remote:	{
														url:	'/quyen/checkkey',
														type:	'post'
													}
												}
			},
			messages:{
				'data[Quyen][ten_quyen]'	:	'Vui lòng nhập vào tên Chức danh',
				'data[Quyen][tu_khoa]' 		: 	'Từ khóa này đã tồn tại'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$('#form-quyen-add').attr('action'),
					dataType:	'json',
					data:		$('#form-quyen-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="quyen/index" data-target="quyen-list">');
							BIN.doListing();
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
	});
</script>