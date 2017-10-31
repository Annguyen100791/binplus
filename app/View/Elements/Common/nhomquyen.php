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
            <th width="30%"><?php echo $this->Paginator->sort('ten_nhomquyen', 'Tên Nhóm quyền hạn', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'nhomquyen-list'));?></th>
            <th width="60%">Danh sách Quyền</th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
	$stt = $this->Paginator->params['paging']['Nhomquyen']['limit']*($this->Paginator->params['paging']['Nhomquyen']['page']-1) + 1;
	
	foreach($ds as $item):
?>
	<tr>
    	<td align="center"><input type="checkbox" value="<?php echo $item['Nhomquyen']['id'] ?>" /></td>
    	<td align="center"><?php echo $stt++ ?></td>
        <td>
        <?php
			echo $this->Html->link($item['Nhomquyen']['ten_nhomquyen'], '/nhomquyen/edit/' . $item['Nhomquyen']['id'], array('title' => 'Hiệu chỉnh Nhóm quyền hạn này'));
		?>
        </td>
        <td>
        <?php
			if(!empty($item['Quyen']))
				foreach($item['Quyen'] as $child)
					echo $this->Html->link($child['ten_quyen'], '/quyen/view/' . $child['id'], array('title' => 'Xem chi tiết Quyền hạn này', 'data-mode' => "ajax", 'data-action' => 'dialog')) . ", ";
		?>
        </td>
        <td align="center">
        <?php
			echo $this->Html->link($this->Html->image('icons/pencil.png'), '/nhomquyen/edit/' . $item['Nhomquyen']['id'], array('title' => 'Hiệu chỉnh Nhóm quyền hạn này', 'escape' => false));
			echo '&nbsp;';
			echo $this->Html->link($this->Html->image('icons/cross.png'), '/nhomquyen/delete', array('title' => 'Xóa Nhóm quyền này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Nhóm quyền này không ?', 'data-id' => $item['Nhomquyen']['id'], 'data-target' => 'nhomquyen-list', 'escape' => false));
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
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhomquyen-list'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhomquyen-list'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhomquyen-list')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhomquyen-list'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'nhomquyen-list'))?>
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