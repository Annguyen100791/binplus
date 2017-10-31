<form id="form-nhanvien-search">    
<div class="formPanel">
    <p>
    <?php
		
		echo $this->Form->input('Nhanvien.ten', array(
													  'label'	=>	'Tìm theo Tên',
													  'div'		=>	false,
													  'id'		=>	'ho_ten',
													  'class'	=>	'text-input',
													  'style'	=>	'width:97.5%'
													  ));
	?>
    </p>
    <p>
    <?php
		echo $this->Form->input('Nhanvien.chucdanh_id', array(
															  'label'	=>	'Chức danh',
															  'div'		=>	false,
															  'class'	=>	'text-input',
															  'style'	=>	'width:99.5%',
															  'options'	=>	$chucdanh,
															  'empty'	=>	' .. Chức danh .. '
															  ));
	?>
    </p>
    <p>
    <?php
		echo $this->Form->input('Nhanvien.phong_id', array(
														  'label'	=>	'Phòng ban/ Đơn vị',
														  'div'		=>	false,
														  'class'	=>	'text-input',
														  'style'	=>	'width:99.5%',
														  'options'	=>	$phong,
														  'empty'	=>	' .. Phòng ban/ Đơn vị .. '
														  ));
	?>
    </p>
</div>    
<div style="text-align:right!important" class="dialog-footer">
    <button class="button" type="submit">Tìm kiếm</button>
    <button class="button btn-close" type="button">Đóng</button>
</div>
<?php
	echo $this->Form->end();
?>
<script>
	$(document).ready(function(){
		
		$('#form-nhanvien-search').submit(function(){
			var $this = this;
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'nhanvien/',
				cache:		false,
				async:		false,
				data:		$('#form-nhanvien-search').serialize(),
				success:	function(result)
				{
					$($this).find('.btn-close').first().click();
					$('#nhanvien-list').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
		$('#ho_ten').focus();
	});
</script>