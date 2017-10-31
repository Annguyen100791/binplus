<?php
	echo $this->Html->script(
		array(
			  'libs/tinymce/tinymce.min'
			  )
	);
	echo $this->Form->create('Chinhsach', array('id' => 'form-chinhsach-edit', 'url' => '/chinhsach/edit'));
	echo $this->Form->hidden('id');
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('tieu_de', array('label'	=>	'Tiêu đề <span style="color:red">*</span>',
														  'id'		=>	'tieu_de',
														  'style'	=>	'width:99%',
														  'class'	=>	'text-input'));
		?>
    </p>
    <p>
    	<?php
			echo $this->Form->input('noi_dung', array('label'	=>	'Nội dung <span class="required">*</span>',
												  'id'		=>	'noi_dung',
												  'class'	=>	'text-input',
												  'rows'	=>	6));
		?>
    </p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <!--<button class="button btn-close" type="button">Đóng</button>-->
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	
	$(document).ready(function(){
		tinymce.init({
			selector: '#noi_dung',
			//height: 400,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser
		});
		$('#form-chinhsach-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Chinhsach][tieu_de]'		:	'required',
				'data[Chinhsach][noi_dung]'	:	'required'
			},
			messages:{
				'data[Chinhsach][tieu_de]'		:	'Vui lòng nhập vào Tiêu đề',
				'data[Chinhsach][noi_dung]' 	: 	'Vui lòng nhập vào Nội dung'
			},
			/*submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'tintuc/edit',
					dataType:	'json',
					data:	$('#form-tintuc-edit').serialize(),
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
			}*/
		});	
	});
</script>