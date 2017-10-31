<?php
	echo $this->Form->create('Lichlamviec', array('id' => 'form-lichlamviec-edit'));
?>
    <h4>Lịch làm việc</h4>
    <p>
        <?php
			echo $this->Form->input('tieu_de', array('label'	=>	'Tiêu đề <span style="color:red">*</span>',
													  'id'		=>	'tieu_de',
													  'style'	=>	'width:97.5%',
													  'class'	=>	'text-input'));
		?>
    </p>
    <p>
    	<?php
			echo $this->Form->input('noi_dung', array('label'	=>	'Nội dung <span class="required">*</span>',
												  'id'		=>	'noi_dung',
												  'class'	=>	'text-input',
												  'rows'	=>	1));
		?>
    </p>
    <p>
    	<label>Thời điểm <span class="required">*</span></label>
    	<?php
			$d = explode(" ", $this->data['Lichlamviec']['ngay_ghinho']);
			$date = explode("-", $d[0]);
			echo $this->Form->day('ngay_ghinho', array('empty' => false, 'value' => $date[2]));
			echo "-";
			echo $this->Form->month('ngay_ghinho', array('empty' => false, 'value' => $date[1]));
			echo "-";
			echo $this->Form->year('ngay_ghinho', 2000,null, array('empty' => false, 'value' => $date[0]));
			echo "&nbsp;&nbsp;&nbsp;";
			$time = explode(":", $d[1]);
			echo $this->Form->hour('ngay_ghinho', true, array('empty' => false, 'value' => $time[0]));
			echo ":";
			echo $this->Form->minute('ngay_ghinho', array('empty' => false, 'value' => $time[1]));
		?>
    </p>
    <div style="padding-top:5px">
    	<div style="float:left; width:50%">
        	<?php
				echo $this->Form->input('pham_vi', array('label'	=>	'Phạm vi <span class="required">*</span>',
												  'id'		=>	'noi_dung',
												  'class'	=>	'text-input',
												  'options'	=>	$pham_vi));
			?>
        </div>
        <div style="float:right; width:50%">
            <label>Tình trạng hiển thị <span style="color:red">*</span></label>
            <?php
				echo $this->Form->input('enabled', array('label'	=>	false, 'checked' => 'checked'));
			?>
        </div>
        <div style="clear:both"></div>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
	});
</script>