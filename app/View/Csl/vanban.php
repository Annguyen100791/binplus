<?php
	echo $this->Form->create('Csl', array('id' => 'form-csl-add'));
?>
    <h4>Chuyển văn bản</h4>
    <p>
        <?php
			echo $this->Form->input('page', array('label'	=>	'Trang <span style="color:red">*</span>',
														  'id'		=>	'page',
														  'value'	=>	1,
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
		?>
    </p>
    <p>
        <?php
			echo $this->Form->input('limit', array('label'	=>	'Số record/ page <span style="color:red">*</span>',
														  'id'		=>	'limit',
														  'style'	=>	'width:97.5%',
														  'value'	=>	1000,
														  'class'	=>	'text-input'));
		?>
    </p>
	<p>
    	<label>Progressing : </label>
        <div id="status"></div>
        <input type="hidden" id="success" value="0">
    </p>
    <p style="text-align:right!important"><input class="button" type="submit" value="Chuyển dữ liệu" /></p>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		$('#form-csl-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Csl][page]'	:	{required	:	true, number: 	true},
				'data[Csl][limit]'		:	{required	:	true, number: 	true}
			},
			messages:{
				'data[Csl][page]'	:	'Vui lòng nhập vào tên Chức danh',
				'data[Csl][limit]' 		: 	'Giá trị không hợp lệ'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		root + 'csl/vanban',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-csl-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							var s = parseInt($('#success').val()) + parseInt(result.total);
							$('#success').val(s);
							$('#status').html('Đã upload thành công ' + s + '/20827');
							
							s = parseInt($('#page').val()) + 1;
							$('#page').val(s);
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
		
		$('#ten_chucdanh').focus();
	});
</script>