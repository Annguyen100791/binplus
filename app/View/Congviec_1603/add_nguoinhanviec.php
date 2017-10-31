<?php 
	echo $this->Form->create('Nguoinhanviec', array('id' => 'form-nguoinhanviec-add')) ;
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => ''));
	//echo $this->Form->hidden('nn_tinhchat_id', array('id' => 'nn_tinhchat_id'));
?>
<style>
ul.dynatree-container{
	height: 240px!important; overflow:auto;
}
</style>
<div class="formPanel">
	<div style="float:left; width:45%;">
    	<label>Cán bộ / nhân viên thực hiện : </label>
        <div class="content-box" id="congviec_nguoinhanviec">
        	<img src="/img/circle_ball.gif" />
        </div>
    </div>
    <div style="float:right; width:52%; padding-right:5px">
    	<p>
        	<?php
			echo $this->Form->input('ten_congviec', 
						array( 
							'label'	=>	'Tên công việc <span class="required">*</span>',
                          	'div'	=>	false,
                          	'class'	=>	'text-input',
                          	'style'	=>	'width:97.5%',
                          	'id'	=>	'nn_ten_congviec',
							))
			?>
        </p>
        <div style="float:left; width:50%">
        <label>Ngày bắt đầu <span class="required">*</span></label>
        <input name="data[Nguoinhanviec][ngay_batdau]" id="nn_ngay_batdau" style="width:180px" class="text-input" />    	
        </div>
        <div style="float:right;">
        <label>Ngày hoàn thành <span class="required">*</span></label>
        <input name="data[Nguoinhanviec][ngay_ketthuc]" id="nn_ngay_ketthuc" style="width:180px" class="text-input" />
        </div>
        <div style="clear:both"></div>
        
        <p>
		
		<?php
			echo $this->Form->input('noi_dung', 
						array( 
							'label'	=>	'Nội dung thực hiện <span class="required">*</span>',
                          	'div'	=>	false,
                          	'class'	=>	'text-input',
                          	'style'	=>	'width:97.5%; height: 120px',
							'rows'	=>	3,
                          	'id'	=>	'nn_noi_dung',
							))
			?>
        </p>
        <p>
        	<label><input type="checkbox" name="data[Nguoinhanviec][giaoviec_tiep]" /> Được phép giao việc cho người khác ?</label>
        </p>
        
    </div>
    <div style="clear:both"></div>
</div>
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Thêm vào danh sách</button>
        
        <button class="button btn-close" type="button">Đóng</button>
    </div>
</form>				

<script>
	
	$(document).ready(function(){
		/*$('#nn_ngay_batdau').val($('#ngay_batdau').val());
		$('#nn_ngay_ketthuc').val($('#ngay_ketthuc').val());
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
		});*/
		$('#nn_ngay_batdau').val(getCookie("nn_ngay_bat_dau"));
		$('#nn_ngay_ketthuc').val(getCookie("nn_ngay_ket_thuc"));
		$('#nn_ngay_batdau').datepicker({
			dateFormat: "dd-mm-yy",
			minDate:	getCookie("nn_ngay_bat_dau"),
			maxDate: 	getCookie("nn_ngay_ket_thuc"),
			onSelect:	function(){
				var newDate = $(this).datepicker('getDate');
			    if (newDate) { // Not null
					$('#nn_ngay_ketthuc').datepicker('setDate', newDate).datepicker('option', 'minDate', newDate);
			    }
			}
		});
		$('#nn_ngay_ketthuc').datepicker({
			dateFormat: "dd-mm-yy",
			minDate: getCookie("nn_ngay_bat_dau"),
			maxDate: getCookie("nn_ngay_ket_thuc")
		});
		
		$('#nn_ten_congviec').val($('#ten_congviec').val());
		$('#nn_tinhchat_id').val($('#tinhchat_id').val());
		//$('#nn_noi_dung').val($('#noi_dung').val());		
		
		BIN.doUpdate('<a href="nhanvien/nhanviennhan/CongViec.khoitao" data-target="congviec_nguoinhanviec" data-title="Thêm người nhận việc"></a>');
		
		$('#form-nguoinhanviec-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nguoinhanviec][ngay_batdau]'	:	'required',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'required',
				'data[Nguoinhanviec][ten_congviec]'	:	'required',
				'data[Nguoinhanviec][noi_dung]'		:	'required',
				'data[Nguoinhanviec][tinhchat_id]'		:	'required'
			},
			messages:{
				'data[Nguoinhanviec][ngay_batdau]'	:	'',
				'data[Nguoinhanviec][ngay_ketthuc]'	:	'',
				'data[Nguoinhanviec][ten_congviec]'	:	'',
				'data[Nguoinhanviec][noi_dung]'		:	'',
				'data[Nguoinhanviec][tinhchat_id]'		:	''
			},
			submitHandler: function(form){
				
				if($('#nv_selected').val() == '')
				{
					alert('Vui lòng chọn người thực hiện công việc');
					return false;
				}
				else
				{
				
					$.ajax({
						type:		'POST',
						url:		$('#form-nguoinhanviec-add').attr('action'),
						cache:		false,
						async:		false,
						dataType:	'json',
						data:	$('#form-nguoinhanviec-add').serialize(),
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
			}
		});	
		
	});
</script>
