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
<div class="table-content data">
	<table width="100%"  style="table-layout: fixed;">
        <thead>
        <tr>
        	<th width="10px">STT</th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Số đến', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="200px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_nhap', 'Ngày nhập', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="100%"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'));?></th>
            <th width="120px"> </th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		$f_quanly = $this->Layout->check_permission('VanBan.quanly');
		$f_sua = $this->Layout->check_permission('VanBan.sua');
		$f_xoa = $this->Layout->check_permission('VanBan.xoa');
		foreach($ds as $item):
	?>
    	<tr>
        	<td style="text-align:center!important"><?php echo $stt++; ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
			?>
            </td>
            <td>
            	<?php echo $item['Vanban']['so_hieu_den'] ?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            <td align="center">
            <?php
					echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhap'])
				?>
            </td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td>
            <?php
				if($f_quanly || $f_sua)
					echo $this->Html->link($this->Html->image('icons/page_edit.png', array('class' => 'icon-button')), '/vanban/edit/' . $item['Vanban']['id'], array('title' => 'Chỉnh sửa văn bản', 'escape' => false));
				echo "&nbsp;";
				if($f_quanly || $f_xoa)
					echo $this->Html->link($this->Html->image('icons/page_delete.png', array('class' => 'icon-button')), '/vanban/delete', array('escape' => false, 'data-id' => $item['Vanban']['id'], 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa văn bản này không ?', 'data-target' => 'vanban-sua-content', 'title' => 'Xóa văn bản này'));
					
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    <tfoot>
    	<tr>
        	<td>
            	<div style="float:left; padding: 20px 0">
                	<a class="button" href="#" id="btn-expexcel-search-sua" target="_blank"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel.</a>
                </div>
            </td>
            <td colspan="8">
            	
                <?php if($this->Paginator->params['paging']['Vanban']['pageCount'] > 1):?>
            	<div class="pagination">
                
                	[Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                    <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'))?>
                    <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'))?>
					<?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content')); ?>
                    <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'))?>
                    <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-sua-content'))?>
                
                </div>
                <?php endif;?>
            </td>
        </tr>
    </tfoot>
    </table>
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