<?php
	echo $this->Html->script(
		array(
			  'jquery.gdocsviewer.min'
			  )
	);
?>
<div style="padding:10px">
<div class="content-box" id="vanban-box"><!-- Start Content Box -->
    
    <div class="content-box-header">
					
        <h3><?php echo $this->Html->image('icons/page_white_magnify.png', array('align' => 'left')); ?>&nbsp; Xem chi tiết văn bản</h3>
        
        <ul class="content-box-tabs">
            <li><a href="#vanban-info" class="default-tab" rel="vanban-info"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')); ?>&nbsp; Nội dung văn bản</a></li>
            <li><a href="#vanban-xuly" rel="vanban-xuly"><?php echo $this->Html->image('icons/page_white_lightning.png', array('align' => 'left')); ?>&nbsp; Nhật ký xử lý văn bản</a></li>
            <li><a href="#vanban-read" rel="vanban-read"><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')); ?>&nbsp; Nhật ký xem văn bản</a></li>
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="vanban-info">
        <!-- BOX INFO -->
        	<table width="100%" id="vanban-info-head">
            
            <tr>
            	<td width="33%"  style="padding-top:20px;">
	            	<div><b><U>THÔNG TIN VĂN BẢN:</U> </b> </div>
                    <div><b>Số hiệu: </b> <?php echo $this->data['Vanban']['so_hieu'] ?></div>
                    <div><b>Thể loại:</b> <?php echo $this->data['Loaivanban']['ten_loaivanban'] ?></div>
                    <div><b>Nơi phát hành:</b> <?php echo $this->data['Vanban']['noi_gui']?></div>
                    <div><b>Ngày phát hành:</b> <?php echo $this->Time->format("d-m-Y", $this->data['Vanban']['ngay_phathanh']); ?></div>
                    <?php if($this->data['Vanban']['chieu_di'] == 0): ?>
                    <div><b>Ngày gửi:</b> <?php echo $this->Time->format("d-m-Y", $this->data['Vanban']['ngay_gui']); ?></div>
                    <?php else:?>
                    <div><b>Ngày văn bản đến:</b> <?php echo $this->Time->format("d-m-Y", $this->data['Vanban']['ngay_nhan']); ?></div>
                    <?php endif;?>
                    <div><b>Người ký:</b> <?php echo $this->data['Vanban']['nguoi_ky'] ?></div>
                </td>
                <td width="33%" style="padding-top:20px;">
                	<div><b><U>NỘI DUNG CHỈ ĐẠO:</U> </b> </div>
					<?php
					 if($this->data['Vanban']['nguoi_duyet_id'] == $this->Session->read('Auth.User.nhanvien_id')): ?>
                    	<div><b>Nội dung trình:</b><br> <?php echo $this->data['Vanban']['noidung_trinh'] ?></div>
                    <?php endif;?>
					<?php if(($this->data['Vanban']['tinhtrang_duyet']) == 1): ?>
                    	<div><b>Lãnh đạo duyệt:</b> <?php echo $this->data['Vanban']['nguoi_duyet'] ?></div>
                    <?php endif;?>
                    <?php if(($this->data['Vanban']['tinhtrang_duyet']) == 1): ?>
                    <div><b>Nội dung duyệt:</b><br> <?php echo $this->data['Vanban']['noidung_duyet'] ?></div>
                    <?php endif;?>
                    <?php 
						if($this->data['Vanban']['vb_gap'] == 1 && $this->data['Vanban']['tinhtrang_duyet'] == 1 ) {
							?>
							<div style="color:#F00"><b>Ngày hoàn thành:</b> <?php echo $this->Time->format("d-m-Y", $this->data['Vanban']['vbgap_ngayhoanthanh']); ?></div>
							<?php 
							
						}
					?>
                </td>
                <td width="34%" style="padding-top:20px">
                	<div><b><U>PHÂN HƯỚNG VĂN BẢN:</U> </b> </div>
					<?php 
						if($this->data['Vanban']['tinhtrang_duyet'] <> 0) { 
					?>
							<div><b>Đã chuyển cho Lãnh đạo: </b>
                                <br>
								<?php 
									foreach($nguoi_nhan as $item_nhan){
										if(in_array($item_nhan['Nhanvanban']['nguoi_nhan_id'],$arr_nguoiduyet))
											echo '- &nbsp;'.$item_nhan['Nhanvien']['full_name'].'<br>';
									}
                                ?>
                            </div>
							<?php 
						}
					?>	
                    <?php if(($this->data['Vanban']['tinhtrang_duyet']) <> 0): ?>
                    	<div><b>Đơn vị chủ trì:</b> <?php echo $phong_chutri?></div>
                    <?php endif;?>
                    <?php
					 if(($this->data['Vanban']['tinhtrang_duyet']) <> 0 && !empty($arr_dsphong)): ?>
                    	<div><b>Đơn vị phối hợp: </b> 
                        <br>
						<?php 
                            foreach($arr_dsphong as $it){echo '- &nbsp;'.$it.'<br>';}
                        ?>
                       </div>
                    <?php endif;
						
					?>
                </td>
                
            </tr>
            <tr>
            	<?php 
					if(!empty($kq_vanban)) {
				?>
                <td colspan="3" style="text-align:center!important; padding-top:10px" >
                    <div><b><U>KẾT QUẢ THỰC HIỆN:</U> </b> </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left!important; padding-top:10px">
                	<?php 
						//if(!empty($kq_vanban))
							$i = 0;
							foreach($kq_vanban as $kq)
							{
								if($kq['Ketquavanban']['nguoi_nhan_id'] == $this->Session->read('Auth.User.nhanvien_id')){
								?>
                                	<div><b>Người báo cáo:</b> <?php echo $kq['Nhanvien']['full_name']; ?></div>
                                    <div><b>Ngày báo cáo:</b> <?php echo $this->Time->format("d-m-Y", $kq['Ketquavanban']['ngay_capnhat']); ?></div>
                                    <div><b>Nội dung báo cáo:</b> <?php echo $kq['Ketquavanban']['noidung_capnhat']; ?></div>
                                    <?php 
										if ($i < count($kq_vanban) - 1)
										echo '<hr>';
										$i++;
									?>

                                <?php	
								}
																
							}
						
					?>
                </td>
            </tr>
            <?php } ?>
            <tr>
            	<td colspan="3" style="text-align:right!important; padding-top:10px" class="noprint">
                	<div class="print-section">
        				<a href="" id="print-button" class="button"><?php echo $this->Html->image('icons/print.png', array('align' => 'left')) ?>&nbsp; In thông tin</a>
        			</div>	
                </td>
            </tr>
            <tr>
            	<td colspan="3" style="text-align:center!important; padding-top:20px" class="noprint">
                	<div style="text-transform:uppercase"><h2><?php echo $this->data['Tinhchatvanban']['ten_tinhchat'] ?></h2></div>
                    <h4><a href="javascript:void(0)"><?php echo $this->data['Vanban']['trich_yeu']?></a></h4>
                </td>
            </tr>
        </table>
		<?php 
				if(!empty($this->data['Filevanban'])) : 
				for ($i = 0; $i < count($this->data['Filevanban']); ++$i)
				{
					$arr = explode(".", $this->data['Filevanban'][$i]['path']);
					$ext = strtolower(end($arr));
						echo '<a name="xem_chitiet" id="xem_chitiet"></a>';
						if($is_mobile || $ext != 'tif')
						{
							echo $this->element('googledocs_viewer');
						}else
							echo $this->element('tiff_viewer');
				}
				endif;
				
		?>    
        <!-- END BOX INFO -->
        </div>
    	<div class="tab-content" id="vanban-xuly">
        <!-- BOX XULY -->
        	<div class="content-box" style="float:left; width:50%">
				
				<div class="content-box-header">
					<h3><?php echo $this->Html->image('icons/page_save.png', array('align' => 'left')); ?>&nbsp; Nơi lưu văn bản gốc</h3>
				</div>
				
				<div class="content-box-content">
					<div class="tab-content default-tab">
                    <ul>
                    <?php
						  	if(!empty($this->data['Luuvanban']))
								foreach($this->data['Luuvanban'] as $item)
									printf("<li>- %s</li>", $item['Noiluu']['ten_phong']);
							else
								echo 'Không phòng nào lưu văn bản gốc.';
						  ?>
                    </ul>
					</div> <!-- End #tab3 -->        
				</div>
                
                <div class="content-box-header">
					<h3><?php echo $this->Html->image('icons/page_white_put.png', array('align' => 'left')); ?>&nbsp; Nơi nhận văn bản</h3>
				</div>
                
                <div class="content-box-content">
					<div class="tab-content default-tab">
                    <?php 
						if(!empty($phong_nhan))
						{
							echo '<ul>';
							foreach($phong_nhan as $phong)
								printf("<li>- %s</li>", $phong['Phong']['ten_phong']);
							echo '</ul>';
						}else
							echo 'Văn bản này không gửi cho phòng nào.';
					?>
					</div> <!-- End #tab3 -->        
				</div>
			</div> <!-- End .content-box -->
			
			<div class="content-box" style="float:right; width:48%">
            	<div class="content-box-header">
					<h3><?php echo $this->Html->image('icons/page_white_lightning.png', array('align' => 'left')); ?>&nbsp; Nhật ký xử lý</h3>
				</div> <!-- End .content-box-header -->
                <div class="content-box-content">
                	<div class="tab-content default-tab" id="xuly-container">
                    	<?php
							if(!empty($this->data['Xulyvanban']))
								foreach($this->data['Xulyvanban'] as $item):
							?>
							<div class="notification success png_bg">
								<a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
								<div>
									<?php  
										echo "<b>" . $item['Nguoixuly']['full_name'] . "</b> đã viết vào lúc " . $this->Time->format("d-m-Y H:i:s", $item['ngay_xuly']) . ":<BR>";
										echo $item['noi_dung'];
									?>
								</div>
							</div>
							<?php
								endforeach;
						?>
                    </div>
                </div>
                <?php if($this->Layout->check_permission('VanBan.xuly')): ?>
				<div class="content-box-header">
					<h3>Xử lý văn bản</h3>
				</div> <!-- End .content-box-header -->
				<div class="content-box-content">
					<div class="tab-content default-tab">
						<?php
							echo $this->Form->create('Xulyvanban', array('id'	=>	'form-xuly-vanban', 'url' => array('controller' => 'Xulyvanban', 'action' => 'add')));
							echo $this->Form->hidden('vanban_id', array('value' => $this->data['Vanban']['id'], 'id' => 'vanban_id'));
							echo $this->Form->input('noi_dung', array(
															  'label'	=>	'Nội dung <span class="required">*</span>',
															  'class'	=>	'text-input',
															  'style'	=>	'width:97.5%',
															  'rows'	=>	4,
															  'id'		=>	'noi_dung'
																	  ));
						?>
                        <p style="text-align:right!important"><input class="button" type="submit" value="Lưu xử lý" /></p>
                        <?php
							echo $this->Form->end();
						?>
						<script>
							$(document).ready(function(){
								
								$('#noi_dung').focus();
							});
                        </script>
					</div> <!-- End #tab3 -->        
                    
				</div> <!-- End .content-box-content -->
                <?php endif;?>
			</div> <!-- End .content-box -->
			<div class="clear"></div>
        </div>
        <!-- END BOX XULY -->
        <!-- BEGIN BOX READ -->
        <div class="tab-content" id="vanban-read">
        <div class="table-content" class="data">
            <table id="nhanvien-data">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="60%">Họ tên Nhân viên</th>
                    <th width="30%">Ngày xem</th>
                </tr>
                </thead>
            <tbody>
            <?php
                $stt = 1;
                foreach($nguoi_nhan as $item):
            ?>
                <tr>
                    <td align="center"><?php echo $stt++ ?></td>
                    <td>
                    <?php
                        if($item['Nhanvien']['nguoi_quanly'] == 1)
                            printf("<b>%s</b>", $item['Nhanvien']['full_name']);
                        else
                            echo $item['Nhanvien']['full_name'];
                    ?>
                    </td>
                    <td>
                    <?php
                        if(!empty($item['Nhanvanban']['ngay_xem']))
                            echo $this->Time->format('d-m-Y H:i:s', $item['Nhanvanban']['ngay_xem']);
                        else
                            echo 'Chưa đọc văn bản';
                    ?>
                    </td>
                </tr>
            <?php
                endforeach;
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        <?php if($this->Paginator->params['paging']['Nhanvien']['pageCount'] > 1):?>
                        <div id="pagination">
                            [Page <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                            <?php echo $this->Paginator->first('|< First', array('class' => 'number'))?>
                            <?php echo $this->Paginator->prev('< Prev', array('class' => 'number'))?>
                            <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number')); ?>
                            <?php echo $this->Paginator->next('Next >', array('class' => 'number'))?>
                            <?php echo $this->Paginator->last('Last >|', array('class' => 'number'))?>
                        </div>
                        <?php endif;?>
                    </td>
                </tr>
            </tfoot>
            </table>
        </div>
        </div>
        <!-- END BOX READ -->
    	
	</div>

</div>

</div>