<style>
	.msg_content{
		/*padding:10px 0;*/
	}
</style>
<?php
	//pr($this->data); die();
?>

<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Xem chi tiết tin nhắn đã gửi</h1></div>
    <div style="float:right;padding-right: 20px">
    	<a href="/tinnhan/edit/<?php echo $this->data['Tinnhan']['id'];?>" class="button" title="Click để soạn thảo tin nhắn">Cập nhật</a>
        <a href="/tinnhan/compose/forwardto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để chuyển tiếp tin nhắn">Chuyển tiếp</a>
        <a href="javascript:void(0)" class="button" title="Click để xóa tin nhắn này" id="btn-tinnhan-delete" data-id="<?php echo $this->data['Tinnhan']['id'] ?>">Xóa</a>
        <a href="/tinnhan/" class="button" title="Click để quay về mục Quản lý tin nhắn">Danh sách</a>    </div>
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
    <td style="vertical-align:top">
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner" style="padding:20px">
     <?php
		echo $this->Session->flash();
	?>
        <table width="95%" cellpadding="4" cellspacing="4" style="line-height:20px">
        	<tr>
            	<td width="150px"><label>Người gửi : </label></td>
                <td><?php echo $this->Html->link($this->data['Nguoigui']['full_name'], '/nhanvien/view/' . $this->data['Tinnhan']['nguoigui_id'], array('title' => 'Xem chi tiết nhân viên')); ?></td>
            </tr>
            <tr>
            	<td><label>Ngày gửi : </label></td>
                <td>
                	<?php
						echo $this->Time->format("d-m-Y H:i:s", $this->data['Tinnhan']['ngay_gui']);
					?>
                </td>
            </tr>
            <tr>
            	<td width="150px"><label>Người nhận : </label></td>
                <td>
                <?php
					//foreach($this->data['Chitiettinnhan'] as $nguoinhan)
					if(count($this->data['Chitiettinnhan']) == 1)
					{
						echo $this->Html->link($this->data['Chitiettinnhan'][0]['Nguoinhan']['full_name'], '/nhanvien/view/' . $this->data['Chitiettinnhan'][0]['nguoinhan_id']);
						if(!empty($this->data['Chitiettinnhan'][0]['ngay_nhan']))
							echo ' đã nhận vào lúc ' . $this->Time->format('H:i:s d-m-Y', $this->data['Chitiettinnhan'][0]['ngay_nhan']);
					}
					else
						echo $this->Html->link('Nhiều người', '/tinnhan/view_nguoinhan/' . $this->data['Tinnhan']['id'], array('title' => 'Xem danh sách người nhận', 'data-mode' => 'ajax', 'data-action' => 'dialog'));
						
				?>
                </td>
            </tr>
            <tr>
            	<td><label>Tiêu đề : </label></td>
                <td>
                	<?php
						echo $this->data['Tinnhan']['tieu_de']
					?>
                </td>
            </tr> 
				<tr>
            	<td style="vertical-align:top"><label><b>Nội dung : </b></label></td>
                <td>               
                  <?php echo $this->data['Tinnhan']['noi_dung']; ?>				
                </td>
            </tr>							
            
             <?php if(!empty($this->data['Tinnhan']['noi_dung_capnhat'])){
			?>
            <tr>
            	<td style="vertical-align:top"><label style="color:#009"><b>Nội dung cũ:</b> </label></td>
                <td>
                <div id="message-content" style="color:#009">
                   <?php
					echo $this->data['Tinnhan']['noi_dung_capnhat']
				?>
                </div>
                </td>
            </tr>
            <?php
			} ?>
            <tr>
            	<td></td>
               </tr>
            <?php  
			if(!empty($this->data['FileTinnhan'])): ?>
            <tr>
            	<td><label>File đính kèm : </label></td>
                <td style="padding:5px 0">
                 <?php
					//foreach($this->data['Tinnhan']['FileTinnhan'] as $file):
						//pr($file);die();
						/*echo $this->Html->link($file['file_path'], Configure::read('TinNhan.attach_path') . $file['file_path'], array('target' => '_blank'));*/
						//echo $this->Html->link($file['file_path'], 'get_files/' . $file['id']);
						//echo '<BR>';
					//endforeach;
				?>
                
				<?php
					foreach($this->data['FileTinnhan'] as $file):
					echo $this->Html->link($file['file_path'], 'get_files/' . $file['id']);
						/*echo $this->Html->link($this->Bin->hidetimelabel($file['file_path']), Configure::read('TinNhan.attach_path') . $file['file_path'], array('target' => '_blank'));*/
						echo '<BR>';
					endforeach;
				?>
                </td>
            </tr>
            <?php endif;?>
        </table>
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