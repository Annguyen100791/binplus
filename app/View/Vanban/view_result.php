
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
                    <div>Số hiệu văn bản : <b><?php echo $data['Vanban']['so_hieu'] ?></b></div>
                    <div>Trích yếu : <b><?php echo $data['Vanban']['trich_yeu'] ?></b></div>
                    <div>Lãnh đạo duyệt : <b><?php echo $data['Vanban']['nguoi_duyet'] ?></b></div>
                    <div>Nội dung chỉ đạo : <b><?php echo $data['Vanban']['noidung_duyet'] ?></b></div>
                    <div>Ngày yêu cầu hoàn thành(deadline) : <b>
					<?php if($data['Vanban']['vb_gap'] == 1)
							echo $this->Time->format("d-m-Y", $data['Vanban']['vbgap_ngayhoanthanh']) 
							
					?></b></div>
                   	<div>Ngày duyệt : <b>
					<?php if($data['Vanban']['ngay_duyet'] != NULL)
							echo $this->Time->format("d-m-Y", $data['Vanban']['ngay_duyet']) 
							
					?></b></div>
                     <hr>
                   <div>Người nhận : <b><?php echo $data['Nhanviennhan']['full_name'] ?></b></div>
                    <div>Người cập nhật : <b><?php echo $data['Nhanviencapnhat']['full_name'] ?></b></div> 
                    
                    <div>Ngày cập nhật : <b><?php echo $this->Time->format("d-m-Y", $data['Ketquavanban']['ngay_capnhat']) ?> </b></div>
                    <div>Nội dung cập nhật : <b><?php echo $data['Ketquavanban']['noidung_capnhat'] ?></b></div>

                    <div>Mức độ cập nhật : <b>
						 <?php
                            if(!empty($data['Ketquavanban']['ngay_capnhat']) && $this->Time->format('Y-m-d', $data['Ketquavanban']['ngay_capnhat']) <= $data['Vanban']['vbgap_ngayhoanthanh'])
                                echo '<div class="prog-bar-blue" style="width: ' . $data['Ketquavanban']['capnhat_mucdo'] . '%; text-align: center;"><div class="prog-bar-text">' . $data['Ketquavanban']['capnhat_mucdo']*10 . '%</div></div>';
                            else
                                echo '<div class="prog-bar-red" style="width: ' . $data['Ketquavanban']['capnhat_mucdo'] . '%; text-align: center;"><div class="prog-bar-text">' . $data['Ketquavanban']['capnhat_mucdo']*10 . '%</div></div>';
                        ?>
                    </b>
                    </div>
                    </div>
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
