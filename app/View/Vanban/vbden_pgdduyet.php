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
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Số hiệu VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_nhan', 'Ngày VB đến', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="300px"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.nguoi_duyet', 'Người duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.tinh_trang', 'Tình trạng', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th ><?php echo $this->Paginator->sort('Vanban.chuyen_nguoiduyet', 'Trạng thái', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.uy_quyen', 'Phân loại', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'));?></th>
            
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		//$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Vanban']['id'] ?>" /></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Click để xem chi tiết văn bản'));
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
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
			?>
            </td>
            <td>
            <?php
				//if(empty($item['Nhanvanban']['ngay_xem']))
					//echo '<b>' . $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản')) . '</b>';
				//else
					echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td align="center">
            <?php
				echo $item['Vanban']['nguoi_duyet'];
			?>
            </td>
            <td>
            <?php
				if ($item['Vanban']['tinhtrang_duyet'] == 1)				
					$tinhtrang_duyet = 'đã duyệt';
				else
					$tinhtrang_duyet = 'chưa duyệt';
				echo $tinhtrang_duyet;
			?>
            </td>
            <td>
            <?php
				if ($item['Vanban']['chuyen_nguoiduyet'] == 1)				
					$trang_thai = 'GĐ chuyển PGĐ';
				else
					$trang_thai = '';
				echo $trang_thai;
			?>
            </td>
            <td>
            <?php
				if ($item['Vanban']['uy_quyen'] == 1)				
					$phan_loai = 'Ủy quyền';
				else
					$phan_loai = '';
				echo $phan_loai;
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <div style="float:left; padding: 20px 0">
        <!--<a href="/vanban/mark_read/type:den" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn đánh dấu văn bản đã chọn hay không ?" data-target="choduyet-list-content">Đánh dấu văn bản đã đọc</a> -->
       <!-- <a class="button" href="#" id="btn-expexcel-den" target="_blank" title="Xuất kết quả tìm kiếm ra file Excel"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a> -->
    </div>
    <?php if($this->Paginator->params['paging']['Vanban']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'pgdduyet-list-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'pgdduyet-list-content'))?>
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