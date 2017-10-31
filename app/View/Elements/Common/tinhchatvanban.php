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
            <th><input class="check-all" type="checkbox" /></th>
            <th width="32px">STT</th>
            <th width="50%"><?php echo $this->Paginator->sort('ten_tinhchat', 'Tên Tính chất văn bản', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'));?></th>
            <th width="40%"><?php echo $this->Paginator->sort('mo_ta', 'Mô tả', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'));?></th>
            <th><?php echo $this->Paginator->sort('enabled', 'Status', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
    $stt = $this->Paginator->params['paging']['Tinhchatvanban']['limit']*($this->Paginator->params['paging']['Tinhchatvanban']['page']-1) + 1;
    
    foreach($ds as $item):
?>
    <tr>
        <td align="center"><input type="checkbox" value="<?php echo $item['Tinhchatvanban']['id'] ?>" /></td>
        <td align="center"><?php echo $stt++ ?></td>
        <td>
        <?php
            echo $this->Html->link($item['Tinhchatvanban']['ten_tinhchat'], '/tinhchatvanban/edit/' . $item['Tinhchatvanban']['id'], array('title' => 'Chỉnh sửa Tính chất văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog'));
        ?>
        </td>
        <td>
        <?php
            echo $this->Html->link($item['Tinhchatvanban']['mo_ta'], '/tinhchatvanban/edit/' . $item['Tinhchatvanban']['id'], array('title' => 'Chỉnh sửa Tính chất văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog'));
        ?>
        </td>
        <td>
        <?php
            if(empty($item['Tinhchatvanban']['enabled']))
				echo $this->Html->link($this->Html->image('icons/cross_circle.png'), '/tinhchatvanban/active/' . $item['Tinhchatvanban']['id'], array('title' => 'Click để cho phép hiển thị', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
            else
				echo $this->Html->link($this->Html->image('icons/tick_circle.png'), '/tinhchatvanban/active/' . $item['Tinhchatvanban']['id'], array('title' => 'Click để không hiển thị trên trang chủ', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
        ?>
        </td>
        <td align="center">
        <?php
            echo $this->Html->link($this->Html->image('icons/pencil.png'), '/tinhchatvanban/edit/' . $item['Tinhchatvanban']['id'], array('title' => 'Chỉnh sửa Tính chất văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog', 'escape' => false));
            echo '&nbsp;';
            echo $this->Html->link($this->Html->image('icons/cross.png'), '/tinhchatvanban/delete', array('title' => 'Xóa Tính chất văn bản này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Tính chất văn bản này không ?', 'data-id' => $item['Tinhchatvanban']['id'], 'data-target' => 'tinhchatvanban-list', 'escape' => false));
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
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tinhchatvanban-list'))?>
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