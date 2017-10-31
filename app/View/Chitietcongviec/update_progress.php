<?php 
	echo $this->Form->create('Chitietcongviec', array('id' => 'form-chitietcongviec')) ;
	echo $this->Form->hidden('id');
?>
<h4>Cập nhật mức độ hoàn thành công việc</h4>
<p>
	<label>Tên công việc : </label>
    <div style="padding:0 20px; color:green"><?php echo $this->data['Congviec']['ten_congviec'] ?></div>
</p>
<p>
	<label>Nội dung công việc : </label>
    <div style="padding:0 20px; color:green"><?php echo $this->data['Chitietcongviec']['noi_dung'] ?></div>
</p>
<p>
	<label>Ngày bắt đầu thực hiện :</label>
    <div style="padding:0 20px; color:green"><?php echo $this->Time->format('d-m-Y', $this->data['Chitietcongviec']['ngay_batdau']); ?></div>
</p>
<p>
	<label>Ngày kết thức :</label>
    <div style="padding:0 20px; color:green"><?php echo $this->Time->format('d-m-Y', $this->data['Chitietcongviec']['ngay_ketthuc']); ?></div>
</p>
<p>	
	<label>Mức độ hoàn thành công việc : <span class="required"></span></label>
	<?php
        echo $this->Form->input('mucdo_hoanthanh', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'options'	=>	$progress));
    ?>
</p>
<p style="text-align:right!important"><input class="button" type="submit" value="Lưu dữ liệu" /></p>
<?php echo $this->Form->end();?>