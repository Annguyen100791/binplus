<!--  start table-content  -->
    <!--  start message -->
    <?php
		
		echo $this->Session->flash();
		$f_edit = $this->Layout->check_permission('CongViec.sua');
	?>
    <!--  end message -->
<?php
	if(!empty($data)):
	
	$this->Paginator->options( 
            array('url'		=> 	$this->passedArgs,  
				)); 
?>
<div style="padding-top:20px" class="data table-content">
	<form id="form-congviec-search" method="post" action="/congviec/search">
    <table class="treeTable">
        <thead>
        <tr>
        	<th width="1px">STT</th>
            <th width="350px"><?php echo $this->Paginator->sort('ten_congviec', 'Tên công việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('NguoiGiaoviec.full_name', 'Người giao việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('NguoiNhanviec.full_name', 'Người nhận việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('ngay_batdau', 'Ngày bắt đầu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('ngay_ketthuc', 'Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="20px"><?php echo $this->Paginator->sort('mucdo_hoanthanh', 'Hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'));?></th>
            <th width="60px">Thao tác</th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Congviec']['limit']*($this->Paginator->params['paging']['Congviec']['page']-1) + 1;
		foreach($data as $item):
		if($item['Congviec']['nguoi_giaoviec_id'] == $this->Session->read('Auth.User.nhanvien_id'))
		{
			if($item['Congviec']['mucdo_hoanthanh'] < 10)
			{
				if($item['Congviec']['ngay_ketthuc'] < date('Y-m-d'))
					$class = 'unfinished';
				else
					$class = 'progressing';
			}else
				$class = ' finished';
		}
		?>
        <tr class="<?php echo $class ?>">
            <td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php 
                if($item['Congviec']['nguoi_giaoviec_id'] == $this->Session->read('Auth.User.nhanvien_id'))
                    echo $this->Html->link($item['Congviec']['ten_congviec'], '/congviec/view/' . $item['Congviec']['id'], array('tip-position' => 'bottom left', 'title' => 'Bạn đã giao việc cho ' . $item['NguoiNhanviec']['full_name'] . ' vào lúc ' . $this->Time->format('d-m-Y h:i:s', $item['Congviec']['ngay_giao']) . '<p><b>Tên công việc:</b> ' . $item['Congviec']['ten_congviec'] . '<BR><b>Nội dung:</b> ' . $item['Congviec']['noi_dung'] . '</p><i>(Click để xem chi tiết )</i>'));
                else
                    echo $item['Congviec']['ten_congviec'];
            ?>
            </td>
            <td><?php echo $item['NguoiGiaoviec']['full_name']?></td>
            <td><?php echo $item['NguoiNhanviec']['full_name']?></td>
            <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_batdau']) ?></td>
            <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_ketthuc']) ?></td>
            <td>
            <div class="prog-border">
            <?php
                if(!empty($item['Congviec']['ngay_capnhat']) && $this->Time->format('Y-m-d', $item['Congviec']['ngay_capnhat']) <= $item['Congviec']['ngay_ketthuc'])
                    echo '<div class="prog-bar-blue" style="width: ' . $item['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $item['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                else
                    echo '<div class="prog-bar-red" style="width: ' . $item['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $item['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
            ?>
            </div>
            </td>
            <td>
            <?php
				if($f_edit)
				{
					echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/congviec/edit/' . $item['Congviec']['id'], array('title' => 'Hiệu chỉnh công việc đã giao', 'escape' => false));
					echo '&nbsp;';
					 echo $this->Html->link($this->Html->image('icons/note_delete.png', array('class' => 'icon-button')), '/congviec/delete/opt:all', array('title' => 'Xóa công việc đã giao này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Công việc này không ?', 'data-id' => $item['Congviec']['id'], 'data-target' => 'cvdagiao-list-content', 'escape' => false));
				}
				if(!empty($item['Congviec']['vanban_id']) && $this->Session->read('Auth.User.username') == 'hoanglm')
				{
					echo '&nbsp;';
					echo $this->Html->link($this->Html->image('icons/disk.png', array('class' => 'icon-button')), '/vanban/download_files/'. $item['Congviec']['vanban_id'], array('title' => 'Download file văn bản đính kèm', 'escape' => false));
				}
					
			?>
            </td>
        </tr>	
     <?php
		endforeach;
	?>
	</tbody>
    <tfoot>
    	<tr>
        	<td colspan="8">
                <?php if($this->Paginator->params['paging']['Congviec']['pageCount'] > 1):?>
            	<div class="pagination">
                	[Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'cvdagiao-list-content'))?>
                </div>
                <?php endif;?>
            </td>
        </tr>
    </tfoot>
    </table>
    </form>
    <div style="float:left; padding: 20px 0">
        <a class="button" href="#" id="btn-expexcel-search" target="_blank"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a>
    </div>
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