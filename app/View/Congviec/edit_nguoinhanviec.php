<?php 
	echo $this->Form->create('Nguoinhanviec', array('id' => 'form-nguoinhanviec-edit', 'url' => array('controller' => 'congviec', 'action' => 'edit_nguoinhanviec'))) ;
	echo $this->Form->hidden('id', array('value' => $this->data['id']));
	echo $this->Form->hidden('nguoinhan_id', array('value' => $this->data['nguoinhan_id']));
?>
<div class="formPanel">
	<p>
    	<label>Người nhận việc </label>
        <input style="width:97.5%; font-weight:bold" class="text-input" disabled="disabled" value="<?php echo $this->data['full_name'] ?>" />
    </p>
    <p>
    	<?php
			echo $this->Form->input('ten_congviec', array(
							'label'		=>	'Tên công việc <span class="required">*</span>',
							'div'		=>	false,
							'id'		=>	'ten_congviec',
							'style'		=>	'width:97.5%',
							'class'		=>	'text-input',
							'value'		=>	$this->data['ten_congviec']
				));
		?>
    </p>
    <p>
    	<div style="float:left; width:50%">
            <label>Ngày bắt đầu <span class="required">*</span></label>
        	<input name="data[Nguoinhanviec][ngay_batdau]" id="nn_ngay_batdau" style="width:90%" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['ngay_batdau']) ?>"  />    	
       </div>
       <div style="float:right;width:50%">
            <label>Ngày hoàn thành <span class="required">*</span></label>
	        <input name="data[Nguoinhanviec][ngay_ketthuc]" id="nn_ngay_ketthuc" style="width:95%" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['ngay_ketthuc']) ?>"/>
       </div>
       <div style="clear:both"></div>
    </p>
    
    <p>
        <label>Nội dung thực hiện <span class="required">*</span></label>
        <textarea class="text-input textarea wysiwyg" name="data[Nguoinhanviec][noi_dung]" id="nn_noidung" style="width:97.5%" rows="3"><?php echo $this->data['noi_dung'] ?></textarea>
    </p>
    
    <p>
        <label><input type="checkbox" name="data[Nguoinhanviec][giaoviec_tiep]" <?php echo (!empty($this->data['giaoviec_tiep'])) ? 'checked' : '' ?> /> Được phép giao việc cho người khác ?</label>
    </p>
    
</div>
 	<div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu thông tin</button>
        <input class="button" type="button" value="Xóa người nhận việc" id="btn-mark-deleted" rel="<?php echo $this->data['id'] ?>" />
        <button class="button btn-close" type="button">Đóng</button>
    </div>
</form>				

<script>

	$(document).ready(function(){
		$('#nn_ngay_batdau').datepicker({
			dateFormat: "dd-mm-yy",
			minDate:	$('#ngay_batdau').datepicker('getDate'),
			maxDate: 	$('#ngay_ketthuc').datepicker('getDate'),
			onSelect:	function(){
				var newDate = $(this).datepicker('getDate');
			    if (newDate) { // Not null
					$('#nn_ngay_ketthuc').datepicker('setDate', newDate).datepicker('option', 'minDate', newDate);
			    }
			}
		});
		$('#nn_ngay_ketthuc').datepicker({
			dateFormat: "dd-mm-yy",
			minDate: $('#nn_ngay_batdau').datepicker('getDate'),
			maxDate: $('#ngay_ketthuc').datepicker('getDate')
		});
		
		$('#form-nguoinhanviec-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nguoinhanviec][ngay_batdau]'	:	'required',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'required',
				'data[Nguoinhanviec][ten_congviec]'		:	'required',
				'data[Nguoinhanviec][noi_dung]'		:	'required'
			},
			messages:{
				'data[Nguoinhanviec][ngay_batdau]'	:	'',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'',
				'data[Nguoinhanviec][ten_congviec]'	:	'',
				'data[Nguoinhanviec][noi_dung]'		:	''
			},
			submitHandler: function(form){
				$.ajax({
					type:		'POST',
					url:		$('#form-nguoinhanviec-edit').attr('action'),
					dataType:	'json',
					data:		$('#form-nguoinhanviec-edit').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$(form).find('.btn-close').first().click();
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
		
		$('#btn-mark-deleted').click(function(){
			if(confirm('Bạn có muốn xóa người nhận công việc này ?'))
			{
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'congviec/del_nguoinhanviec/' + $(this).attr('rel'),
					dataType:	'json',
					success:	function(result)
					{
						if(result.success)
						{
							$('#form-nguoinhanviec-edit').find('.btn-close').first().click();
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
	});
</script>
