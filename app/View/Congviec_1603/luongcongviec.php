<div style="padding-top:20px" class="data table-content">
    <table id="luongcongviec-data">
        <thead>
        <tr>
            <th width="400px">Tên công việc</th>
            <th width="120px">Người giao việc</th>
            <th width="120px">Người nhận việc</th>
            <th width="80px">Ngày bắt đầu</th>
            <th width="80px">Ngày hoàn thành</th>
            <th width="20px">Hoàn thành</th>
            <th width="80px">Thao tác</th>
        </tr>
        </thead>
    <tbody>
    <?php
            $node = 'node';
            for($i = 0; $i < count($ds_congviec); $i++):
                $node = 'node-' . $ds_congviec[$i]['Congviec']['id'];
                
                $class = '';
                
                if($ds_congviec[$i]['Congviec']['mucdo_hoanthanh'] < 10)
                {
                    if($ds_congviec[$i]['Congviec']['ngay_ketthuc'] < date('Y-m-d'))
                        $class = 'unfinished';
                    else
                        $class = 'progressing';
                }else
                    $class = ' finished';
                    
                if(!empty($ds_congviec[$i]['Congviec']['parent_id']))
                    $class .= ' child-of-node-' . $ds_congviec[$i]['Congviec']['parent_id'];
                if($i == 0):
            ?>
            <tr id="node-<?php echo $ds_congviec[$i]['Congviec']['id'] ?>" class="<?php echo $class ?>">
                <td><span class="folder"><?php echo $this->Html->link($ds_congviec[$i]['Congviec']['ten_congviec'], 'javascript:void(0)') ?></span></td>
                <td><?php echo $ds_congviec[$i]['NguoiGiaoviec']['full_name']?></td>
                <td><?php echo $ds_congviec[$i]['NguoiNhanviec']['full_name']?></td>
                <td><?php echo $this->Time->format('d-m-Y', $ds_congviec[$i]['Congviec']['ngay_batdau']) ?></td>
                <td><?php echo $this->Time->format('d-m-Y', $ds_congviec[$i]['Congviec']['ngay_ketthuc']) ?></td>
                <td>
                    <div class="prog-border">
                    <?php
                        if(!empty($ds_congviec[$i]['Congviec']['ngay_capnhat']) && $this->Time->format('Y-m-d', $ds_congviec[$i]['Congviec']['ngay_capnhat']) <= $ds_congviec[$i]['Congviec']['ngay_ketthuc'])
                            echo '<div class="prog-bar-blue" style="width: ' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                        else
                            echo '<div class="prog-bar-red" style="width: ' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                    ?>
                    </div>
                </td>
                <td>
                    <?php
                        if(//$this->data['Congviec']['mucdo_hoanthanh'] < 10 && // công việc chính chưa hoàn thành
                            $ds_congviec[$i]['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id')
                        )
						{
							echo $this->Html->link($this->Html->image('icons/time.png', array('class' => 'icon-button')), '/congviec/update_progress/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Cập nhật mức độ hoàn thành công việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action'  => 'dialog'));
							if($ds_congviec[0]['Congviec']['mucdo_hoanthanh'] < 10 && $ds_congviec[$i]['Congviec']['giaoviec_tiep'] == 1)
							{
								echo '&nbsp;';
								echo $this->Html->link($this->Html->image('icons/note_go.png', array('class' => 'icon-button')), '/congviec/add_process/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Giao việc cho nhân viên khác thực hiện', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
							}
						}
                        
                        if($ds_congviec[$i]['Congviec']['nguoi_giaoviec_id'] == $this->Session->read('Auth.User.nhanvien_id'))
                        {
                            echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/congviec/edit/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Hiệu chỉnh công việc', 'escape' => false));
                            echo '&nbsp;';
                            echo $this->Html->link($this->Html->image('icons/note_delete.png', array('class' => 'icon-button')), '/congviec/delete', array('title' => 'Xóa công việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'delete-item', 'data-id' => $ds_congviec[$i]['Congviec']['id'], 'data-confirm' => 'Bạn có muốn xóa giao việc này không ?'));
                        }
                        
                    ?>
                </td>
            </tr>
            <?php
                else:
                
            ?>
                <tr id="<?php echo $node ?>" class="<?php echo $class ?>">
                    <td>
                        <span class="file">
                            <?php 
                                if($ds_congviec[$i]['Congviec']['nguoi_giaoviec_id'] == $this->Session->read('Auth.User.nhanvien_id'))
                                    echo $this->Html->link($ds_congviec[$i]['Congviec']['ten_congviec'], '/congviec/view/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Bạn đã giao việc cho ' . $ds_congviec[$i]['NguoiNhanviec']['full_name'] . '<p><b>Tên công việc:</b> ' . $ds_congviec[$i]['Congviec']['ten_congviec'] . '<BR><b>Nội dung:</b> ' . $ds_congviec[$i]['Congviec']['noi_dung'] . '</p>'));
                                else
                                     echo $this->Html->link($ds_congviec[$i]['Congviec']['ten_congviec'], 'javascript:void(0)');
                            ?>
                        </span>
                    </td>
                    <td><?php echo $ds_congviec[$i]['NguoiGiaoviec']['full_name']?></td>
                    <td><?php echo $ds_congviec[$i]['NguoiNhanviec']['full_name']?></td>
                    <td><?php echo $this->Time->format('d-m-Y', $ds_congviec[$i]['Congviec']['ngay_batdau']) ?></td>
                    <td><?php echo $this->Time->format('d-m-Y', $ds_congviec[$i]['Congviec']['ngay_ketthuc']) ?></td>
                    <td>
                    <div class="prog-border">
                    <?php
                        if(!empty($ds_congviec[$i]['Congviec']['ngay_capnhat']) && $this->Time->format('Y-m-d', $ds_congviec[$i]['Congviec']['ngay_capnhat']) <= $ds_congviec[$i]['Congviec']['ngay_ketthuc'])
                            echo '<div class="prog-bar-blue" style="width: ' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                        else
                            echo '<div class="prog-bar-red" style="width: ' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $ds_congviec[$i]['Congviec']['mucdo_hoanthanh']*10 . '%</div></div>';
                    ?>
                    </div>
                    </td>
                    <td>
                    <?php
                        if(//$this->data['Congviec']['mucdo_hoanthanh'] < 10 && // công việc chính chưa hoàn thành
                            $ds_congviec[$i]['Congviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id')
                        )
						{
							echo $this->Html->link($this->Html->image('icons/time.png', array('class' => 'icon-button')), '/congviec/update_progress/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Cập nhật mức độ hoàn thành công việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
							if($this->data['Congviec']['mucdo_hoanthanh'] < 10 && $ds_congviec[$i]['Congviec']['giaoviec_tiep'] == 1)
							{
								echo '&nbsp;';
								echo $this->Html->link($this->Html->image('icons/note_go.png', array('class' => 'icon-button')), '/congviec/add_process/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Giao việc cho nhân viên khác thực hiện', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
							}
						}
                        
                        if($ds_congviec[$i]['Congviec']['nguoi_giaoviec_id'] == $this->Session->read('Auth.User.nhanvien_id'))
                        {
                            echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/congviec/edit/' . $ds_congviec[$i]['Congviec']['id'], array('title' => 'Hiệu chỉnh công việc', 'escape' => false));
                            echo '&nbsp;';
							echo $this->Html->link($this->Html->image('icons/note_delete.png', array('class' => 'icon-button')), '/congviec/delete', array('title' => 'Xóa công việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'delete-item', 'data-id' => $ds_congviec[$i]['Congviec']['id'], 'data-confirm' => 'Bạn có muốn xóa giao việc này không ?', 'data-congviecchinh' => 'true'));
                        }
                        
                    ?>
                    </td>
                </tr>	
            <?php
                endif;
            endfor;

    ?>
    </tbody>

    </table>
    
    <div style=" padding:10px 0"><span style="font-weight:bold; text-decoration:underline;">Chú thích</span></div>
    <div>
        <div style="width:30px; height:15px; background-color:#fffbcc; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Trễ tiến độ</div>
        <div style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Đã hoàn thành</div>
        <div style="width:30px; height:15px; background-color:#eaf5ff; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Đang thực hiện</div>
    </div>
</div>
<script>
	$(document).ready(function(){
		BIN.doListing();
		$("#luongcongviec-data").treeTable({
			margin:	0,
			padding: 20,
			initialState: "expanded"
		});
	});
</script>
