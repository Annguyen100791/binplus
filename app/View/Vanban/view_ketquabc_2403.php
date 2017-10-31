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
                    <div>Số hiệu văn bản : <b><?php echo $data[0]['Vanban']['so_hieu'] ?></b></div>
                    <div>Trích yếu văn bản : <b><?php echo $data[0]['Vanban']['trich_yeu'] ?></b></div>
                    <div>Ngày yêu cầu hoàn thành : <b><?php echo $this->Time->format("d-m-Y", $data[0]['Vanban']['vbgap_ngayhoanthanh']) ?></b></div>
                    <hr>
					<?php 
					
					foreach($data as $item)
					{ 
					//pr($item);die();
					?>
                        <div>Người nhận : <b><?php echo $item['Nhanviennhan']['full_name'] ?></b></div>
                        <div>Ngày cập nhật : <b><?php echo $this->Time->format("d-m-Y", $item['Ketquavanban']['ngay_capnhat']) ?> </b></div>
                    	<div>Nội dung cập nhật : <b><?php echo $item['Ketquavanban']['noidung_capnhat'] ?></b></div>
                        <?php if(!empty($item['Filebaocao'])): ?>
                        	<b>File đính kèm : &nbsp;</b>
                        <br />
                        <?php
                            foreach($item['Filebaocao'] as $file):
								echo $this->Html->link($file['ten_cu'], '/vanban/files_attbaocao/' . $file['id']);
                                echo '<BR>';
                           endforeach;
                        ?>
                        
                        <?php endif; ?>
                      	<hr>
					<?php
                    }
					?>
                    
                </td>
        </table>
        </div>
	</div>
</div>
</div>