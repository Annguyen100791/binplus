<?php 
	echo $this->Form->create('Congviec', array('id' => 'form-chitietcongviec')) ;
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('refer',array('id' => 'url_refer', 'value' => $refer));
	echo $this->Html->script(
		array(
			  'libs/jquery/ajaxupload.3.5'
			  )
	);
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get("post_max_size"));
	$max_memory = (int)(ini_get('memory_limit'));
	$file_limit = min($max_upload, $max_post, $max_memory);
		
?>
<div class="formPanel">
<p style="padding-top:15px">	
	<label>Mức độ hoàn thành công việc : <span class="required"></span></label>
	<?php
        echo $this->Form->input('Congviec.mucdo_hoanthanh', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'options'	=>	$progress));
    ?>
</p>
<p>
	<label>Ghi chú : </label>
	<?php
        echo $this->Form->input('Congviec.ghi_chu', array('label'	=>	false,
                                                         'div'		=>	false,
                                                         'class'	=>	'text-input',
						  								 'style'	=>	'width:97.5%',
														 'rows'	=>	4,
														 'id'	=>	'ghi_chu'
														 ));
    ?>	
</p>
<p>
	<div style="line-height:30px">
        <input type="button" class="button" id="btn-attachfile" value="Đính kèm file" title=""/>
        <span style="padding-left: 10px"><i>(Chỉ cho phép các file JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT và file có dung lượng nhỏ hơn <?php echo $file_limit ?> MB)</i></span>
     </div>
     <div id="file_list" style="padding:5px"></div>
     <div id="upload_status"></div>
</p>
</div>    
    <div style="text-align:right!important" class="dialog-footer">
    	<button class="button" id="btn-update-progress" type="submit">Cập nhật</button>
       
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<?php echo $this->Form->end();?>
<script>
	$(document).ready(function(){

		$('#btn-update-progress').click(function(){

			$('#form-chitietcongviec').submit()

			return false;

		});

		var row = 1;

		// init ajax upload
		new AjaxUpload($('#btn-attachfile'), {
				action: BIN.baseURL + 'congviec/attachfile_baocao',
				name: 'data[Congviec][file]',
				onSubmit: function(file, ext){
					if (! (ext && /^(tiff|tif|jpg|png|jpeg|gif|swf|flv|avi|wmv|mp3|zip|rar|doc|docx|xls|xlsx|ppt|pptx|pdf|odt|ott|odm|html|oth|ods|ots|odg|otg|odp|otp|odf|odb|oxt)$/.test(ext))){ 
					alert('Chỉ được phép upload các file có phần mở rộng là TIFF, TIF, JPG, PNG, GIF, ZIP, RAR, DOC, DOCX, XLS, XLSX, PPT, PPTX, PDF, ODT, OTT, ODM, HTML, OTH, ODS, OTS, ODG, OTG, ODP, OTP, ODF, ODB, OXT.');
						return false;
					}
					$('#upload_status').html('<img src="/img/circle_ball.gif">');
					f_FileUploading = true;
				},
				onComplete: function(file, response){
					//On completion clear the status
					$('#upload_status').text('');
					console.log(response);
					//Add uploaded file to list
					var jsonObj = eval('(' + response + ')');
					//alert(jsonObj);
					if(jsonObj.success)
					{
						$('#file_list').append('<div class="uploaded_item" id="row' + row + '"><div style="float:left">' + jsonObj.ten_cu + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_moi + '" onClick="remove_file(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Hủy bỏ file này"></a></div><div style="clear:both"></div><input type="hidden" name=data[Filecongviec]['+row+'][path] class="uploaded" value="' + jsonObj.path + '"><input type="hidden" name=data[Filecongviec]['+row+'][ten_cu] class="uploaded" value="' + jsonObj.ten_cu + '"><input type="hidden" name=data[Filecongviec]['+row+'][ten_moi] class="uploaded" value="' + jsonObj.ten_moi + '"></div>');
						row++;
					} else{

						$('#file_list').append('<div class="uploaded_err" id="row' + row + '"><div style="float:left">' + jsonObj.message + '</div><div style="float:right"><a href="javascript:void(0)" id="' + jsonObj.ten_cu + '" onClick="remove_message(this)" rel="' + row + '"><img src="/img/closelabel.png" title="Remove message"></a></div><div style="clear:both"></div></div>');
						row++;
					}
					f_FileUploading = false;
				}
			});
		});
</script>