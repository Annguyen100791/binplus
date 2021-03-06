<?php
	echo $this->Session->flash();
	if(!empty($data)):
	
	$this->Paginator->options( 
            array('url'		=> 	$this->passedArgs,  
				)); 
?>
<div style="padding-top:20px" class="data table-content">
	<table class="treeTable">
        <thead>
            <tr>
            	<th width="1px"><input class="check-all" type="checkbox" /></th>
                <th width="1px">STT</th>
                <th width="350px"><?php echo $this->Paginator->sort('ten_congviec', 'Tên công việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="120px"><?php echo $this->Paginator->sort('NguoiGiaoviec.full_name', 'Người giao việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="120px"><?php echo $this->Paginator->sort('NguoiNhanviec.full_name', 'Người nhận việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="80px"><?php echo $this->Paginator->sort('ngay_batdau', 'Ngày bắt đầu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="80px"><?php echo $this->Paginator->sort('ngay_ketthuc', 'Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="20px"><?php echo $this->Paginator->sort('mucdo_hoanthanh', 'Hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'));?></th>
                <th width="60px">Thao tác</th>
            </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Congviec']['limit']*($this->Paginator->params['paging']['Congviec']['page']-1) + 1;
		foreach($data as $item):
			
			$class = '';
				
			if($item['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id'))
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
            	<td align="center"><input type="checkbox" value="<?php echo $item['Congviec']['id'] ?>" /></td>
                <td align="center"><?php echo $stt++ ?></td>
                <td>
                    <?php 
						if($item['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id'))
							echo $this->Html->link($item['Congviec']['ten_congviec'], '/congviec/view/' . $item['Congviec']['id'].'/congviec-all', array('title' => '<b>' . $item['NguoiGiaoviec']['full_name'] . '</b> đã giao cho bạn <p><b>Tên công việc:</b> ' . $item['Congviec']['ten_congviec'] . '<BR><b>Nội dung:</b> ' . $item['Congviec']['noi_dung'] . '<BR><b>Ngày giao việc:</b> ' . $this->Time->format('d-m-Y H:i:s', $item['Congviec']['ngay_giao']) . '</p>Click để xem chi tiết công việc'));
						else
							echo $this->Html->link($item['Congviec']['ten_congviec'], '/congviec/view/' . $item['Congviec']['id'], array('title' => 'Click để xem chi tiết công việc'));
					?>
                </td>
                <td><?php echo $item['NguoiGiaoviec']['full_name']?></td>
                <td><?php echo $item['NguoiNhanviec']['full_name']?></td>
                <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_batdau']) ?></td>
                <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_ketthuc']) ?></td>
                <td>
                    <div class="prog-border">
					<?php
                        if(!empty($item['Congviec']['ngay_capnhat']) && $this->Time->format('Y-m-d', $item['Congviec']['ngay_capnhat']) <= $item['Congviec']['ngay_ketthuc'] && $item['Congviec']['mucdo_hoanthanh'])
                            echo '<div class="prog-bar-blue" style="width: ' . $item['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $item['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                        else
                            echo '<div class="prog-bar-red" style="width: ' . $item['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $item['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                    ?>
                    </div>
                </td>
                <td>
                <?php
					if($item['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id'))
					{
						 echo $this->Html->link($this->Html->image('icons/time.png', array('class' => 'icon-button')), '/congviec/update_progress/' . $item['Congviec']['id'].'/congviec-all', array('title' => 'Cập nhật mức độ hoàn thành công việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
						 if($item['CongviecChinh']['mucdo_hoanthanh'] < 10 && $item['Congviec']['giaoviec_tiep'] == 1)
						{
							echo '&nbsp;';
							echo $this->Html->link($this->Html->image('icons/note_go.png', array('class' => 'icon-button')), '/congviec/add_process/' . $item['Congviec']['id'], array('title' => 'Giao việc cho nhân viên khác thực hiện', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
						}
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
        	<?php if($this->Session->read('Auth.User.username') == 'hoanglm') {?>
            <td >
            <div style="float:left; padding: 20px 0">
        		<a href="/congviec/mark_read/type:all" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Chức năng này để cập nhật tiến độ 100% những công việc không liên quan, bạn có đồng ý cập nhật không ?" data-target="congviec-all">Đánh dấu </a>
    		</div>
            </td>
            <td >
            </td>
            <td >
            </td>
            <?php } ?>
            <td colspan="8">
                <?php if($this->Paginator->params['paging']['Congviec']['pageCount'] > 1):?>
            	<div class="pagination">
                	[Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-all'))?>
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