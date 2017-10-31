<?php
	echo $this->Form->create('Lichcongtac', array('id' => 'form-lichcongtac-edit'));
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => $nvcongtac));
?>
<style>
.dynatree-container{
	height: 245px!important; overflow:auto
}
</style>

<div class="formPanel">
	<div>
    	<div style="float:left; width:48%" id="congtac_leftcontent">
            
            <div>
                <?php
                    echo $this->Form->input('tieu_de', array('label'	=>	'Tiêu đề <span style="color:red">*</span>',
                                                                  'id'		=>	'tieu_de',
                                                                  'style'	=>	'width:97.5%',
                                                                  'class'	=>	'text-input'));
                ?>
            </div>
            <div style="padding-top:5px">
                <?php
                    echo $this->Form->input('noi_dung', array('label'	=>	'Nội dung <span class="required">*</span>',
                                                          'id'		=>	'noi_dung',
                                                          'class'	=>	'text-input',
                                                          'rows'	=>	3));
                ?>
            </div>
            <div style="padding-top:5px">
                <div style="float:left; width:50%">
                    <?php
                        echo $this->Form->input('congtac_tinhchat_id', array('label'	=>	'Tính chất',
                                                                      'id'		=>	'congtac_tinhchat_id',
                                                                      'style'	=>	'width:180px',
                                                                      'empty'	=>	'',
                                                                      'options'	=>	$tinhchat));
                    ?>
                </div>
                <div style="float:right; width:50%">
                    <?php
                        echo $this->Form->input('nguoi_ky', array('label'	=>	'Người ký',
                                                                      'id'		=>	'nguoi_ky',
                                                                      'style'	=>	'width:180px',
                                                                      'class'	=>	'text-input'));
                    ?>
                </div>
                <div style="clear:both"></div>
            </div>
            <div style="padding-top:5px">
                <div style="float:left; width:50%">
                    <label>Ngày bắt đầu <span class="required">*</span></label>
                    <input name="data[Lichcongtac][ngay_batdau]" id="ngay_batdau" style="width:165px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Lichcongtac']['ngay_batdau'])?>" />
                </div>
                <div style="float:right; width:50%">
                    <label>Ngày kết thúc <span class="required">*</span></label>
                    <input name="data[Lichcongtac][ngay_ketthuc]" id="ngay_ketthuc" style="width:180px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Lichcongtac']['ngay_ketthuc']) ?>" />
                </div>
                <div style="clear:both"></div>
            </div>
            <div style="padding-top:5px">
                <?php
                    echo $this->Form->input('noi_congtac', array('label'	=>	'Nơi công tác <span class="required">*</span>',
                                                                  'id'		=>	'noi_congtac',
                                                                  'style'	=>	'width:97.5%',
                                                                  'class'	=>	'text-input'));
                ?>
            </div>
    	</div>
        <div style="float:right; width:50%">
            	<label>Nhân viên đi công tác <span class="required">*</span></label>
                
                <!-- BEGIN NHANVIEN -->
                <div class="content-box" id="nhanvien-congtac" style="overflow:auto">
                    
                </div>	
                <!-- END NHANVIEN -->
    	</div>
        <div style="clear:both"></div>
    </div>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" type="submit">Lưu dữ liệu</button>
        <input class="button" type="button" value="Xóa Lịch công tác này" id="btn-lich-del" rel="<?php echo $this->data['Lichcongtac']['id'] ?>" />
        <button class="button btn-close" type="button">Đóng</button>
    </div>
        
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		$('#ngay_batdau').datepicker({dateFormat: "dd-mm-yy"});
		$('#ngay_ketthuc').datepicker({dateFormat: "dd-mm-yy"});
		
		$('#btn-lich-del').click(function(){
			if(confirm('Bạn có muốn xóa lịch công tác này không ?'))
			{
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'calendars/del_lichcongtac',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	{id: $(this).attr('rel')},
					success:	function(result)
					{
						if(result.success)
						{
							alert(result.message);
							$('#form-lichcongtac-edit').find('.btn-close').first().click();
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
		
		$('#form-lichcongtac-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Lichcongtac][tieu_de]'	:	'required',
				'data[Lichcongtac][noi_dung]'	:	'required',
				'data[Lichcongtac][ngay_batdau]'	:	'required',
				'data[Lichcongtac][ngay_ketthuc]'	:	'required',
				'data[Lichcongtac][noi_congtac]'	:	'required'
			},
			messages:{
				'data[Lichcongtac][tieu_de]'	:	'Vui lòng nhập vào Tiêu đề',
				'data[Lichcongtac][noi_dung]' 	: 	'Vui lòng nhập vào Nội dung',
				'data[Lichcongtac][ngay_batdau]'	:	'',
				'data[Lichcongtac][ngay_ketthuc]'	:	'',
				'data[Lichcongtac][noi_congtac]'	:	'Vui lòng nhập vào nơi công tác'
			},
			submitHandler: function(form){
				
				if($('#nv_selected').val() == '')
				{
					alert('Vui lòng chọn nhân viên đi công tác.');
					return false;
				}
				$.ajax({
					type:		'POST',
					url:		BIN.baseURL + 'calendars/edit_lichcongtac',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	$('#form-lichcongtac-edit').serialize(),
					success:	function(result)
					{
						if(result.success)
						{
							$('#form-lichcongtac-edit').find('.btn-close').first().click();
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
		BIN.doUpdate('<a href="nhanvien/nhanviennhan/LichCongTac.tao_lichcongtac" data-target="nhanvien-congtac">');
	});
</script>