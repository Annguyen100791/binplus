<div>
<?php
	echo $this->Form->create('Nhanvien', array('id' => 'form-nhanvien-profile'));
	echo '<div class="formPanel">';
	echo '<p>';	
	echo $this->Form->input('email', array('label'	=>	'Email',
											  'id'		=>	'email',
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));
	echo '<BR>';
	echo $this->Form->input('dia_chi', array('label'	=>	'Địa chỉ',
											  'id'		=>	'dia_chi',
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));
	echo '<BR>';
	echo $this->Form->input('dien_thoai', array('label'	=>	'Điện thoại',
											  'id'		=>	'dien_thoai',
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));
	echo '<BR>';
	echo $this->Form->input('dien_thoai_noi_bo', array('label'	=>	'Điện thoại nội bộ',
											  'id'		=>	'dien_thoai_noi_bo',
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));
	echo '<BR>';
	echo $this->Form->input('dien_thoai_nha_rieng', array('label'	=>	'Điện thoại nhà riêng',
											  'id'		=>	'dien_thoai_nha_rieng',
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));
	echo '<BR>';
	echo $this->Form->input('signature', array('label'	=>	'Chữ ký tin nhắn',
											  'id'		=>	'signature',
											  'rows'	=>	3,
											  'style'	=>	'width:97.5%',
											  'class'	=>	'text-input'));		
	echo '</p>';	
	echo '</div>';
?>
	<div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php	
	echo $this->Form->end();
?>
</div>
<script>
	$(document).ready(function(){
		
		$('#form-nhanvien-profile').submit(function(){
			$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'nhanvien/profile',
					dataType:	'json',
					data:		$(this).serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$('#form-nhanvien-profile').find('.btn-close').first().click();
							alert(result.message);
							location.reload();
						}else
							alert(result.message);
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
			return false;
		});
	});
</script>