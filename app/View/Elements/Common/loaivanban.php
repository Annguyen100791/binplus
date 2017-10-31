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
            <th width="30%"><?php echo $this->Paginator->sort('ten_loaivanban', 'Tên loại văn bản', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'loaivanban-list'));?></th>
            <th width="40%"><?php echo $this->Paginator->sort('mo_ta', 'Mô tả', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('chieu_di', 'Chiều', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'));?></th>
            <th><?php echo $this->Paginator->sort('enabled', 'Status', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
    $stt = $this->Paginator->params['paging']['Loaivanban']['limit']*($this->Paginator->params['paging']['Loaivanban']['page']-1) + 1;
    
    foreach($ds as $item):
?>
    <tr>
        <td align="center"><input type="checkbox" value="<?php echo $item['Loaivanban']['id'] ?>" /></td>
        <td align="center"><?php echo $stt++ ?></td>
        <td>
        <?php
            echo $this->Html->link($item['Loaivanban']['ten_loaivanban'], '/loaivanban/edit/' . $item['Loaivanban']['id'], array('title' => 'Chỉnh sửa Loại văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog'));
        ?>
        </td>
        <td>
        <?php
            echo $this->Html->link($item['Loaivanban']['mo_ta'], '/loaivanban/edit/' . $item['Loaivanban']['id'], array('title' => 'Chỉnh sửa loại văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog'));
        ?>
        </td>
        <td>
            <?php echo $chieu_di[$item['Loaivanban']['chieu_di']] ?>
        </td>
        <td>
        <?php
            if(empty($item['Loaivanban']['enabled']))
				echo $this->Html->link($this->Html->image('icons/cross_circle.png'), '/loaivanban/active/' . $item['Loaivanban']['id'], array('title' => 'Click để cho phép hiển thị', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
            else
				echo $this->Html->link($this->Html->image('icons/tick_circle.png'), '/loaivanban/active/' . $item['Loaivanban']['id'], array('title' => 'Click để không hiển thị trên trang chủ', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
        ?>
        </td>
        <td align="center">
        <?php
            echo $this->Html->link($this->Html->image('icons/pencil.png'), '/loaivanban/edit/' . $item['Loaivanban']['id'], array('title' => 'Chỉnh sửa loại văn bản', 'data-mode' => "ajax", 'data-action' => 'dialog', 'escape' => false));
            echo '&nbsp;';
            echo $this->Html->link($this->Html->image('icons/cross.png'), '/loaivanban/delete', array('title' => 'Xóa Loại văn bản này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Loại văn bản này không ?', 'data-id' => $item['Loaivanban']['id'], 'data-target' => 'loaivanban-list', 'escape' => false));
        ?>
        </td>
    </tr>
        <?php
    endforeach;
    
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="pagination">
                    [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'loaivanban-list'))?>
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