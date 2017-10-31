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
        	<th width="60px"><input class="check-all" type="checkbox" /></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Số hiệu VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_nhan', 'Ngày VB đến', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.tinh_trang', 'Tình trạng', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.ngay_duyet', 'Người duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'datrinh-list-content'));?></th>
            <th width="1px"></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		//$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Vanban']['id'] ?>" />
            <?php 
			 if($this->Session->read('Auth.User.nhanvien_id') == 683)
					echo $this->Html->link($this->Html->image('icons/edit_ex.png', array('class' => 'icon-button')), '/vanban/edit_vb_except/' . $item['Vanban']['id'], array('title' => 'Chỉnh sửa văn bản đã trình trường hợp ngoại lệ', 'escape' => false));
			 ?>
             </td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Click để xem chi tiết văn bản'));
				//if(empty($item['Nhanvanban']['ngay_xem']))
				//{
					//echo $this->Html->image('icons/new.png', array('style' => 'vertical-align:text-top; margin-left: 5px'));
				//}
					
			?>
            </td>
            
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhan'])
			?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhan'])
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
            <td>
            <?php
				if ($item['Vanban']['tinhtrang_duyet'] == 1)				
					$tinhtrang_duyet = 'đã duyệt';
				else
					$tinhtrang_duyet = 'chưa duyệt';
				echo $tinhtrang_duyet;
			?>
            </td>
            <td align="center">
            <?php
				echo $item['Vanban']['nguoi_duyet']
			?>
            </td>
            <td>
            <?php
				//if($f_quanly || $f_sua)
					echo $this->Html->link($this->Html->image('icons/page_edit.png', array('class' => 'icon-button')), '/vanban/edit_vanbantrinh/' . $item['Vanban']['id'], array('title' => 'Chỉnh sửa văn bản đã trình', 'escape' => false));
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
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'datrinh-list-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'datrinh-list-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'datrinh-list-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'datrinh-list-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'datrinh-list-content'))?>
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