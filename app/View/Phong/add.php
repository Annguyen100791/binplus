<?php
	echo $this->Form->create('Phong', array('id' => 'form-phong-add'));
?>
<div class="formPanel">
    <p>
    <?php
		echo $this->Form->input('loai_donvi', array('label'	=>	'Loại đơn vị <span style="color:red">*</span>',
														  'id'		=>	'loai_donvi',
														  'options'	=>	$loai_donvi,
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
	?>
    <BR />
    <?php
		echo $this->Form->input('ten_phong', array('label'	=>	'Tên Phòng ban/ Đơn vị <span style="color:red">*</span>',
														  'id'		=>	'ten_phong',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
	?>
    <BR />
    <?php
		echo $this->Form->input('thuoc_phong', array('label'	=>	'Thuộc phòng ban/ đơn vị ',
														  'id'		=>	'thuoc_phong',
														  'style'	=>	'width:100%',
														  'class'	=>	'text-input',
														  'options'	=>	$ds,
														  'empty'	=>	''));
	?>
    <BR />
    <?php
		echo $this->Form->input('chuc_nang', array('label'	=>	'Chức năng',
														  'id'		=>	'chuc_nang',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
	?>
    <BR />
    <?php
		echo $this->Form->input('dia_chi', array('label'	=>	'Địa chỉ',
														  'id'		=>	'dia_chi',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
	?>
    <BR />
    <?php
		echo $this->Form->input('dien_thoai', array('label'	=>	'Điện thoại',
														  'id'		=>	'dien_thoai',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
	?>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
</form>				

<script>
	$(document).ready(function(){
		$('#form-phong-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Phong][loai_donvi]'		:	'required',
				'data[Phong][ten_phong]'		:	'required'
			},
			messages:{
				'data[Phong][loai_donvi]'		:	'Vui lòng nhập vào loại đơn vị',
				'data[Phong][ten_phong]'		:	'Vui lòng nhập vào tên phòng / đơn vị'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'phong/add',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-phong-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="phong/index" data-target="phong-list">');
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