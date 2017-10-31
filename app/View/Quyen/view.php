<?php
	echo $this->Form->create('Quyen', array('id' => 'form-quyen-edit'));
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('ten_quyen', array('label'	=>	'Tên Quyền hạn',
														  'id'		=>	'ten_quyen',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input',
														  'value'	=>	$this->data['Quyen']['ten_quyen'],
														  'disabled'	=>	true));
		?>
    </p>
    <p>
    	<?php
			echo $this->Form->input('mo_ta', array('label'	=>	'Mô tả',
												  'id'		=>	'mo_ta',
												  'class'	=>	'text-input',
												  'rows'	=>	2,
												  'value'	=>	$this->data['Quyen']['mo_ta'],
												  'disabled'	=>	true));
		?>
    </p>
    <p>
        <?php
			echo $this->Form->input('tu_khoa', array('label'	=>	'Từ khóa',
													  'id'		=>	'tu_khoa',
													  'style'	=>	'width:97.5%',
													  'class'	=>	'text-input',
													  'value'	=>	$this->data['Quyen']['tu_khoa'],
													  'disabled'	=>	true));
		?>
    </p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end(null);
?>