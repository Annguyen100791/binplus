<!--  start table-content  -->
<div id="table-content">

    <!--  start message -->
    <?php
		echo $this->Session->flash();
	?>
    <!--  end message -->
    
    <!--  start data-table ..................................................................................... -->

<?php
	if(!empty($ds)):
	
	$this->Paginator->options( 
            array('update'	=>	'#nhomquyen-list-content',  
                  'url'		=> 	$this->passedArgs,  
				  'before' 	=> $this->Js->get('#nhomquyen-list-content')->effect('fadeOut', array('buffer' => false)),
				  'complete' => $this->Js->get('#nhomquyen-list-content')->effect('fadeIn', array('buffer' => false))
				)); 
?>
<div class="data">
	<table id="nhomquyen-data">
        <thead>
        <tr>
        	<th><input class="check-all" type="checkbox" /></th>
        	<th width="32px">STT</th>
            <th width="30%"><?php echo $this->Paginator->sort('ten_nhomquyen', 'Tên Nhóm quyền hạn');?></th>
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
					echo $this->Html->link($child['ten_quyen'], '/quyen/view/' . $child['id'], array('title' => 'Xem chi tiết Quyền hạn này', 'rel' => "facebox")) . ", ";
		?>
        </td>
        <td align="center">
        <?php
			echo $this->Html->link($this->Html->image('icons/pencil.png'), '/nhomquyen/edit/' . $item['Nhomquyen']['id'], array('title' => 'Hiệu chỉnh Nhóm quyền hạn này', 'escape' => false));
			echo '&nbsp;';
			echo $this->Html->link($this->Html->image('icons/cross.png'), '#', array('title' => 'Xóa nhóm quyền hạn này', 'rel' => $item['Nhomquyen']['id'], 'escape' => false, 'class' => 'delete-item'));
		?>
        </td>
    </tr>
        <?php
	endforeach;
	
?>
	</tbody>
    <tfoot>
    	<tr>
        	<td colspan="5">
                <?php if($this->Paginator->params['paging']['Nhomquyen']['pageCount'] > 1):?>
            	<div id="pagination">
                	[Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                        <?php echo $this->Paginator->first('|<', array('class' => 'number'))?>
                        <?php echo $this->Paginator->prev('<', array('class' => 'number'))?>
                        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number')); ?>
                        <?php echo $this->Paginator->next('>', array('class' => 'number'))?>
                        <?php echo $this->Paginator->last('>|', array('class' => 'number'))?>
                </div>
                <?php endif;?>
            </td>
        </tr>
    </tfoot>
    </table>
</div>    
<script>
	$(document).ready(function(){
		init_dsNhomQuyen();
	});
</script>
<?php
	echo $this->Js->writeBuffer(); 
	endif;
?>

</div>
<!--  end content-table  -->