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
	<table>
        <thead>
        <tr>
        	<th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="1px">STT</th>
            <th width="50%"><?php echo $this->Paginator->sort('Tinnhan.tieu_de', 'Tiêu đề', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'sent-list-content'));?></th>
            <th width="120px">Người nhận</th>
            <th width="130px"><?php echo $this->Paginator->sort('Tinnhan.ngay_gui', 'Ngày gửi', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'sent-list-content'));?></th>
            <th width="60px">Action</th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Tinnhan']['limit']*($this->Paginator->params['paging']['Tinnhan']['page']-1) + 1;
		foreach($ds as $item):
		
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Tinnhan']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Tinnhan']['tieu_de'], '/tinnhan/view_sent/' . $item['Tinnhan']['id'], array('title' => 'Xem chi tiết tin nhắn'));
				if(!empty($item['FileTinnhan']))
					echo $this->Html->image('icons/attach.png', array('align' => 'right'))
			?>
            </td>
            <td>
			<?php 
				//pr($item['Chitiettinnhan']);die();
					if(!empty($item['Chitiettinnhan']))
					{
						if(isset($item['Chitiettinnhan']['Nguoinhan']))
							echo $item['Chitiettinnhan']['Nguoinhan']['full_name'] ;
						else{
							if(count($item['Chitiettinnhan']) > 1)
								echo "Nhiều người";
							else
								echo $item['Chitiettinnhan'][0]['Nguoinhan']['full_name'] ;
						}
					}
				//}
			?>
            </td>
            <td><?php echo $this->Time->format("d-m-Y H:i:s", $item['Tinnhan']['ngay_gui']) ?></td>
            <td>
            <?php
				echo $this->Html->link($this->Html->image('icons/email_go.png'), '/tinnhan/compose/resendto:' . $item['Tinnhan']['id'], array('title' => 'Chuyển tiếp tin nhắn này', 'escape' => false));
				echo '&nbsp;';
				echo $this->Html->link($this->Html->image('icons/email_delete.png'), '/tinnhan/sent_delete', array('title' => 'Xóa tin nhắn này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Tin nhắn này không? Ghi chú: Các tin nhắn đã có người đọc sẽ không được xóa', 'data-id' => $item['Tinnhan']['id'], 'data-target' => 'sent-list-content', 'escape' => false));
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
                	[Page <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                    <?php echo $this->Paginator->first('|< First', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'sent-list-content'))?>
                    <?php echo $this->Paginator->prev('< Prev', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'sent-list-content'))?>
					<?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'sent-list-content')); ?>
                    <?php echo $this->Paginator->next('Next >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'sent-list-content'))?>
                    <?php echo $this->Paginator->last('Last >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'sent-list-content'))?>
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