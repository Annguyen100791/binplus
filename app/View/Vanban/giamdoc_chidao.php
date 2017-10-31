<?php
	echo $this->Form->create('Vanban', array('id' => 'form-vanban-follow', 'url' => '/vanban/giamdoc_chidao'));
	echo $this->Form->hidden('vanban_id', array('value' => $id));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('gd_chidao', array('label'	=>	'Chỉ đạo',
														  'id'		=>	'gd_chidao',
														  'rows'	=>	4,
														  'style'	=>	'width:97.5%',
														  'value'	=> $gd_chidao ,	
														  'class'	=>	'text-input'));
		?>
    </p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Chỉ đạo văn bản</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		$('#form-vanban-follow').submit(function(){
			$.ajax({
				type:	'POST',
				dataType: 'html',
				url:	$('#form-vanban-follow').attr('action'),
				data:	$('#form-vanban-follow').serialize(),
				success: function(data){
					$('#form-vanban-follow').find('.btn-close').first().click();
					$('#ghichu-update').text($('#gd_chidao').val());
					$('#ghichu-update-null').text('Giám đốc VTĐN chỉ đạo: ' + $('#gd_chidao').val());
				}
			});
			return false;
		});
	});
</script>