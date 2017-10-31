<?php
	echo $this->Html->css(array(
			'/js/libs/fullcalendar/fullcalendar',
	));
	
	echo $this->Html->script(
		array(
			  'libs/fullcalendar/fullcalendar.min',
			  'libs/tinymce/tinymce.min'
			  )
	);
	
?>
<div style="padding:20px">
<div class="content-box" id="congviec-box"><!-- Start Content Box -->
    
    <div class="content-box-header">
					
        <h3><?php echo $this->Html->image('icons/note_edit.png', array('align' => 'left')) ?>&nbsp; Khởi tạo công việc</h3>
        
        <ul class="content-box-tabs">
            <li><a href="#congviec-noidung" class="default-tab" rel="congviec-noidung"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Nội dung công việc</a></li>
            <li><a href="#congviec-nhan" rel="congviec-nhan">&nbsp; Thêm người nhận việc</a><a href="#congviec-nhan" rel="congviec-nhan"><?php echo $this->Html->image('icons/group.png', array('align' => 'left')) ?></a></li>
            
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <?php echo $this->Form->create('Congviec', array('id' => 'form-congviec-add')); ?>
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="congviec-noidung">
        <?php
				echo $this->Session->flash();
				echo '<p>';
				echo $this->Form->input('ten_congviec',
					array(
						  'label'	=>	'Tên công việc <span class="required">*</span>',
						  'div'		=>	false,
						  'class'	=>	'text-input',
						  'style'	=>	'width:97.5%',
						  'id'		=>	'ten_congviec',
						  'value'	=>	isset($vanban) ? $vanban['Vanban']['so_hieu'] : ''
						  ));
				echo '</p>';
				
		?>
        	<p>
            	<label>Giao việc theo văn bản</label>
                <div id="congviec-vanban"></div>
                <div id="chonvanban-container">
                	<a class="button" href="/congviec/chon_vanban" data-mode="ajax" data-action="dialog" title="Giao việc theo văn bản" data-width="800">Chọn văn bản</a>
                </div>
            </p>
            
        	<div>
            	<div style="float:left; width:32%">
                <?php
					echo $this->Form->input('tinhchat_id',
					array(
						  'label'	=>	'Tính chất công việc',
						  'div'		=>	false,
						  'class'	=>	'text-input',
						  'style'	=>	'width:97.5%',
						  'empty'	=>	'',
						  'options'	=>	$tinhchat,
						  'id'		=>	'tinhchat_id'
						  ));
				?>
                </div>
            	<div style="float:left; width:32%">
                <?php
					echo $this->Form->input('loaicongviec_id',
					array(
						  'label'	=>	'Phân loại công việc <span class="required">*</span>',
						  'div'		=>	false,
						  'class'	=>	'text-input',
						  'style'	=>	'width:97.5%',
						  'options'	=>	$loai_congviec,
						  'id'		=>	'loaicongviec_id'
						  ));
				?>
                </div>
                
                <div style="float:right; width:180px; margin-left:10px;">
                    <label>Ngày hoàn thành <span class="required">*</span></label>
                    <input name="data[Congviec][ngay_ketthuc]" id="ngay_ketthuc" style="width:150px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', time())?>" />
                </div>
                <div style="float:right; width:180px; margin-left:10px">
                    <label>Ngày bắt đầu <span class="required">*</span></label>
                    <input name="data[Congviec][ngay_batdau]" id="ngay_batdau" style="width:150px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', time())?>"  />
                </div>
                <div style="clear:both"></div>
            </div>
        <p>
        	<div style="float:left">
            <?php
				echo $this->Form->input('nguoinhan_id', 
					array(
                          'label'	=>	'Nhân sự chịu trách nhiệm chính <span class="required">*</span>',
                          'div'		=>	false,
                          'class'	=>	'text-input',
                          'id'		=>	'nguoinhan_id',
						  'style'	=>	'min-width:330px; width:auto',
                          'options'	=>	$nv,
						  'empty'	=>	'-- chọn cán bộ / nhân viên chịu trách nhiệm chính --'
                          )
				);
			?>
            </div>
            <div style="float:left; padding-left: 30px"> 
            	<label>&nbsp;</label>
                <input type="checkbox" name="data[Congviec][giaoviec_tiep]" /> <b>Được phép giao việc cho người khác ?</b>
            </div>
            <div style="clear:both"></div>
        </p>
        <p>
			<?php
				echo $this->Form->input('noi_dung',
				array(
					  'label'	=>	'Nội dung <span class="required">*</span>',
					  'div'		=>	false,
					  'class'	=>	'text-input',
					  'rows'	=>	3,
					  'style'	=>	'width:97.5%',
					  'id'		=>	'noi_dung',
					  'value'	=>	isset($vanban) ? $vanban['Vanban']['trich_yeu'] : ''
					  ));
            ?>
        </p>
        </div>
    	<div class="tab-content" id="congviec-nhan">
        	<div style="padding:10px 5px">
                	<a class="button" href="/congviec/add_nguoinhanviec" data-mode="ajax" data-action="dialog" title="Chọn nhân sự thực hiện công việc" data-width="800">Thêm mới Người nhận việc</a>
                    <a class="button btn-congviec-add" href="#">Khởi tạo công việc</a>
            </div>
        	<div class="data" id="nhanvien-data">
            	<div id='calendar' style="width:100%;"></div>
            </div>
    	</div>
	</div>
    
    <div style="padding:10px">
        <a class="button btn-congviec-add" href="#">Khởi tạo công việc</a>
    </div>
	<?php echo $this->Form->end(); ?>
</div>

</div>
<script>
	
	$(document).ready(function(){
		<?php
			if(!empty($vanban)):
		?>
			$('#congviec-vanban').html('<div class="uploaded_item"><div style="float:left"><?php echo $this->Html->link($vanban['Vanban']['trich_yeu'], '/vanban/view/' . $vanban['Vanban']['id'], array('title' => 'Xem chi tiết', 'target' => '_blank')) ?></div><div style="float:right"><a href="javascript:void(0)" onClick="unselect()"><img src="/img/closelabel.png" title="Bỏ chọn"></a></div><div style="clear:both"></div><input type="hidden" name=data[Congviec][vanban_id] value="<?php echo $vanban['Vanban']['id'] ?>"></div>');
			$('#chonvanban-container').hide();
		<?php
			endif;
		?>
		
		
		
	});
</script>