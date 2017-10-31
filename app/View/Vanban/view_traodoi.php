<?php //echo $this->element('sql_dump');  die();
//pr($data);die();
?>
<div style="padding:10px">
<div class="content-box" id="vanban-box"><!-- Start Content Box -->
    <div class="content-box-header">
        <h3><?php echo $this->Html->image('icons/page_white_magnify.png', array('align' => 'left')); ?>&nbsp; Kết quả trao đổi văn bản</h3>
        <div class="clear"></div>
    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="vanban-info">
        <!-- BOX INFO -->
        	<table width="100%">
            <tr>
            	<td width="80%">
                    <div>Số hiệu văn bản : <b><?php echo $data['Vanban']['so_hieu'] ?></b></div>
                    <div>Trích yếu : <b><?php echo $data['Vanban']['trich_yeu'] ?></b></div>
                    <div>Nội dung chỉ đạo : <b><?php echo $data['Vanban']['noidung_duyet'] ?></b></div>
                    <div>Ngày yêu cầu hoàn thành : <b><?php 
						if($data['Vanban']['vb_gap'] == 1)
						echo $this->Time->format("d-m-Y", $data['Vanban']['vbgap_ngayhoanthanh']) ?></b></div>
                    <div>Người cập nhật : <b><?php echo $data['Nhanviencapnhat']['full_name'] ?></b></div>
                    <div>Ngày cập nhật : <b><?php echo $this->Time->format("d-m-Y", $data['Traodoivanban']['ngay_capnhat']) ?> </b></div>
                    <div>Người nhận : <b><?php echo $data['Nhanviennhan']['full_name'] ?></b></div>
                    <div>Nội dung cập nhật : <b><?php echo $data['Traodoivanban']['noidung_capnhat'] ?></b></div>
                    </div>
                    <div>
                    	<?php if(!empty($data['Filetraodoi'])): ?>
                    	<br />
                        <b>File đính kèm : &nbsp;</b>
                        <br />
                        <?php
                            foreach($data['Filetraodoi'] as $file):
								echo $this->Html->link($file['ten_cu'], '/vanban/files_atttraodoi/' . $file['id']);
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