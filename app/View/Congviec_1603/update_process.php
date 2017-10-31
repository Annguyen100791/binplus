<?php 
	echo $this->Form->create('Chitietcongviec', array('id' => 'form-chitietcongviec')) ;
	echo $this->Form->hidden('id');
?>
<div class="formPanel">
<p>	
	<label>Mức độ hoàn thành công việc : <span class="required"></span></label>
	<?php
        echo $this->Form->input('Chitietcongviec.mucdo_hoanthanh', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'options'	=>	$progress));
    ?>
</p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Cập nhật</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php echo $this->Form->end();?>