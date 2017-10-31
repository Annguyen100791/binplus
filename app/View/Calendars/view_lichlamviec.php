<?php
	echo $this->Form->create('Lichlamviec', array('id' => 'form-lichlamviec-view'));
?>
<div class="formPanel">
    	<BR />
        <?php
			echo $this->Form->input('tieu_de', array('label'	=>	'Tiêu đề',
													  'id'		=>	'tieu_de',
													  'div'		=>	true,
													  'style'	=>	'width:97.5%',
													  'class'	=>	'text-input'));
		?>
        <BR />
    	<?php
			echo $this->Form->input('noi_dung', array('label'	=>	'Nội dung',
												  'id'		=>	'noi_dung',
												  'div'		=>	true,
												  'class'	=>	'text-input',
												  'rows'	=>	2));
		?>
        <BR />
    	<?php
			echo $this->Form->input('ngayghinho', array('label'	=>	'Thời gian',
												  'id'		=>	'ghinho',
												  'div'		=>	true,
												  'style'	=>	'width:97.5%; font-weight:bold',
												  'class'	=>	'text-input',
												  'value'	=>	$this->Time->format('H:i d-m-Y', $this->data['Lichlamviec']['ngay_ghinho'])));
		?>
        <BR />
    	<?php
			echo $this->Form->input('dia_diem', array('label'	=>	'Địa điểm',
												  'id'		=>	'dia_diem',
												  'div'		=>	true,
												  'style'	=>	'width:97.5%',
												  'class'	=>	'text-input'));
		?>
        <BR />
    	<?php
			echo $this->Form->input('thanhphan_thamdu', array('label'	=>	'Thành phần tham dự',
												  'id'		=>	'thanhphan_thamdu',
												  'div'		=>	true,
												  'rows'	=>	3,
												  'style'	=>	'width:97.5%',
												  'class'	=>	'text-input'));
		?>
        <BR />
        <div>
        <div style="float:left; width:48%">
    	<?php
			echo $this->Form->input('NguoiNhap.full_name', array('label'	=>	'Người nhập',
												  'id'		=>	'full_name',
												  'div'		=>	true,
												  'style'	=>	'width:97.5%',
												  'class'	=>	'text-input'));
		?>
        
        </div>
        <div style="float:right; width:48%">
        <?php
			echo $this->Form->input('ngay_nhap1', array('label'	=>	'Ngày nhập',
												  'id'		=>	'ngay_nhap1',
												  'div'		=>	true,
												  'style'	=>	'width:95%',
												  'value'	=>	$this->Time->format('d-m-Y H:i:s', $this->data['Lichlamviec']['ngay_nhap']),
												  'class'	=>	'text-input'));
		?>
        </div>
        <div style="clear:both"></div>
        </div>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		$('#form-lichlamviec-view input,textarea').attr('disabled', 'disabled');
	});
</script>