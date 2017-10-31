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
            <th width="60%"><?php echo $this->Paginator->sort('tieu_de', 'Nội dung', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'tintuc-list'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('ngay_gui', 'Ngày nhập', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('full_name', 'Người nhập', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'));?></th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php
		$stt = $this->Paginator->params['paging']['Tintuc']['limit']*($this->Paginator->params['paging']['Tintuc']['page']-1) + 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Tintuc']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Tintuc']['tieu_de'], '/tintuc/view/' . $item['Tintuc']['id'], array('title' => 'Xem chi tiết thông tin mới', 'escape' => false, 'data-action' => 'dialog'));
			?>
            </td>
            <td align="center"><?php echo $this->Time->format("d-m-Y", $item['Tintuc']['ngay_gui']) ?></td>
            <td><?php echo $item['Nguoigui']['full_name'] ?></td>
            <td>
            <?php
				if($this->Layout->check('ThongBao.sua', $item['Tintuc']['nguoigui_id']))
				{
					echo $this->Html->link($this->Html->image('icons/pencil.png'), '/tintuc/edit/' . $item['Tintuc']['id'], array('title' => 'Hiệu chỉnh thông tin mới', 'escape' => false,'data-action' => 'dialog'));
					
					echo '&nbsp;';
				}
				if($this->Layout->check('ThongBao.xoa', $item['Tintuc']['nguoigui_id']))
					echo $this->Html->link($this->Html->image('icons/cross.png'), '/tintuc/delete', array('title' => 'Click để xóa thông tin mới này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa thông tin mới này không ?', 'data-id' => $item['Tintuc']['id'], 'data-target' => 'tintuc-list', 'escape' => false, 'class' => 'delete-item'));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
    </tbody>
    </table>
    <?php if($this->Paginator->params['paging']['Tintuc']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
            <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'))?>
            <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'))?>
            <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list')); ?>
            <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'))?>
            <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'tintuc-list'))?>
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