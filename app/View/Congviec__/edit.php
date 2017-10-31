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
//	pr($this->data['Vanban']);	die();
?>
<div style="padding:20px">
    <div class="content-box" id="congviec-box"><!-- Start Content Box -->
        <div class="content-box-header">
                        
            <h3><?php echo $this->Html->image('icons/note_edit.png', array('align' => 'left')) ?>&nbsp; Hiệu chỉnh công việc</h3>
            
            <ul class="content-box-tabs">
                <li><a href="#congviec-noidung" class="default-tab" rel="congviec-noidung"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Nội dung công việc</a></li>
                <li><a href="#congviec-nhan" rel="congviec-nhan"><?php echo $this->Html->image('icons/group.png', array('align' => 'left')) ?>&nbsp; Thêm người nhận việc</a></li>
            </ul>
            
            <div class="clear"></div>
            
        </div> <!-- End .content-box-header -->
        
        <?php 
			
            echo $this->Form->create('Congviec', array('id' => 'form-congviec-edit')); 
            echo $this->Form->hidden('id', array('id' => 'congviec_id'));
        ?>
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
                              'id'		=>	'ten_congviec'
                              ));
                    echo '</p>';
                    
            ?>
            	<p>
                    <label>Giao việc theo văn bản</label>
                    <div id="congviec-vanban"></div>
                    <div id="chonvanban-container">
                        <a class="button" href="/congviec/chon_vanban" data-mode="ajax" data-action="dialog" title="Chọn văn bản" data-width="800">Chọn văn bản</a>
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
                    
                    <div style="float:right; width:180px; margin-left:10px">
                        <label>Ngày hoàn thành <span class="required">*</span></label>
                        <input name="data[Congviec][ngay_ketthuc]" id="ngay_ketthuc" style="width:160px" class="text-input"  value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_ketthuc']) ?>"/>
                    </div>
                    <div style="float:right; width:180px; margin-left:10px">
                        <label>Ngày bắt đầu <span class="required">*</span></label>
                        <input name="data[Congviec][ngay_batdau]" id="ngay_batdau" style="width:160px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_batdau']) ?>" />
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
                    <input type="checkbox" name="data[Congviec][giaoviec_tiep]" <?php echo !empty($this->data['Congviec']['giaoviec_tiep']) ? 'checked' :'' ?> /> <b>Được phép giao việc cho người khác ?</b>
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
						  'id'		=>	'noi_dung'
						  ));
				?>
            </p>
            </div>
            <div class="tab-content" id="congviec-nhan">
                <div style="padding:10px">
                    <a class="button" href="<?php echo '/congviec/add_nguoinhanviec/' . $this->data['Congviec']['id']?>" rel="facebox" id="btn-nhanvien-add">Thêm mới Người nhận việc</a>
                    <a class="button btn-congviec-edit" style="font-weight:bold; padding:3px"  href="#">Lưu thông tin</a>
                </div>
                <div class="data" id="nhanvien-data">
                    <div id='calendar' style="width:100%;"></div>
                </div>
            </div>
        </div>
        
        <div style="padding:10px">
        	<a class="button btn-congviec-edit" style="font-weight:bold; padding:3px"  href="#">Lưu thông tin</a>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>    
<script>
	$(document).ready(function(){
					
		<?php
			if(!empty($this->data['Vanban']['trich_yeu'])):
		?>
			$('#congviec-vanban').html('<div class="uploaded_item"><div style="float:left"><?php echo $this->Html->link($this->data['Vanban']['trich_yeu'], '/vanban/view/' . $this->data['Congviec']['vanban_id'], array('title' => 'Xem chi tiết', 'target' => '_blank')) ?></div><div style="float:right"><a href="javascript:void(0)" onClick="unselect()"><img src="/img/closelabel.png" title="Bỏ chọn"></a></div><div style="clear:both"></div><input type="hidden" name=data[Congviec][vanban_id] value="<?php echo $this->data['Congviec']['vanban_id'] ?>"></div>');
			$('#chonvanban-container').hide();
		<?php
			endif;
		?>
		
		$('#ten_congviec').focus();
	});
</script>