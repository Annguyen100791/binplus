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
	<table class="treeTable">
        <thead>
            <tr>
                <th width="1px">STT</th>
                <th width="300px"><?php echo $this->Paginator->sort('ten_congviec', 'Tên công việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th width="130px"><?php echo $this->Paginator->sort('NguoiGiaoviec.full_name', 'Người giao việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th width="130px"><?php echo $this->Paginator->sort('NguoiNhanviec.full_name', 'Người nhận việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th width="80px"><?php echo $this->Paginator->sort('ngay_batdau', 'Ngày bắt đầu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th width="80px"><?php echo $this->Paginator->sort('ngay_ketthuc', 'Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th><?php echo $this->Paginator->sort('loaicongviec_id', 'Loại công việc', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-unfinished'));?></th>
                <th width="20px"><?php echo $this->Paginator->sort('mucdo_hoanthanh', 'Hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'));?></th>
                <th width="80px">Thao tác</th>
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
				if($item['Congviec']['tinhchat_id'] = 11) 
					$class = 'instant';
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
                    echo $this->Html->link($item['Congviec']['ten_congviec'], '/congviec/view/' . $item['Congviec']['id'], array('title' => 'Bạn đã giao việc cho ' . $item['NguoiNhanviec']['full_name'] . '<p><b>Tên công việc:</b> ' . $item['Congviec']['ten_congviec'] . '<BR><b>Nội dung:</b> ' . $item['Congviec']['noi_dung'] . '</p>'));
                else
                    echo $item['Congviec']['ten_congviec'];
            ?>
            </td>
            <td><?php echo $item['NguoiGiaoviec']['full_name']?></td>
            <td><?php echo $item['NguoiNhanviec']['full_name']?></td>
            <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_batdau']) ?></td>
            <td><?php echo $this->Time->format('d-m-Y', $item['Congviec']['ngay_ketthuc']) ?></td>
            <td><?php 
						if($item['Congviec']['loaicongviec_id'] == 1)
							echo "Theo dõi và báo cáo";
						if($item['Congviec']['loaicongviec_id'] == 1)
							echo "Chỉ xem";
						if(empty($item['Congviec']['loaicongviec_id']))
							echo "";
					?>
            </td>
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
                     echo $this->Html->link($this->Html->image('icons/note_delete.png', array('class' => 'icon-button')), '/congviec/delete/opt:instant', array('title' => 'Xóa công việc đã giao này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Công việc này không ?', 'data-id' => $item['Congviec']['id'], 'data-target' => 'congviec-instant', 'escape' => false));
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
                       <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'congviec-instant'))?>
                </div>
                <?php endif;?>
            </td>
        </tr>
    </tfoot>
    </table>
</div>    
<?php
	endif;
?>
<!--  end content-table  -->