<?php
	echo $this->Form->create('Lichlamviec', array('id' => 'form-lichlamviec-edit'));
	echo $this->Form->hidden('id');
?>
<div class="formPanel">
    <p>
        <?php
			echo $this->Form->input('tieu_de', array('label'	=>	'Tiêu đề <span style="color:red">*</span>',
													  'id'		=>	'tieu_de',
													  'style'	=>	'width:97.5%',
													  'class'	=>	'text-input'));
		?>
   	<br />
    	<?php
			echo $this->Form->input('noi_dung', array('label'	=>	'Nội dung <span class="required">*</span>',
												  'id'		=>	'noi_dung',
												  'class'	=>	'text-input',
												  'rows'	=>	1));
		?>
    <br />
    	<?php
			echo $this->Form->input('dia_diem', array('label'	=>	'Địa điểm',
														  'id'		=>	'dia_diem',
														  'style'	=>	'width:97.5%',
														  'class'	=>	'text-input'));
		?>
    <br />
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
    <p>
    	<?php
			echo $this->Form->input('thanhphan_thamdu', array('label'	=>	'Thành phần tham dự',
														  'id'		=>	'thanhphan_thamdu',
														  'style'	=>	'width:97.5%',
														  'rows'	=>	2,
														  'class'	=>	'text-input'));
		?>
    </p>
    <div style="padding-top:5px">
    	<div style="float:left">
        	<?php
				echo $this->Form->input('pham_vi', array('label'	=>	'Phạm vi hiển thị <span class="required">*</span>',
												  'id'		=>	'chon_pham_vi',
												  'class'	=>	'text-input',
												  'options'	=>	$pham_vi));
			?>
        </div>
        <div style="float:right; width:300px" id="phamvi_phong">
			<?php
			/*
                echo $this->Form->input('phong_id', array('label'		=>	'Chọn phòng <span class="required">*</span>',
                                                              'id'		=>	'phong_id',
                                                              'options'	=>	$ds_phong,
                                                              'style'	=>	'width:97.5%',
                                                              'class'	=>	'text-input'));
			*/															  
            ?>
        </div>
        <div style="float:right; width:300px" id="phamvi_group">
			<?php
			/*
                echo $this->Form->input('group_id', array('label'		=>	'Chọn nhóm làm việc <span class="required">*</span>',
                                                              'id'		=>	'group_id',
                                                              'options'	=>	$groups,
                                                              'style'	=>	'width:97.5%',
                                                              'class'	=>	'text-input'));
			*/															  
            ?>
        </div>
        <div style="clear:both"></div>
    </div>
    
    <p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <input class="button" type="button" value="Xóa Lịch làm việc này" id="btn-lich-del" rel="<?php echo $this->data['Lichlamviec']['id'] ?>" />
        <button class="button btn-close" type="button">Đóng</button>
    </div>
        
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		
		$('#btn-lich-del').click(function(){
			if(confirm('Bạn có muốn xóa lịch làm việc này không ?'))
			{
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'calendars/del_lichlamviec',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	{id: $(this).attr('rel')},
					success:	function(result)
					{
						if(result.success)
						{
							alert(result.message);
							$('#form-lichlamviec-edit').find('.btn-close').first().click();
							$('#calendar').fullCalendar('refetchEvents');
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
		$('#form-lichlamviec-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Lichlamviec][tieu_de]'	:	'required',
				'data[Lichlamviec][noi_dung]'	:	'required'
			},
			messages:{
				'data[Lichlamviec][tieu_de]'	:	'Vui lòng nhập vào Tiêu đề',
				'data[Lichlamviec][noi_dung]' 	: 	'Vui lòng nhập vào Nội dung'
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'calendars/edit_lichlamviec',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-lichlamviec-edit').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$('#form-lichlamviec-edit').find('.btn-close').first().click();
							$('#calendar').fullCalendar('refetchEvents');
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
		
		$('#phamvi_phong').hide();
		$('#phamvi_group').hide();
		
	});
</script>