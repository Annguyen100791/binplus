<?php 
	echo $this->Form->create('Congviec', array('id' => 'form-nguoinhanviec-add', 'url' => array('controller' => 'congviec', 'action' => 'add_process'))) ;
	echo $this->Form->hidden('parent_id', array('value' => $congviec_id));
?>
<div class="formPanel">
	<p>
    <?php
		echo $this->Form->input('nguoinhan_id', 
			array(
				  'label'	=>	'Người nhận việc <span class="required">*</span>',
				  'div'		=>	false,
				  'class'	=>	'text-input',
				  'style'	=>	'width:97.5%',
				  'id'		=>	'nguoinhan_id',
				  'options'	=>	$nv,
				  'empty'	=>	'-- chọn người nhận việc --'
				  )
		);
	?>
    </p>
    <p>
    	<div style="float:left; width:50%">
        	<label>Ngày bắt đầu <span class="required">*</span></label>
        	<input name="data[Congviec][ngay_batdau]" id="nn_ngay_batdau" style="width:120px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', time()) ?>"  />    
       </div>
       <div style="float:right;">
       		<label>Ngày kết thúc <span class="required">*</span></label>
	        <input name="data[Congviec][ngay_ketthuc]" id="nn_ngay_ketthuc" style="width:120px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', time()) ?>"/>
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
        <label><input type="checkbox" name="data[Congviec][giaoviec_tiep]" /> Được phép giao việc cho người khác ?</label>
    </p>
    
</div>
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Thêm vào danh sách</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
</form>				

<script>

	$(document).ready(function(){
		$('#nn_ngay_batdau').datepicker({dateFormat: "dd-mm-yy"});
		$('#nn_ngay_ketthuc').datepicker({dateFormat: "dd-mm-yy"});
		
		$('#form-nguoinhanviec-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nguoinhanviec][nguoinhan_id]' :	'required',
				'data[Nguoinhanviec][ngay_batdau]'	:	'required',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'required',
				'data[Nguoinhanviec][ten_congviec]'	:	'required',
				'data[Nguoinhanviec][noi_dung]'		:	'required'
			},
			messages:{
				'data[Nguoinhanviec][nguoinhan_id]' :	'',
				'data[Nguoinhanviec][ngay_batdau]'	:	'',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'',
				'data[Nguoinhanviec][ten_congviec]'	:	'',
				'data[Nguoinhanviec][noi_dung]'		:	''
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$('#form-nguoinhanviec-add').attr('action'),
					dataType:	'json',
					data:		$('#form-nguoinhanviec-add').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
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
