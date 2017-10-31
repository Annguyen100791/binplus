<!--  start table-content  -->
    <!--  start message -->
    <?php
		echo $this->Session->flash();
	?>
    <!--  end message -->
<?php
	if(!empty($ds)):
	
	$this->Paginator->options( 
            array('url'		=> 	$this->passedArgs,  
				  )); 
?>
<div style="padding-top:20px" class="data table-content">
	<table id="unread-data">
        <thead>
        <tr>
        	<th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="1px">STT</th>
            <th width="400px"><?php echo $this->Paginator->sort('Tinnhan.tieu_de', 'Tiêu đề', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'unread-list-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('Nguoigui.full_name', 'Người gửi', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'unread-list-content'));?></th>
            <th width="130px"><?php echo $this->Paginator->sort('Tinnhan.ngay_gui', 'Ngày gửi', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'unread-list-content'));?></th>
            <th width="60px">Action</th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Tinnhan']['limit']*($this->Paginator->params['paging']['Tinnhan']['page']-1) + 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Chitiettinnhan']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Tinnhan']['tieu_de'], '/tinnhan/view/' . $item['Tinnhan']['id'], array('title' => 'Xem chi tiết tin nhắn'));
				if(!empty($item['FileTinnhan']))
					echo $this->Html->image('icons/attach.png', array('align' => 'right'))
			?>
            </td>
            <td><?php echo $this->Html->link($item['Nguoigui']['full_name'], '/tinnhan/compose/sendto:' . $item['Tinnhan']['nguoigui_id'], array('title' => 'Gửi tin nhắn cho nhân viên này')) ?></td>
            <td><?php echo $this->Time->format("d-m-Y H:i:s", $item['Tinnhan']['ngay_gui']) ?></td>
            <td>
            <?php
				echo $this->Html->link($this->Html->image('icons/email_go.png'), '/tinnhan/compose/replyto:' . $item['Tinnhan']['id'], array('title' => 'Trả lời tin nhắn này', 'escape' => false));
				echo '&nbsp;';
				echo $this->Html->link($this->Html->image('icons/email_delete.png'), '/tinnhan/unread_delete', array('title' => 'Xóa tin nhắn này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Tin nhắn này không ?', 'data-id' => $item['Chitiettinnhan']['id'], 'data-target' => 'unread-list-content', 'escape' => false));
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
                <?php if($this->Paginator->params['paging']['Tinnhan']['pageCount'] > 1):?>
            	<div class="pagination">
                	[Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                    <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'unread-list-content'))?>
                    <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'unread-list-content'))?>
					<?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'unread-list-content')); ?>
                    <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'unread-list-content'))?>
                    <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'unread-list-content'))?>
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
