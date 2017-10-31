<?php 
	echo $this->Form->create('Congviec', array('id' => 'form-nguoinhanviec-edit', 'url' => array('controller' => 'congviec', 'action' => 'edit_process'))) ;
	echo $this->Form->hidden('id');
?>
<div>
	<p>
    	<label>Người giao việc </label>
        <input style="width:97.5%; font-weight:bold" class="text-input" disabled="disabled" value="<?php echo $this->Session->read('Auth.User.fullname') ?>" />
    </p>
    <p>
    	<label>Người nhận việc </label>
        <input style="width:97.5%; font-weight:bold" class="text-input" disabled="disabled" value="<?php echo $this->data['NguoiNhanviec']['full_name'] ?>" />
    </p>
    <p>
    	<div style="float:left; width:50%">
        	<label>Ngày bắt đầu <span class="required">*</span></label>
        	<input name="data[Congviec][ngay_batdau]" id="nn_ngay_batdau" style="width:140px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_batdau']) ?>"  />    
       </div>
       <div style="float:right;">
       		<label>Ngày kết thúc <span class="required">*</span></label>
	        <input name="data[Congviec][ngay_ketthuc]" id="nn_ngay_ketthuc" style="width:140px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_ketthuc']) ?>"/>
       </div>
       <div style="clear:both"></div>
    </p>
    <p>
    	<?php
			echo $this->Form->input('ten_congviec', array(
							'label'		=>	'Tên công việc <span class="required">*</span>',
							'div'		=>	false,
							'id'		=>	'ten_congviec',
							'style'		=>	'width:97.5%',
							'class'		=>	'text-input'
				));
		?>
    </p>
    <p>
    <?php
		echo $this->Form->input('noi_dung',
				array(
					'label'		=>	'Nội dung thực hiện <span class="required">*</span>',
					'div'		=>	false,
					'class'		=>	'text-input',
					'style'		=>	'width:97.5%',
					'rows'		=>	3
				)
		);
	?>
    </p>
    
    <p>
        <label><input type="checkbox" name="data[Congviec][giaoviec_tiep]" <?php echo (!empty($this->data['Congviec']['quyen_giaoviec'])) ? 'checked' : '' ?> /> Được phép giao việc cho người khác ?</label>
    </p>
    
</div>
    <p style="text-align:right!important"><input class="button" type="submit" value="Lưu thông tin" /></p>
</form>				

<script>

	$(document).ready(function(){
		$.datepicker.setDefaults( $.datepicker.regional["vi"] );
		$('#nn_ngay_batdau').datepicker({dateFormat: "dd-mm-yy"});
		$('#nn_ngay_ketthuc').datepicker({dateFormat: "dd-mm-yy"});
		
		$('#form-nguoinhanviec-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Congviec][ngay_batdau]'	:	'required',
				'data[Congviec][ngay_ketthuc]'	:	'required',
				'data[Congviec][ten_congviec]'	:	'required',
				'data[Congviec][noi_dung]'		:	'required'
			},
			messages:{
				'data[Congviec][ngay_batdau]'	:	'',
				'data[Congviec][ngay_ketthuc]'	:	'',
				'data[Congviec][ten_congviec]'	:	'',
				'data[Congviec][noi_dung]'		:	''
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$('#form-nguoinhanviec-edit').attr('action'),
					cache:		false,
					async:		false,
					dataType:	'json',
					data:		$('#form-nguoinhanviec-edit').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(document).trigger('close.facebox');
							location.reload();
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
		
		$('#btn-mark-deleted').click(function(){
			if(confirm('Bạn có muốn xóa người nhận công việc này ?'))
			{
				$.ajax({
					type:		'POST',
					url:		root + 'congviec/del_nguoinhanviec/' + $(this).attr('rel'),
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						if(result.success)
						{
							$(document).trigger('close.facebox');
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
		
		$('#facebox').css('left', $(window).width() / 2 - ($('#facebox .popup').width() / 2));
	});
</script>
