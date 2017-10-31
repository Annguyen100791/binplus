<?php
    echo $this->Session->flash();
    if(!empty($ds)):
    
    $this->Paginator->options( 
    array(//'update'	=>	'#listing-container',  
          'url'		=> 	$this->passedArgs
        )); 
?>
<div class="data">
    <table>
        <thead>
        <tr>
        	<th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="1px">STT</th>
            <th width="20%"><?php echo $this->Paginator->sort('ho', 'Họ tên Nhân viên', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhanvien-list'));?></th>
            <th width="20%"><?php echo $this->Paginator->sort('Chucdanh.ten_chucdanh', 'Chức danh', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhanvien-list'));?></th>
            <th width="30%"><?php echo $this->Paginator->sort('Phong.ten_phong', 'Phòng ban/ Đơn vị', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhanvien-list'));?></th>
            <th width="20%"><?php echo $this->Paginator->sort('email', 'Email', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhanvien-list'));?></th>
            <th><?php echo $this->Paginator->sort('tinh_trang', 'Status', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhanvien-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
    $stt = $this->Paginator->params['paging']['Nhanvien']['limit']*($this->Paginator->params['paging']['Nhanvien']['page']-1) + 1;
    
    foreach($ds as $item):
?>
	<tr>
        <td align="center"><input type="checkbox" value="<?php echo $item['Nhanvien']['id'] ?>" /></td>
        <td align="center"><?php echo $stt++ ?></td>
        <td>
        <?php
			 echo $this->Html->link(sprintf("%s %s %s", $item['Nhanvien']['ho'], $item['Nhanvien']['ten_lot'], $item['Nhanvien']['ten']), '/nhanvien/edit/' . $item['Nhanvien']['id'], array('title' => 'Hiệu chỉnh nhân viên', 'data-nguoiquanly' => $item['Nhanvien']['nguoi_quanly'], 'class' => 'nv-quanly'));
        ?>
        </td>
        <td><?php echo $item['Chucdanh']['ten_chucdanh'] ?></td>
        <td><?php echo $item['Phong']['ten_phong'] ?></td>
        <td><?php echo $item['Nhanvien']['email'] ?></td>
        <th>
        <?php
            if(empty($item['Nhanvien']['tinh_trang']))
				echo $this->Html->link($this->Html->image('icons/cross_circle.png'), '/nhanvien/active/' . $item['Nhanvien']['id'], array('title' => 'Click để cho phép hiển thị', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
            else
				echo $this->Html->link($this->Html->image('icons/tick_circle.png'), '/nhanvien/active/' . $item['Nhanvien']['id'], array('title' => 'Click để không hiển thị trên trang chủ', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
        ?>
        </th>
        <td>
        <?php
            echo $this->Html->link($this->Html->image('icons/pencil.png'), '/nhanvien/edit/' . $item['Nhanvien']['id'], array('title' => 'Hiệu chỉnh nhân viên', 'escape' => false));
            echo '&nbsp;';
            echo $this->Html->link($this->Html->image('icons/cross.png'), '#', array('title' => 'Click để xóa nhân viên này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa nhân viên này không ?', 'data-id' => $item['Nhanvien']['id'], 'data-target' => 'nhanvien-list', 'escape' => false, 'class' => 'delete-item'));
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
                <div class="pagination">
                    [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhanvien-list'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhanvien-list'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhanvien-list')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhanvien-list'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhanvien-list'))?>
                </div>
            </td>
        </tr>
    </tfoot>
    </table>
</div>    
<?php
    endif;
?>
</div>
<script>
	$(document).ready(function(){
		BIN.doListing();
		$('#nhanvien-list a.nv-quanly').each(function(index, element) {
            if($(element).attr('data-nguoiquanly') == '1')
				$(element).css('font-weight', 'bold');
        });
	});
</script>