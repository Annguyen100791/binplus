<!--  start table-content  -->
    <!--  start message -->
    <?php
		echo $this->Session->flash();
	?>
    <!--  end message -->
<?php
	if(!empty($ds)):
	
	$this->Paginator->options( 
            array('url'		=> 	$this->passedArgs,  
				)); 
?>
<div style="padding-top:20px" class="data table-content">
	<table>
        <thead>
        <tr>
        	<th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Số hiệu VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.ngay_nhap', 'Ngày nhập', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_nhan', 'Ngày VB đến', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th ><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="95px"><?php echo $this->Paginator->sort('Vanban.vbgap_ngayhoanthanh', ' Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
            <th width="130px"><?php echo $this->Paginator->sort('Ketquavanban.nguoi_capnhat_id', ' Người báo cáo', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'));?></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Vanban']['id'] ?>" /></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id']. '#xem_chitiet', array('title' => 'Click để xem chi tiết văn bản'));
					
			?>
            </td>
            
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
			?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
             <td align="center">
            <?php
					echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhap'])
				?>
            </td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhan'])
			?>
            </td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/'. $item['Vanban']['id'].'#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['vbgap_ngayhoanthanh'])
			?>
            </td>
            <td align="center">
            <?php
				foreach($item['Ketquavanban'] as $kq)
				{
					//pr($kq);die();
					if($this->Session->read('Auth.User.nhanvien_id') == 683 || $this->Session->read('Auth.User.nhanvien_id') == 681 || $this->Session->read('Auth.User.chucdanh_id') == 30)
					{
					 ?>
                    <br>
                    <?php
						echo $this->Html->link($kq['Nhanviencapnhat']['full_name'], '/vanban/view_result/' . $kq['id'], array('tip-position' => 'bottom left', 'title' => '<b>Nội dung chỉ đạo:</b> ' . $item['Vanban']['noidung_duyet'] . '<p><b>Ngày hoàn thành:</b> ' . $item['Vanban']['vbgap_ngayhoanthanh'] . '<BR><b>Nội dung báo cáo:</b> ' . $kq['noidung_capnhat'] . '<BR><b>Ngày báo cáo:</b> ' . $kq['ngay_capnhat']  . '<BR><b>Người nhận báo cáo:</b> ' . $kq['Nhanviennhan']['full_name'] . '<p><i>(Click để xem chi tiết )</i>'));
					}
					else if($kq['nguoi_nhan_id'] == $this->Session->read('Auth.User.nhanvien_id')):
					?>
                    <br>
                    <?php
					echo $this->Html->link($kq['Nhanviencapnhat']['full_name'], '/vanban/view_result/' . $kq['id'], array('tip-position' => 'bottom left', 'title' => '<b>Nội dung chỉ đạo:</b>' . $item['Vanban']['noidung_duyet'] . '<p><b>Ngày hoàn thành:</b> ' . $item['Vanban']['vbgap_ngayhoanthanh'] . '<BR><b>Nội dung báo cáo:</b> ' . $kq['noidung_capnhat'] . '<p><b>Ngày báo cáo:</b> ' . $kq['ngay_capnhat'] .'</p><i>(Click để xem chi tiết )</i>'));
					endif;
				}
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <div style="float:left; padding: 20px 0">
       <!-- <a class="button" href="#" id="btn-expexcel-den" target="_blank" title="Xuất kết quả tìm kiếm ra file Excel"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a> -->
    </div>
    <?php if($this->Paginator->params['paging']['Vanban']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'capnhatketqua-list-content'))?>
    </div>
    <?php endif;?>
</div>    
<script>
	$(document).ready(function(){
		BIN.doListing();
	});
</script>
<?php
	endif;
?>
<!--  end content-table  -->