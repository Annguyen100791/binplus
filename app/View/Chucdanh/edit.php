<?php
	echo $this->Form->create('Chucdanh', array('id' => 'form-chucdanh-add'));
	echo $this->Form->hidden('id');
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_chucdanh', array('label'	=>	'Tên Chức danh <span style="color:red">*</span>',
														  'id'		=>	'ten_chucdanh',
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
			echo $this->Form->input('nhomquyen_id', array('label'	=>	'Nhóm quyền mặc định <span style="color:red">*</span>',
														  'id'		=>	'nhomquyen_id',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input',
														  'empty'	=>	''));
		?>
    </p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end();
?>

<script>
	$(document).ready(function(){
		$('#form-chucdanh-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Chucdanh][ten_chucdanh]'	:	'required',
				'data[Chucdanh][thu_tu]'		:	{required	:	true, number: 	true}
			},
			messages:{
				'data[Chucdanh][ten_chucdanh]'	:	'Vui lòng nhập vào tên Chức danh',
				'data[Chucdanh][thu_tu]' 		: 	'Giá trị không hợp lệ'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'chucdanh/edit',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-chucdanh-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
							BIN.doUpdate('<a href="chucdanh/index" data-target="chucdanh-list">');
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