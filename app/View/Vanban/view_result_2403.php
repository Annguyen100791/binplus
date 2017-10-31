<?php //echo $this->element('sql_dump');  die();
//pr($data);die();
?>
<div style="padding:10px">
<div class="content-box" id="vanban-box"><!-- Start Content Box -->
    <div class="content-box-header">
        <h3><?php echo $this->Html->image('icons/page_white_magnify.png', array('align' => 'left')); ?>&nbsp; Kết quả xử lý văn bản</h3>
        <div class="clear"></div>
    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="vanban-info">
        <!-- BOX INFO -->
        	<table width="100%">
            <tr>
            	<td width="80%">
                    <div>Người cập nhật : <b><?php echo $data['Nhanvien']['full_name'] ?></b></div>
                    <div>Ngày yêu cầu hoàn thành : <b><?php echo $this->Time->format("d-m-Y", $data['Vanban']['vbgap_ngayhoanthanh']) ?></b></div>
                    <div>Ngày cập nhật : <b><?php echo $this->Time->format("d-m-Y", $data['Ketquavanban']['ngay_capnhat']) ?> </b></div>
                    <div>Nội dung cập nhật : <b><?php echo $data['Ketquavanban']['noidung_capnhat'] ?></b></div>
                    
                    <div>
                    	<?php if(!empty($data['Filebaocao'])): ?>
                    	<br />
                        <b>File đính kèm : &nbsp;</b>
                        <br />
                        <?php
                            foreach($data['Filebaocao'] as $file):
                               // pr($data['Filebaocao']);die();
								echo $this->Html->link($file['ten_cu'], '/vanban/files_attbaocao/' . $file['id']);
								//echo $this->Html->link($data['Filebaocao']['ten_cu'], '/vanban/files_att/' . $data['Filebaocao']['id']);
                                echo '<BR>';
                           endforeach;
                        ?>
                    	<?php endif;?>
                    </div>
                </td>
        </table>
        </div>
	</div>
</div>
</div>