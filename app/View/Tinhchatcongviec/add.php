<?php
	echo $this->Form->create('Tinhchatcongviec', array('id' => 'form-tinhchatcongviec-add'));
?>
    <div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_tinhchat', array('label'	=>	'Tên tính chất công việc <span style="color:red">*</span>',
														  'id'		=>	'ten_tinhchat',
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
		echo $this->Form->input('enabled', array('label'	=>	'Tình trạng hiển thị <span style="color:red">*</span>',
													  'id'		=>	'enabled',
													  'class'	=>	'text-input',
													  'options'	=>	array('1'	=>	'Có', '0'	=>	'Không'),
													  ));
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
		$('#form-tinhchatcongviec-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Tinhchatcongviec][ten_tinhchat]'	:	'required'
			},
			messages:{
				'data[Tinhchatcongviec][ten_tinhchat]'	:	'Vui lòng nhập vào tên Tên tính chất công việc'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$(form).attr('action'),
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-tinhchatcongviec-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="tinhchatcongviec/index" data-target="tinhchatcongviec-list">');
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