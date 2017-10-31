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
        	<th><input class="check-all" type="checkbox" title="Chọn/ bỏ chọn tất cả" /></th>
        	<th width="32px">STT</th>
            <th width="25%"><?php echo $this->Paginator->sort('ten_quyen', 'Tên Quyền hạn', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'quyen-list'));?></th>
            <th width="40%"><?php echo $this->Paginator->sort('mo_ta', 'Mô tả', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'quyen-list'));?></th>
            <th width="20%"><?php echo $this->Paginator->sort('tu_khoa', 'Từ khóa', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'quyen-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
	$stt = $this->Paginator->params['paging']['Quyen']['limit']*($this->Paginator->params['paging']['Quyen']['page']-1) + 1;
	
	foreach($ds as $item):
?>
	<tr>
    	<td align="center"><input type="checkbox" value="<?php echo $item['Quyen']['id'] ?>" /></td>
    	<td align="center"><?php echo $stt++ ?></td>
        <td>
        <?php
			echo $this->Html->link($item['Quyen']['ten_quyen'], '/quyen/edit/' . $item['Quyen']['id'], array('title' =>     'Chỉnh sửa quyền hạn', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '500'));
		?>
        </td>
        <td>
        <?php
			echo $this->Html->link($item['Quyen']['mo_ta'], '/quyen/edit/' . $item['Quyen']['id'], array('title' =>     'Chỉnh sửa quyền hạn', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '500'));
		?>
        </td>
        <td>
        <?php
			echo $this->Html->link($item['Quyen']['tu_khoa'], '/quyen/edit/' . $item['Quyen']['id'], array('title' =>     'Chỉnh sửa quyền hạn', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '500'));
		?>
        </td>
        <td align="center">
        <?php
			echo $this->Html->link($this->Html->image('icons/pencil.png'), '/quyen/edit/' . $item['Quyen']['id'], array('title' =>     'Chỉnh sửa quyền hạn', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '500', 'escape' => false));
			echo '&nbsp;';
			echo $this->Html->link($this->Html->image('icons/cross.png'), '#', array('title' => 'Click để xóa Quyền hạn này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Quyền hạn này không ?', 'data-id' => $item['Quyen']['id'], 'data-target' => 'quyen-list', 'escape' => false, 'class' => 'delete-item'));
		?>
        </td>
    </tr>
        <?php
	endforeach;
	
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                <div class="pagination">
                    [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'quyen-list'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'quyen-list'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'quyen-list')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'quyen-list'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'quyen-list'))?>
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
	});
</script>