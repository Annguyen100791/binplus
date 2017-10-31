<?php
	echo $this->Form->create('Tinhchatvanban', array('id' => 'form-tinhchatvanban-edit', 'url' => '/tinhchatvanban/edit'));
	echo $this->Form->hidden('id');
?>
    <div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_tinhchat', array('label'	=>	'Tên tính chất văn bản <span style="color:red">*</span>',
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
		$('#form-tinhchatvanban-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Tinhchatvanban][ten_tinhchat]'	:	'required'
			},
			messages:{
				'data[Tinhchatvanban][ten_tinhchat]'	:	'Vui lòng nhập vào tên Tên tính chất văn bản'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$(form).attr('action'),
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-tinhchatvanban-edit').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="tinhchatvanban/index" data-target="tinhchatvanban-list">');
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