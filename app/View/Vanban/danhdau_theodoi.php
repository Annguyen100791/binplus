<?php
	echo $this->Form->create('Vanban', array('id' => 'form-vanban-follow', 'url' => '/vanban/danhdau_theodoi'));
	echo $this->Form->hidden('vanban_id', array('value' => $id));
	echo $this->Form->hidden('type', array('value' => $type));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ghi_chu', array('label'	=>	'Ghi chú',
														  'id'		=>	'ghi_chu',
														  'rows'	=>	4,
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
		?>
    </p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Theo dõi văn bản</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		$('#form-vanban-follow').submit(function(){
			var target = '<?php echo empty($target) ? '' : $target ?>';
			$.ajax({
				type:	'POST',
				dataType: 'html',
				url:	$('#form-vanban-follow').attr('action'),
				data:	$('#form-vanban-follow').serialize(),
				success: function(data){
					$('#form-vanban-follow').find('.btn-close').first().click();
					if(target != '')
						$('#' + target).html(data);
				}
			});
			return false;
		});
	});
</script>