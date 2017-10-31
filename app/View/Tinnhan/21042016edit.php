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
<style>
.dynatree-container{
	height: 560px!important; overflow:auto
}
</style>
<!--  start page-heading -->

<div id="page-heading">
	<div style="float:left"><h1>Cập nhật tin nhắn</h1></div>
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
	echo $this->Session->flash();
	echo $this->Form->hidden('files', array('value' => isset($tinnhan['FileTinnhan']) ? json_encode($tinnhan['FileTinnhan']) : '', 'id' => 'attach_files'));
	$nguoinhan = array();
	//pr($ds_nguoinhan);die();
	//pr($this->data['Chitiettinnhan']); die();
	if(isset($tinnhan) && !empty($tinnhan))
		foreach($tinnhan['Chitiettinnhan'] as $item)
			array_push($nguoinhan, $item['nguoinhan_id']);		
			/*$check = in_array($nguoi_gui,$nguoinhan ); 
			if(!$check)
			array_push($nguoinhan, $nguoi_gui);*/

//pr($nguoinhan);die();
	echo $this->Form->create('Tinnhan', array('id'	=>	'form-tinnhan-edit'));
	echo $this->Form->hidden('noi_dung', array('value' => $tinnhan['Tinnhan']['noi_dung']));
	echo $this->Form->hidden('ngay_gui', array('value' => $tinnhan['Tinnhan']['ngay_gui']));
	//pr($sl);die();
	$sl=count($nguoinhan);
		if( $sl > 1 )
			{echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));}
		else
			{echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => $nguoinhan));}
	
	if(isset($related_id))
		echo $this->Form->hidden('related_id', array('value' => $related_id));
?>
    <div id="compose-list-content">
    
    	<div class="content-box" style="width:62%; float:left">
				
            <div class="content-box-header">
                <div style="float:left">
                <h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Nội dung tin nhắn</h3>
                </div>
                <div style="float:right; padding:5px 15px">
                	<input type="checkbox" name="data[Tinnhan][chuyen_tiep]" /> <b>Không chuyển tiếp ?</b>
                    <button class="btn-submit button" type="button" style="font-weight:bold">Gửi tin nhắn</button>
                </div>
                <div style="clear:both"></div>
            </div> <!-- End .content-box-header -->
            
            <div class="content-box-content">
                
                <div class="tab-content default-tab" id="tinnhan_leftcontent">
                	<p>
                    	<?php
							
							echo $this->Form->input('tieu_de',
								array(
									  'label'	=>	'Tiêu đề <span class="required">*</span>',
									  'div'		=>	false,
									  'class'	=>	'text-input',
									  'style'	=>	'width:97.5%',
									  'id'		=>	'tieu_de',
									  'title'	=>	'Tiêu đề của tin nhắn',
									  'value'	=>	'[CN]: '.$tinnhan['Tinnhan']['tieu_de']
									  ));
						?>
                    </p>
                    <p>
                   
                    	<?php
							echo $this->Form->input('noi_dung_capnhat',
								array(
									  'label'	=>	'Nội dung cập nhật <span class="required">*</span>',
									  'div'		=>	false,
									  'class'	=>	'text-input',
									  'rows'	=>	3,
									  'style'	=>	'width:97.5%',
									  'id'		=>	'noi_dung_capnhat',					  
									  'value'	=>	$tinnhan['Tinnhan']['noi_dung_capnhat']
									 
									  ));
						?>
                    </p>
                    <p>
                   
                    	<?php
						echo  '<b>Nội dung:</b> <br>'.$tinnhan['Tinnhan']['noi_dung'];
							/*echo $this->Form->input('noi_dung',
								array(
									  'label'	=>	'Nội dung <span class="required">*</span>',
									  'div'		=>	false,
									  'class'	=>	'text-input',
									  'rows'	=>	3,
									  'style'	=>	'width:97.5%',
									  'id'		=>	'noi_dung',					  
									  'value'	=>	$tinnhan['Tinnhan']['noi_dung']
									 
									  ));*/
						?>
                    </p>
                     
                    <p>
                    	<label>File đính kèm (Chỉ cho phép các file JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT và file có dung lượng nhỏ hơn <?php echo $file_limit ?> MB)</label>
                        <div id="file_list" style="padding:5px"></div>
                        <div id="upload_status"></div>
                        <div>
                        	<div style="float:left">
                            <button class="button" type="button" id="btn-attachfile" style="font-weight:bold">Đính kèm file</button>
                            </div>
                            <div style="float:right">
                            	<button class="button" type="submit" style="font-weight:bold">Gửi tin nhắn</button>
                            </div>
                            <div style="clear:both"></div>
						</div>
                    </p>
                </div> <!-- End #tab3 -->        
                
            </div> <!-- End .content-box-content -->
            
        </div> <!-- End .content-box -->
        
        <div class="content-box" style="width:36%; float:right" id="tinnhan_nhantinnhan">
        </div> <!-- End .content-box -->
        <div class="clear"></div>
    
    </div>
	</div>
    <!--  end content-table-inner ............................................END  -->
    <?php
	echo $this->Form->end();
?>
    </td>
    <td id="tbl-border-right"></td>
</tr>
<tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
</tr>
</table>