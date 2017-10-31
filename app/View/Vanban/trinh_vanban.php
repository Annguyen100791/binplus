<?php
	echo $this->Html->script(
		array(
			  'jquery.gdocsviewer.min'
			  )
	);
?>
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
	//$nguoinhan = array('681','683','687','709','744');
	if($this->Session->read('Auth.User.donvi_id') == '')
		$nguoinhan = array('681','894');
	else
		$nguoinhan = array();
	echo $this->Form->create('Vanban', array('id'	=>	'form-vanban-compose'));
	echo $this->Form->input('id');
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));
	//pr($phong_phoihop_id);die();
	//echo $this->Form->hidden('phong_phoihop_id', array('id' => 'phong_phoihop_id', 'value' =>$phong_phoihop_id));
?>
    <div id="compose-list-content">
    
    	<div class="content-box" style="width:58%; float:left">
				
            <div class="content-box-header">
                <div>
                	<h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Trình văn bản</h3>
                </div>
                <div style="float:right; padding:6px">
                	<a class="button" id="btn-vanban-add" style="font-weight:bold;" title="Click để Trình văn bản"><span style="padding:7px">Trình văn bản</span></a>
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
                     <?php 
						//if($this->Session->read('Auth.User.donvi_id') == ''):
					 ?>
                     <div style="float:left; width:40%">
                     	<?php
									echo $this->Form->input('phongchutri_id',
										array(
											  'label'	=>	'Đơn vị chủ trì <span class="required">*</span>',
											  'div'		=>	false,
											  'class'	=>	'text-input',
											  'style'	=>	'width:100%',
											  'id'		=>	'phongchutri_id',
											  'options'	=>	$phong_chutri,
											   'empty'	=>	'---Chọn đơn vị chủ trì---'
											  ));
								?>
                     </div>
                     <?php 
					 	//endif;
					 ?>
                     <p>
                     </p>
                     <div style="float:left; width:20%; padding-left:20px;">
                     	<input type="checkbox" name="data[Vanban][chuyen_bypass]" /> <b>Chuyển Bypass</b>
                     </div>
                     <div style="float:left; width:20%; padding-left:20px;">
                     	<input type="checkbox" name="data[Vanban][uy_quyen]" /> <b>VB ủy quyền</b>
                     </div>
                     <div style="clear:both"></div>
                   </div>
                  </p>
                  <p>
                  	<div style="line-height:30px">
                        <input type="button" class="button" id="btn-attachfile" value="Đính kèm file" title=""/>
                        <span style="padding-left: 10px"><i>(Chỉ cho phép các file JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT và file có dung lượng nhỏ hơn <?php echo $file_limit ?> MB)</i></span>
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
<?php 
	if(!empty($files)) : 
	for ($i = 0; $i < count($files); ++$i)
	{
		$arr = explode(".", $files[$i]['Filevanban']['path']);
		$ext = strtolower(end($arr));
		//pr($arr);pr($ext);die();
			echo '<a name="xem_chitiet" id="xem_chitiet"></a>';
			if($is_mobile || $ext != 'tif')
			{
				echo $this->element('googledocs_viewer_duyet');
			}else
				echo $this->element('tiff_viewer_duyet');
	}
	endif;
			
?> 
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
