<?php 
	echo $this->Form->create('Congviec', array('id' => 'form-chitietcongviec')) ;
	echo $this->Form->hidden('id');
?>
<div class="formPanel">
<p style="padding-top:15px">	
	<label>Mức độ hoàn thành công việc : <span class="required"></span></label>
	<?php
        echo $this->Form->input('Congviec.mucdo_hoanthanh', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'options'	=>	$progress));
    ?>
</p>
<p>
	<label>Ghi chú : </label>
	<?php
        echo $this->Form->input('Congviec.ghi_chu', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'class'	=>	'text-input',
						  								 'style'	=>	'width:97.5%',
														 'rows'	=>	4,
														 'id'	=>	'ghi_chu'
														 ));
    ?>	
</p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Cập nhật</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php echo $this->Form->end();?>
