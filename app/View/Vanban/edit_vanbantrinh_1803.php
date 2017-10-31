<!--  start page-heading -->
<!-- end page-heading -->

<?php
	echo $this->Html->script(
		array(
			  'libs/jquery/ajaxupload.3.5',
			  'libs/tinymce/tinymce.min'
			  )
	);
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get("post_max_size"));
	$max_memory = (int)(ini_get('memory_limit'));
	$file_limit = min($max_upload, $max_post, $max_memory);
?>
    
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
			  'libs/tinymce/tinymce.min'
			  )
	);
	echo $this->Session->flash();
	//pr($this->data['Filevanbanduyet']);die();
	echo $this->Form->hidden('files', array('value' => isset($this->data['Filevanbanduyet']) ? json_encode($this->data['Filevanbanduyet']) : '', 'id' => 'attach_files'));
	$nguoinhan = array();
	if(!empty($this->data['Nhanvanban']))
		foreach($this->data['Nhanvanban'] as $item)
			array_push($nguoinhan, $item['nguoi_nhan_id']);
	echo $this->Form->create('Vanban', array('id'	=>	'form-vanban-compose'));
	echo $this->Form->input('id');
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));
	//pr($nguoinhan);die();
?>
    <div id="compose-list-content">
    
    	<div class="content-box" style="width:58%; float:left">
				
            <div class="content-box-header">
                <div>
                	<h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Trình văn bản</h3>
                </div>
                <div style="float:right; padding:6px">
                	<a class="button" id="btn-vanban-add" style="font-weight:bold;" title="Click để Trình lại văn bản"><span style="padding:7px">Trình lại văn bản</span></a>
                </div>
                <div style="clear:both"></div>
            </div> <!-- End .content-box-header -->
            
            <div class="content-box-content">
                
                <div class="tab-content default-tab" id="vanban_leftcontent">
                  <!--<div class="content-box" style="width:58%; float:left"> -->

                  <div style="padding-top:10px">
                    <?php
                     echo $this->Form->input('noidung_trinh', 
								array(
									  'label'	=>	'Nội dung <span class="required">*</span>',
									  'div'		=>	false,
									  'class'	=>	'text-input',
									  'rows'	=>	7,
									  'style'	=>	'width:97.5%',
									  'id'		=>	'noidung_trinh'
									  ));
                     ?>
                  </div>
                  
                  <div style="padding-top:10px">
                     <div style="clear:both"></div>
                  </div>
                  <p>
                    <div style="line-height:30px">
                        <div style="float:left; width:33%">
                     	<?php
							echo $this->Form->input('nguoi_duyet_id',
									array(
										  'label'	=>	'Trình lãnh đạo <span class="required">*</span>',
										  'div'		=>	false,
										  'class'	=>	'text-input',
										  'style'	=>	'width:100%',
										  'id'		=>	'nguoi_duyet_id',
										  'options'	=>	$nhanviennhan
										  ));
						?>
                     </div>
                     <div style="float:left; width:44%">
                     	<?php
									echo $this->Form->input('phongchutri_id',
										array(
											  'label'	=>	'Đơn vị chủ trì <span class="required">*</span>',
											  'div'		=>	false,
											  'class'	=>	'text-input',
											  'style'	=>	'width:100%',
											  'id'		=>	'phongchutri_id',
											  'options'	=>	$phong_chutri,
											  'empty'	=>	false
											  ));
								?>
                     </div>
                     <div style="float:left; width:23%">
                     	<label><?php
								echo $this->Form->checkbox('chuyen_bypass', array(
										 'id'		=>	'chuyen_bypass',
												 ));
								?>&nbsp;&nbsp; Chuyển Bypass</label>
                     	
                     </div>
                     <div style="clear:both"></div>
                   </div>
                  </p>
                  <p style="padding-top:10px">
                    	<label>File đính kèm (Chỉ cho phép các file TIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, PPT, PPTX và file có dung lượng nhỏ hơn <?php echo $file_limit ?> MB)</label>
                        <div id="file_list" style="padding:5px"></div>
                        <div id="upload_status"></div>

                  </p>
                  <p>
                  	<div style="line-height:30px">
                        <input type="button" class="button" id="btn-attachfile" value="Đính kèm file" title=""/>
                        <span style="padding-left: 10px"><i>(Chỉ cho phép các file có phần mở rộng là TIF, TIFF, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF và file có dung lượng nhỏ hơn <b><?php echo $file_limit ?></b> MB)</i></span>
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
<script>
	$(document).ready(function(){
		$('#noidung_trinh').focus();

		tinymce.init({
			selector: '#noidung_trinh',
			//height: 400,
			language: 'vi_VN',
			plugins: BIN.editorPlugins,
			toolbar: BIN.editorToolbar,
			theme_advanced_toolbar_location : 'top', 
			theme_advanced_toolbar_align : 'left', 
			theme_advanced_statusbar_location : 'bottom', 
			theme_advanced_resizing : true, 
			theme_advanced_resize_horizontal : false, 
			convert_fonts_to_spans : true, 
			convert_urls : false,
			force_br_newlines : true,
			force_p_newlines 	: false,
			forced_root_block	: '',
			file_browser_callback: BIN.editorFileBrowser
		});
	});
</script>
