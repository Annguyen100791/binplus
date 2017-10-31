<?php
	echo $this->Form->create('User', array('id' => 'form-user-changepass'));
	echo '<div class="formPanel">';
	echo '<p>';	
	echo $this->Form->input('oldpassword', array(
												 'label'	=>	'Mật khẩu cũ <span class="required">*</span>',
												 'class'	=>	'text-input',
												 'style'	=>	'width:97.5%',
												 'type'		=>	'password',
												 'id'		=>	'oldpassword',
												 'autocomplete'	=>	'off'
												 ));
	echo '</p>';	
	echo '<p>';	
	echo $this->Form->input('password1', array(
												 'label'	=>	'Mật khẩu mới (ít nhất 6 ký tự)<span class="required">*</span>',
												 'class'	=>	'text-input',
												 'style'	=>	'width:97.5%',
												 'type'		=>	'password',
												 'id'		=>	'password1',
												 'autocomplete'	=>	'off'
												 ));
	echo '</p>';	
	echo '<p>';	
	echo $this->Form->input('password2', array(
												 'label'	=>	'Xác nhận lại mật khẩu <span class="required">*</span>',
												 'class'	=>	'text-input',
												 'style'	=>	'width:97.5%',
												 'type'		=>	'password',
												 'id'		=>	'password2',
												 'autocomplete'	=>	'off'
												 ));
	echo '</p>';	
	echo '</div>';
?>
<div style="text-align:right!important" class="dialog-footer">
    <button class="button" type="submit">Đổi mật khẩu</button>
    <button class="button btn-close" type="button">Đóng</button>
</div>
<?php	
	echo $this->Form->end();
?>
<script>
	$(document).ready(function(){
		
		$('#form-user-changepass').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[User][oldpassword]'	:	'required',
				'data[User][password1]'	:	{
												required:	true,
      											minlength: 6
											},
				'data[User][password2]'	:	{equalTo: '#password1'}
			},
			messages:{
				'data[User][oldpassword]'	:	'Vui lòng nhập vào Mật khẩu cũ.',
				'data[User][password1]'		:	'Mật khẩu ít nhất phải có 6 ký tự.',
				'data[User][password2]'		:	'Xác nhận Mật khẩu không đúng.'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'users/changepass',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:		$('#form-user-changepass').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							alert(result.message);
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