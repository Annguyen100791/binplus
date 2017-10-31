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
            <th width="60%"><?php echo $this->Paginator->sort('tieu_de', 'Nội dung', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'chinhsach-list'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('ngay_gui', 'Ngày nhập', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('full_name', 'Người nhập', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php
		$stt = $this->Paginator->params['paging']['Chinhsach']['limit']*($this->Paginator->params['paging']['Chinhsach']['page']-1) + 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Chinhsach']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Chinhsach']['tieu_de'], '/chinhsach/view/' . $item['Chinhsach']['id'], array('title' => 'Xem chi tiết chính sách mới', 'escape' => false, 'data-action' => 'dialog'));
			?>
            </td>
            <td align="center"><?php echo $this->Time->format("d-m-Y", $item['Chinhsach']['ngay_gui']) ?></td>
            <td><?php echo $item['Nguoigui']['full_name'] ?></td>
            <td>
            <?php
				if($this->Layout->check('ChinhSach.sua', $item['Chinhsach']['nguoigui_id']))
				{
					echo $this->Html->link($this->Html->image('icons/pencil.png'), '/chinhsach/edit/' . $item['Chinhsach']['id'], array('title' => 'Hiệu chỉnh chính sách mới', 'escape' => false,'data-action' => 'dialog'));
					
					echo '&nbsp;';
				}
				if($this->Layout->check('ChinhSach.xoa', $item['Chinhsach']['nguoigui_id']))
					echo $this->Html->link($this->Html->image('icons/cross.png'), '/chinhsach/delete', array('title' => 'Click để xóa chính sách mới này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa chính sách mới này không ?', 'data-id' => $item['Chinhsach']['id'], 'data-target' => 'chinhsach-list', 'escape' => false, 'class' => 'delete-item'));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
    </tbody>
    </table>
    <?php if($this->Paginator->params['paging']['Chinhsach']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
            <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'))?>
            <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'))?>
            <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list')); ?>
            <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'))?>
            <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'chinhsach-list'))?>
    </div>
    <?php endif;?>
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