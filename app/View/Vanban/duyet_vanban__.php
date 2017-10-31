<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Duyệt văn bản</h1></div>
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
	echo $this->Form->hidden('files', array('value' => isset($this->data['Filevanban']) ? json_encode($this->data['Filevanban']) : '', 'id' => 'attach_files'));
	$nguoinhan = array();
	if(!empty($this->data['Nhanvanban']))
		foreach($this->data['Nhanvanban'] as $item)
			array_push($nguoinhan, $item['nguoi_nhan_id']);
	if(!empty($nv_chucdanh))
		foreach($nv_chucdanh as $c)
			if (in_array($c['b']['id'],$nguoinhan) == false)
			array_push($nguoinhan, $c['b']['id']);
	if(!empty($nv_quyen))
		foreach($nv_quyen as $q)
			if (in_array($q['b']['id'],$nguoinhan) == false)
			array_push($nguoinhan, $q['b']['id']);			
	echo $this->Form->create('Vanban', array('id'	=>	'form-vanban-compose'));
	echo $this->Form->input('id');
	echo $this->Form->hidden('phong_phoihop_id', array('id' => 'phong_phoihop_id', 'value' =>$phong_phoihop_id ));
	echo $this->Form->hidden('phongchutri_id', array('id' => 'phongchutri_id', 'value' =>$phongchutri_id ));
	 
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));
?>	
    <div id="compose-list-content">
    	<div class="content-box" style="width:58%; float:left">
            <div class="content-box-header">
                <div>
                	<h3><?php echo $this->Html->image('icons/page_white_text.png', array('align' => 'left')) ?>&nbsp; Duyệt văn bản</h3>
                </div>
                <div style="float:right; padding:6px">
                	<a class="button" id="btn-vanban-add" style="font-weight:bold;" title="Click để Duyệt văn bản"><span style="padding:7px">Duyệt văn bản</span></a>
                </div>
                <div style="clear:both"></div>
            </div> <!-- End .content-box-header -->
            <div class="content-box-content">
                <div class="tab-content default-tab" id="vanban_leftcontent">
                  <!--<div class="content-box" style="width:58%; float:left"> -->
				  	<div style="padding-top:10px" id="don_vi_chu_tri">
                        <div style="float:left">
                        <b>Đơn vị chủ trì: &nbsp;
                        <span id='dvchutri_name'>
                        <?php 
                            echo $phong_chutri;
                        ?>
                        </span>
                        </b>
                        </div>
                        <div style="float:left; padding-left:10px">
						  <?php					   					  
                          echo $this->Html->link('Thay đổi ', '/vanban/duyet_vb_dvchutri_edit/'.$this->data['Vanban']['id'], array('title' => 'Click để thay đổi đơn vị chủ trì ','class'=>'shortcut-button', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
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
                	<div style="padding-top:10px"><b>Đơn vị phối hợp : &nbsp;</b>
                        <div id="txt-donviphoihop">
                        <br>
                        <?php 
							foreach($phong_phoihop as $it){echo '- &nbsp;'.$it.'<br>';}
                        ?>
                        </div>
                    </div>
                    <?php if(!empty($this->data['Filevanbanduyet'])): ?>
                        <br>
                        <b>File đính kèm : &nbsp;</b>
                        <div>
                        <br>
                        <?php
                            foreach($this->data['Filevanbanduyet'] as $file):
                                echo $this->Html->link($file['ten_cu'], '/vanban/files_att/' . $file['id']);
                                echo '<BR>';
                            endforeach;
                        ?>
                    <?php endif;?>
                    	</div>
                    <br>
                    <div><b>Nội dung trình : &nbsp;</b>
                    	<div>
                            <br>
                            <div><?php echo $this->data['Vanban']['noidung_trinh']; ?></div>
                        </div>
                    </div>

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
                  <div class="tab-content default-tab" id="vanban_leftcontent">
					<?php 
						if($this->Session->read('Auth.User.nhanvien_id') == 681):
		 		  ?>
                    <div>
                        <div style="float:left; width:20%">
                            <!--<input id = "importance" type="checkbox" name="data[Vanban][importance]" /> <b>VB Quan trọng</b> -->
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
                            <div style="float:left; width:30%">
                               <!-- <input id = "chuyen_nguoiduyet" type="checkbox" name="data[Vanban][chuyen_nguoiduyet]" onchange="chuyenChange(this)" /> <b>Chuyển PGĐ duyệt</b> -->
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

                            </div>

                            <div style="float:left; width:45%; padding-left: 10px;" id="nhanvien_duyetvb">

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
                      	<?php 
						endif;
						?>
                    	<div style="clear:both"></div>

                  	</div>

                  

                  </div>

                  <div style="padding-top:10px" id="vbtime">

                  	<div style="float:left">

                    <div class="checkbox"> 

      </div>

                    
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

              		  <div style="float:left; padding-left:10px; width:300px" id="vbgap_thoigian">
                        	<b>Ngày hoàn thành:</b><input name="data[Vanban][vbgap_ngayhoanthanh]" id="vbgap_ngayhoanthanh" value="<?php echo $this->Time->format('d-m-Y', time()) ?>" class="text-input" size="15"/>
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

<script>
	$(document).ready(function(){
		$('#noidung_duyet').focus();
		$('#vbgap_thoigian').hide();
		$('#vbgap_ngayhoanthanh').datepicker({dateFormat: "dd-mm-yy"});
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
