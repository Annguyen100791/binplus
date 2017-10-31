<!--  start page-heading -->
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
	echo $this->Html->script(
		array(
			  'libs/jquery/ajaxupload.3.5',
			  )
	);
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get("post_max_size"));
	$max_memory = (int)(ini_get('memory_limit'));
	$file_limit = min($max_upload, $max_post, $max_memory);
	
	
	echo $this->Session->flash();
	echo $this->Form->create('Vanban', array('id'	=>	'form-vanban-compose'));
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => ''));
?>
    <div id="compose-list-content">
    
    	<div class="content-box" style="width:58%; float:left">
				
            <div class="content-box-header">
                <div>
                	<h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Soạn thảo văn bản</h3>
                </div>
                <div style="float:right; padding:6px">
                	<a class="button" id="btn-vanban-add" style="font-weight:bold;" title="Click để Gửi văn bản"><span style="padding:7px">Gửi văn bản</span></a>
                </div>
                <div style="clear:both"></div>
            </div> <!-- End .content-box-header -->
            
            <div class="content-box-content">
                
                <div class="tab-content default-tab" id="vanban_leftcontent">
                
                	<div>
                     <div style="float:left; width:45%">
                     	<?php
									echo $this->Form->input('chieu_di',
										array(
											  'label'	=>	'Chiều văn bản <span class="required">*</span>',
											  'div'		=>	false,
											  'class'	=>	'text-input',
											  'style'	=>	'width:100%',
											  'id'		=>	'chieu_di',
											  'options'	=>	$chieu_di,
											  'empty'	=>	false,
											  'onChange'	=>	'chieudiChange(this)'
											  ));
								?>
                     </div>
                     <div style="float:right; width:48%; padding-right:10px">
                     	<?php
									echo $this->Form->input('so_hieu',
										array(
											  'label'	=>	'Số văn bản <span class="required">*</span>',
											  'div'		=>	false,
											  'class'	=>	'text-input',
											  'style'	=>	'width:100%',
											  'id'		=>	'so_hieu'
											  ));
								?>
                     </div>
                     <div style="clear:both"></div>
                  </div>
                  
                  
                  <div id="noidung_vb_den">
                  	<div style="padding-top: 10px">
                     	<div style="float:left; width:33%">
                        	<?php
										echo $this->Form->input('so_hieu_den',
											array(
												  'label'	=>	'Số thứ tự văn bản đến',
												  'div'		=>	false,
												  'class'	=>	'text-input',
												  'style'	=>	'width:95%',
												  'id'		=>	'so_hieu_den',
												  //'value'	=>	$so_hieu_den
												  ));
									?>
                        </div>
                        <div style="float:left; width:30%; padding-left: 10px">
                        	<label>Ngày văn bản đến <span class="required">*</span></label>
                        	<input name="data[Vanban][ngay_nhan]" id="ngay_nhan" value="<?php echo $this->Time->format('d-m-Y', time()) ?>" class="text-input" size="15" style="width:100%"  />
                        </div>
                        
                        <div style="float:right; width:30%; padding-right: 10px">
                        	<label>Ngày chuyển văn bản</label>
                        	<input name="data[Vanban][ngay_chuyen]" id="ngay_chuyen" value="" style="width:100%" class="text-input" size="15" />
                        </div>
                        <div style="clear:both"></div>
                     </div>
                     
                     
                     
                  </div><!-- end of vanban den -->
                  
                  <div style="padding-top:10px">
                     <div style="width:20%; float:left">
                        <label>Ngày phát hành <span class="required">*</span></label>
                        <input name="data[Vanban][ngay_phathanh]" id="ngay_phathanh" value="<?php echo $this->Time->format('d-m-Y', time()) ?>" class="text-input" size="15" />
                     </div>
                     <div style="width:40%; float:left; padding-left: 10px">
                     <?php
                        echo $this->Form->input('noi_gui',
                           array(
                                'label'	=>	'Nơi phát hành <span class="required">*</span>',
                                'div'		=>	false,
                                'class'	=>	'text-input',
                                'style'	=>	'width:100%',
                                'value'	=>	$this->Session->read('Auth.User.ten_phong'),
                                'id'		=>	'noi_gui'
                                ));
                     ?>
                     </div>
                     <div style="float:right; width:35%;">
                     <?php
                        echo $this->Form->input('nguoi_ky',
                           array(
                                'label'	=>	'Người ký phát hành văn bản <span class="required">*</span>',
                                'div'		=>	false,
                                'class'	=>	'text-input',
                                'style'	=>	'width:95%',
                                'id'		=>	'nguoi_ky'
                                ));
                     ?>
                     </div>
                     <div style="clear:both"></div>
                  </div>
                  
                  
                  <div style="padding-top:10px">
                    <?php
                     echo $this->Form->input('trich_yeu',
                              array(
                                   'label'	=>	'Trích yếu <span class="required">*</span>',
                                   'div'		=>	false,
                                   'class'	=>	'text-input',
                                   'style'	=>	'width:98.5%',
                                   'id'		=>	'trich_yeu'
                                   ));
                     ?>
                  </div>
	             <div style="padding-top:10px">
							<?php
                        echo $this->Form->input('noi_luu',
                           array(
                                'label'	=>	'Nơi lưu bản gốc hoặc bản chính <span class="required">*</span>',
                                'div'		=>	false,
                                'class'	=>	'text-input',
                                'style'	=>	'width:100%',
                                'multiple'	=>	'multiple',
                                'id'		=>	'noi_luu',
                                'value'	=>	$this->Session->read('Auth.User.phong_id'),
                                'options'	=>	$phong
                                ));
                     ?>
                  </div>
                  
                  <div style="padding-top:10px">
                  	<div style="width:33%; float:left">
                     <?php
								echo $this->Form->input('nguoi_duyet',
									array(
										  'label'	=>	'Người duyệt văn bản',
										  'div'		=>	false,
										  'class'	=>	'text-input',
										  'style'	=>	'width:100%',
										  'id'		=>	'nguoi_duyet'
										  ));
							?>
                     </div>
                     <div style="width:62%; float:right; padding-right:10px">
                     <?php
								echo $this->Form->input('noidung_duyet',
									array(
										  'label'	=>	'Nội dung phê duyệt',
										  'div'		=>	false,
										  'class'	=>	'text-input',
										  'style'	=>	'width:100%',
										  'id'		=>	'noidung_duyet'
										  ));
							?>
                     </div>
                     <div style="clear:both"></div>
                  </div>
                  
                  <p>
                  	<div style="line-height:30px">
                        <input type="button" class="button" id="btn-attachfile" value="Đính kèm file văn bản" title=""/>
                        <span style="padding-left: 10px"><i>(Chỉ cho phép các file có phần mở rộng là TIF, TIFF, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT và file có dung lượng nhỏ hơn <b><?php echo $file_limit ?></b> MB)</i></span>
                     </div>
                     <div id="file_list" style="padding:5px"></div>
                     <div id="upload_status"></div>
                 </p>
                </div> <!-- End #tab nội dung văn bản -->        
                
            </div> <!-- End .content-box-content -->
            
        </div> <!-- End .content-box -->
        
        <div class="content-box" style="width:40%; float:right" id="vanban_nhanvanban">
        </div> <!-- End .content-box -->
        <div class="clear"></div>
    
    </div>
<?php
	echo $this->Form->end();
?>
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