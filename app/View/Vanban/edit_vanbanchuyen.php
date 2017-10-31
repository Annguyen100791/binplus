<?php
	echo $this->Html->script(
		array(
			  'jquery.gdocsviewer.min'
			  )
	);
?>
<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Duyệt lại văn bản</h1></div>
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
	echo $this->Html->script(
		array(
			  'libs/tinymce/tinymce.min'
			  )
	);
	echo $this->Html->script(
		array(
			  'libs/jquery/ajaxupload.3.5',
			  )
	);
	echo $this->Session->flash();
	echo $this->Form->hidden('files', array('value' => isset($this->data['Filevanbanduyet']) ? json_encode($this->data['Filevanbanduyet']) : '', 'id' => 'attach_files'));
	$nguoinhan = array();
	if(!empty($this->data['Nhanvanban']))
		foreach($this->data['Nhanvanban'] as $item)
			array_push($nguoinhan, $item['nguoi_nhan_id']);
	echo $this->Form->hidden('sl_nhan', array('id' => 'sl_nhan', 'value' =>count($nhanviennhan)));
	echo $this->Form->create('Vanban', array('id'	=>	'form-vanban-compose'));
	echo $this->Form->input('id');
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));
	echo $this->Form->hidden('phong_phoihop_id', array('id' => 'phong_phoihop_id', 'value' =>$phong_phoihop_id ));
	echo $this->Form->hidden('phongchutri_id', array('id' => 'phongchutri_id', 'value' =>$phongchutri_id ));
	echo $this->Form->hidden('chucdanh_id', array('id' => 'chucdanh_id', 'value' =>$this->Session->read('Auth.User.chucdanh_id')));

?>	
     <div id="compose-list-content">
    	<div class="content-box" style="width:58%; float:left">
            <div class="content-box-header">
                <div>
                	<h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Duyệt lại văn bản</h3>
                </div>
                <div style="float:right; padding:6px">
                	<a class="button" id="btn-vanban-add" style="font-weight:bold;" title="Click để Duyệt lại văn bản"><span style="padding:7px">Duyệt lại</span></a>
                </div>
                <div style="clear:both"></div>
            </div> <!-- End .content-box-header -->
            <div class="content-box-content">
                <div class="tab-content default-tab" id="vanban_leftcontent">
                  <!--<div class="content-box" style="width:58%; float:left"> -->
				  	<div style="padding-top:10px" id="don_vi_chu_tri">
                        <div style="float:left">
                        <b>Đơn vị chủ trì : &nbsp;
                        <span id='dvchutri_name'>
                        <?php 
                            echo $phong_chutri;
                        ?>
                        </span>
                        </b>
                        </div>
                        <div style="float:left; padding-left:10px">
						  <?php					   					  
                          echo $this->Html->link('Thay đổi ', '/vanban/duyet_vb_dvchutri_edit/'.$this->data['Vanban']['id']."/". $phongchutri_id, array('title' => 'Click để thay đổi đơn vị chủ trì ','class'=>'shortcut-button', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
                          ?>
                        </div>
                        
                        <div style="float:right">
                        
                        <?php 
							if($this->Session->read('Auth.User.nhanvien_id') != 681){
								if(empty($this->data['Theodoivanban']))
								{
									?>
                                    <b>Đánh dấu văn bản cần theo dõi: &nbsp;
                                    <?php								
							  		echo $this->Html->link($this->Html->image('icons/star.png', array('class' => 'icon-button')), '/vanban/danhdau_theodoi/type:/target:/id:' . $this->data['Vanban']['id'], array('escape' => false, 'title' => 'Theo dõi văn bản này', 'class' => 'follow', 'data-mode' => 'ajax', 'data-action' => 'dialog'));
								}
								else
								{
									foreach($this->data['Theodoivanban'] as $i_theodoi)
									if($this->Session->read('Auth.User.nhanvien_id') != $i_theodoi['nguoi_theodoi_id'] ) {
										?>
										<b>Đánh dấu văn bản cần theo dõi: &nbsp;
										<?php								
										echo $this->Html->link($this->Html->image('icons/star.png', array('class' => 'icon-button')), '/vanban/danhdau_theodoi/type:/target:/id:' . $this->data['Vanban']['id'], array('escape' => false, 'title' => 'Theo dõi văn bản này', 'class' => 'follow', 'data-mode' => 'ajax', 'data-action' => 'dialog'));
									} 
								}
							}
                        ?></b>
                        </div>
                        
                        <div style="clear:both"></div>
                	</div>
                	<?php if($this->Session->read('Auth.User.donvi_id') == '') :?>  
                        <div style="padding-top:10px"><b>Đơn vị phối hợp : &nbsp;</b>
                            <div id="txt-donviphoihop">
                            <br>
                            <?php 
                                foreach($phong_phoihop as $it){echo '- &nbsp;'.$it.';<br>';}
                            ?>
                            </div>
                        </div>
					<?php endif;?>
                    <?php if(!empty($this->data['Filevanbanduyet'])): ?>
                    	<br />
                        <b>File đính kèm : &nbsp;</b>
                        <br />
                        <?php
                            foreach($this->data['Filevanbanduyet'] as $file):
                                echo $this->Html->link($file['ten_cu'], '/vanban/files_att/' . $file['id']);
                                echo '<BR>';
                            endforeach;
                        ?>
                    <?php endif;?>
                    <br/>
                    <div><b>Nội dung trình : &nbsp;</b>
					<br>
					<?php 

						echo $this->data['Vanban']['noidung_trinh'] ;

					?></b></div>

                  <div style="padding-top:10px">
					<?php

                     echo $this->Form->input('noidung_duyet', 

								array(

									  'label'	=>	'Nội dung duyệt <span class="required">*</span>',

									  'div'		=>	false,

									  'class'	=>	'text-input',

									  'rows'	=>	7,

									  'style'	=>	'width:97.5%',

									  'id'		=>	'noidung_duyet'

									  ));
                     ?>
                  </div>
                  <div style="padding-top:10px">
                     <div style="clear:both"></div>
                  </div>
                  <div class="tab-content default-tab" id="vanbaneditchuyen_leftcontent">
                    <div class="vb_importance">
                    <?php
						if($this->Session->read('Auth.User.nhanvien_id') == 681): // dành riêng cho GĐ VTĐN
		 		  ?>
                        <div style="float:left; width:20%">
                             <?php
								echo $this->Form->input('importance',
										array(
											  'label'	=>	false,
											  'type'	=> 'checkbox',
											  'after' => '<b>&nbsp; VB quan trọng &nbsp;
					</b>',
											  'id'		=>	'importance'
											  ));
								?>
                        </div>
                  <?php endif;?>
				 </div>
                 <!-- Thêm mới -->
                 <div class="vb_chuyen_checkbox">
                  <?php if($this->Session->read('Auth.User.chucdanh_id') == 2 || ($this->Session->read('Auth.User.chucdanh_id') == 3 && $this->data['Vanban']['chuyen_nguoiduyet'] != 1) || $this->Session->read('Auth.User.chucdanh_id') == 30 ): ?>
                           <!-- Chỉnh sửa -->
                            <div style="float:left; width:30%">
                                <?php
								echo $this->Form->input('chuyen_nguoiduyet',

										array(

											  'label'	=>	false,

											  'type'	=> 'checkbox',

											  'after' => '<b>&nbsp; Chuyển PGĐ duyệt &nbsp;

					</b>',

											  'id'		=>	'chuyen_nguoiduyet',

											  'onchange'	=>	'chuyenChange(this)'

											  ));
								?>
								<div style="float:left; width:100%" id="chuyenpgd_khac">
										<?php
											echo $this->Form->input('pgd_khac',
													array(
															'label'	=>	false,
															'type'	=> 'checkbox',
															'after' => '<b>&nbsp; Thêm PGĐ duyệt &nbsp;
									</b>',
															'id'		=>	'pgd_khac',
															'onchange'	=>	'chuyenpgdChange(this)'
															));
											?>
								</div>
                            </div>

                            <div style="float:left; width:50%; padding-left: 10px;" id="nhanvien_duyetvb">

                                <?php

                                    echo $this->Form->input('nguoi_duyet_id',

                                            array(

                                                  'label'	=>	'Người nhận <span class="required">*</span>',

                                                  'div'		=>	false,

                                                  'class'	=>	'text-input',

                                                  'id'		=>	'nguoi_duyet_id',

                                                  'options'	=>	$nhanviennhan

                                                  ));

                                ?>

                                <div style="float:right; padding-top:2px; ">

                                    <a class="button" id="btn-vanban-chuyen" style="font-weight:bold;" title="Click để Chuyển văn bản"><span style="padding:7px">Chuyển văn bản</span></a>

                                </div>

                            </div>
                            
                  <?php endif; ?>

				<!-- Thêm mới -->
                  <?php if($this->Session->read('Auth.User.nhanvien_id') == 681 || ($this->Session->read('Auth.User.nhanvien_id') == 784 )): ?>


                            <div style="float:left; width:50%; padding-left: 10px;" id="nhanvienkhac_duyetvb">

                                <?php

                                    echo $this->Form->input('nguoi_duyetkhac_id',

                                            array(

                                                  'label'	=>	'Người nhận <span class="required">*</span>',

                                                  'div'		=>	false,

                                                  'class'	=>	'text-input',

                                                  'id'		=>	'nguoi_duyetkhac_id',

                                                  'options'	=>	$pgd_konhan

                                                  ));

                                ?>
                                <div style="float:right; padding-top:2px; ">

                                    <a class="button" id="btn-vanban-chuyenpgd" style="font-weight:bold;" title="Click để Chuyển văn bản"><span style="padding:7px">Chuyển văn bản</span></a>

                                </div>
                            </div>
                  <?php endif; ?>


                    	<div style="clear:both"></div>

                  	</div>
                 <!-- Kết thúc Thêm mới -->
                    	<div style="clear:both"></div>

                  </div>

                  <div style="padding-top:10px" id="vbtime">

	                  	<div style="float:left;width:30%;">

	                    <div class="checkbox"></div>


						<?php


										echo $this->Form->input('vb_gap',

											array(

												  'label'	=>	false,

												  'type'	=> 'checkbox',

												  'after' => '<b>&nbsp;VB giao ngày hoàn thành &nbsp;

						</b>',

												  'id'		=>	'vb_gap',

												  'onchange'	=>	'VBGapChange(this)'

												  ));

						?>

	                    </div>

	              		  <div style="float:left; padding-left:10px; width:45%;vertical-align: middle;" id="vbgap_thoigian">
	                        	<?php if($this->data['Vanban']['vb_gap'] == 0) :?>
	                            <b style="line-height:2.5em;">Ngày hoàn thành:</b><input style="float:right;padding-top:2px;" name="data[Vanban][vbgap_ngayhoanthanh]" id="vbgap_ngayhoanthanh" value="<?php echo $this->Time->format('d-m-Y', time()) ?>" class="text-input" size="15"/>
	                            <?php else : ?>
	                            <b style="line-height:2.5em;">Ngày hoàn thành:</b><input style="float:right;padding-top:2px;" name="data[Vanban][vbgap_ngayhoanthanh]" id="vbgap_ngayhoanthanh" value="<?php echo $this->Time->format('d-m-Y', $this->data['Vanban']['vbgap_ngayhoanthanh']) ?>" class="text-input" size="15"/>
	                            <?php endif;?>
	                     </div>
	                	</div>
                  <p>
                    <div style="line-height:30px">

	                  <input type="hidden" id="btn-attachfile" /> 

                     <div style="clear:both"></div>

                   </div>

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
		$('#noidung_duyet').focus();
		$('#vbgap_thoigian').hide();
		if ($("#vb_gap").is(":checked")) {  
			$('#vbgap_thoigian').show();
			$('#vbgap_ngayhoanthanh').datepicker({dateFormat: "dd-mm-yy"});
			//$('#vb_gap').attr('disabled','disabled');
			/*$('#vb_gap')[0].addEventListener('click',function(e){e.preventDefault();return false;});
			$('#vb_gap')[0].addEventListener('keydown',function(e){e.preventDefault();return false;})*/;
			//var ngay = $('#vbgap_ngayhoanthanh').val();
			//$('#vbgap_ngayhoanthanh').datepicker({
				//dateFormat: "dd-mm-yy",
				//maxDate: ngay
			//});
		} else {
			// checkbox is not checked 
			$('#vbgap_thoigian').hide();
			//$('#vbgap_ngayhoanthanh').datepicker({dateFormat: "dd-mm-yy"});
		}
		/*if($("#chucdanh_id").val() == 2 || $("#chucdanh_id").val() == 30) // GĐ VTĐN và GĐ Trung tâm
		{
			if ($("#vb_gap").is(":checked")) {  
				$('#vbgap_thoigian').show();
				$('#vbgap_ngayhoanthanh').datepicker({dateFormat: "dd-mm-yy"});
			} else {
				// checkbox is not checked 
				$('#vbgap_thoigian').hide();
			}
		}
		else
		{
			if ($("#vb_gap").is(":checked")) {  
				$('#vbgap_thoigian').show();
				$('#vb_gap')[0].addEventListener('click',function(e){e.preventDefault();return false;});
				$('#vb_gap')[0].addEventListener('keydown',function(e){e.preventDefault();return false;});
				var ngay = $('#vbgap_ngayhoanthanh').val();
				$('#vbgap_ngayhoanthanh').datepicker({
					dateFormat: "dd-mm-yy",
					maxDate: ngay
				});
			} else {
				// checkbox is not checked 
				$('#vbgap_thoigian').hide();
				$('#vbgap_ngayhoanthanh').datepicker({dateFormat: "dd-mm-yy"});
			}
		}*/
		tinymce.init({
			selector: '#noidung_duyet',
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
<style>
	form label {padding: 0 0 10px;font-weight: bold}
</style>

