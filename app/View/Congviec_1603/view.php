<?php
	echo $this->Html->css(array(
		'treetable/jquery.treetable',
	));
	
	echo $this->Html->script(
		array(
			  'jquery.treetable',
			  'libs/jquery/jquery.hashchange',
			  'libs/tinymce/tinymce.min'
			  )
	);
	$quyen_giaoviec = $this->Layout->check_permission('CongViec.khoitao');
?>
<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Xem chi tiết công việc</h1></div>
    <div style="clear:both"></div>
</div>
<!-- end page-heading -->
    
<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
<tr>
    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
    <th class="topleft"></th>
    <td id="tbl-border-top">&nbsp;</td>
    <th class="topright"></th>
    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
</tr>
<tr>
    <td id="tbl-border-left"></td>
    <td>
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner">
		
        <?php 
			echo $this->Form->create('Congviec'); 
			echo $this->Form->hidden('id', array('id' =>'congviec_id'));
		?>
        <div class="tab-content default-tab" id="congviec-noidung">
        <?php
			echo $this->Session->flash();
				echo '<p>';
				echo $this->Form->input('ten_congviec',
					array(
						  'label'	=>	'Tên công việc ',
						  'div'		=>	false,
						  'class'	=>	'text-input',
						  'style'	=>	'width:97.5%; font-weight:bold; color: green',
						  'id'		=>	'ten_congviec'
						  ));
				echo '</p>';
		?>
        	<div>
            	<div style="float:left; width:32%">
                <?php
					echo $this->Form->input('ten_tinhchat',
					array(
						  'label'	=>	'Tính chất công việc ',
						  'div'		=>	false,
						  'class'	=>	'text-input',
						  'style'	=>	'width:97.5%',
						  'value'	=>	$this->data['Tinhchatcongviec']['ten_tinhchat'],
						  'id'		=>	'tinhchat_id'
						  ));
				?>
                </div>
            	
                <div style="float:right; width:32%">
                    <label>Ngày kết thúc </label>
                    <input name="data[Congviec][ngay_ketthuc]" id="ngay_ketthuc" style="width:160px" class="text-input" value="<?php echo $this->Time->format("d-m-Y", $this->data['Congviec']['ngay_ketthuc']) ?>"  />
                </div>
                <div style="float:right; width:32%">
                    <label>Ngày bắt đầu </label>
                    <input name="data[Congviec][ngay_batdau]" id="ngay_batdau" style="width:160px" class="text-input" value="<?php echo $this->Time->format("d-m-Y", $this->data['Congviec']['ngay_batdau']) ?>" />
                </div>
                <div style="clear:both"></div>
            </div>
        <p>
        	<label>Nội dung</label>
            <div style="border:1px solid #CCC; padding:10px;-moz-border-radius: 4px; -webkit-border-radius: 4px;			border-radius: 4px; line-height:1.5em;">
            	<?php echo $this->data['Congviec']['noi_dung'] ?>
            </div>
        </p>
        
        <?php if(!empty($this->data['Congviec']['vanban_id'])): ?>
        <p>
        	<label>Giao việc theo văn bản</label>
            <div style="border:1px solid #CCC; padding:10px;-moz-border-radius: 4px; -webkit-border-radius: 4px;			border-radius: 4px; line-height:1.5em;">
            	<?php 
					echo $this->Html->link(
						sprintf("<b>Trích yếu </b>: %s <BR><b>Số hiệu </b>: %s, <b>Ngày phát hành </b>: %s, <b>Nơi phát hành </b>: %s", 
							$this->data['Vanban']['trich_yeu'], 
							$this->data['Vanban']['so_hieu'], 
							$this->Time->format('d-m-Y', $this->data['Vanban']['ngay_phathanh']), $this->data['Vanban']['noi_gui']),
						'/vanban/view/' . $this->data['Congviec']['vanban_id'] . '#xem_chitiet',
						array('escape' => false, 'title' => 'Click để xem chi tiết văn bản', 'target' => '_blank')
					); 
				?>
            </div>
        </p>
        <?php endif;?>
        
        <div style="padding-bottom:20px">
        	<div style="float:left; width:200px">
            	<label>Người giao việc </label>
                <input type="text" class="text-input" style="width:97.5%; font-weight:bold" value="<?php echo $this->data['NguoiGiaoviec']['full_name'] ?>"? />
            </div>
            <div style="width:140px; float:left; margin-left:20px">
            	<label>Ngày giao việc </label>
                <input type="text" class="text-input" style="width:80px" value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_giao']) ?>"? />
            </div>
            <div style="float:left; width:200px">
                <?php 
					if(!empty($this->data['Filecongviec']))
					{
				?>
                <label>Files đính kèm </label>
				<?php
						foreach($this->data['Filecongviec'] as $file):
							echo $this->Html->link($file['ten_cu'], '/congviec/files_att/' . $file['id']);
							echo '<BR>';
							echo '<BR>';
						endforeach;
					}
				?>
                
            </div>
            <div style="float:right; margin-left:20px; ">
            	<label>Mức độ hoàn thành công việc</label>
                
                <div class="prog-border" style="float:left;margin-top:3px">
                	<?php
						if(!empty($this->data['Congviec']['ngay_capnhat']) && $this->Time->format('Y-m-d', $this->data['Congviec']['ngay_capnhat']) <= $this->data['Congviec']['ngay_ketthuc'])
							echo '<div class="prog-bar-blue" style="width: ' . $this->data['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $this->data['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
						else
							echo '<div class="prog-bar-red" style="width: ' . $this->data['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $this->data['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
					?>
                </div>
                <?php if($this->data['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id')): ?>
                <div style="float:left; margin-left:10px;margin-top:3px">
                	<a href="/congviec/update_progress/<?php echo $this->data['Congviec']['id'].'/' . $refer ?>" data-mode="ajax" class="button" data-action="dialog" data-width="400" title="Cập nhật mức độ hoàn thành công việc">Cập nhật</a>
                </div>
                <?php endif;?>
                <div style="clear:both"></div>
            </div>
            
            <div style="width:200px; float:right; margin-right:20px">
            	<label>Người thực hiện </label>
                <input type="text" class="text-input" style="width:97.5%; font-weight:bold" value="<?php echo $this->data['NguoiNhanviec']['full_name'] ?>"? />
            </div>
            
            <div style="clear:both"></div>
        </div>
        
        <?php echo $this->Form->end(); ?>
        
        <!-- Extra Infor -->
        <div class="content-box" id="congviec-box"><!-- Start Content Box -->
    		<div class="content-box-header">
		        <h3><img src="/img/icons/email.png" align="left" />&nbsp; Công việc đã giao</h3>
                <ul class="content-box-tabs">
                    <li><a href="#congviec-comment" class="default-tab" rel="congviec-comment" id="congviec-comment-tab" title="Thảo luận về công việc"><img src="/img/icons/note.png" align="left" />&nbsp;Thảo luận về công việc</a></li>
                    <li><a href="#congviec-progressing" rel="congviec-progressing" id="congviec-progressing-tab" title="Chi tiết quá trình thực hiện"><img src="/img/icons/email_open.png" align="left" />&nbsp;Chi tiết quá trình thực hiện</a></li>
                    <li><a href="#congviec-flow" rel="congviec-flow" id="congviec-flow-tab" title="Chi tiết luồng công việc"><img src="/img/icons/email_go.png" align="left" />&nbsp;Chi tiết luồng công việc</a></li>
                </ul>
                <div class="clear"></div>
        
    		</div> <!-- End .content-box-header -->
            
            <div class="content-box-content">
                <div class="tab-content default-tab" id="congviec-comment">
                     <div class="content-box-content" id="congviec-comments">
                        <img src="/img/circle_ball.gif" />
                    </div>
                    <div id="add-comment">
                        <label>Gửi thảo luận</label>
                        <form>
                            <textarea id="noi_dung" class="text-input" style=""></textarea>
                            <BR>
                            <button class="button" type="button" id="btn-submit-comment">Gửi thảo luận</button>
                        </form>
                    </div>
                </div>
                
                <div class="tab-content default-tab" id="congviec-progressing">
                </div>
                
                <div class="tab-content default-tab" id="congviec-flow">
                </div>
            </div>
       </div> 

        <!-- END -->
    </div>
    <!--  end content-table-inner ............................................END  -->
    </td>
    <td id="tbl-border-right"></td>
</tr>
<tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
</tr>
</table>