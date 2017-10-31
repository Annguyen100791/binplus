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
            <th width="80%">Tên nhóm</th>
            <th width="80px">Thứ tự</th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php
		$stt = 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Group']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Group']['ten_nhom'], '/groups/edit/' . $item['Group']['id'], array('title' => 'Chỉnh sửa nhóm làm việc', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
			?>
            </td>
            <td>
			<?php
                echo $this->Html->image('icons/arrow_down.png', array('rel' => $item['Group']['id'], 'style' => 'cursor:pointer', 'title' => 'Move down', 'class' => 'move-down'));
				echo $this->Html->image('icons/arrow_up.png', array('rel' => $item['Group']['id'], 'style' => 'cursor:pointer', 'title' => 'Move up', 'class'	=>	'move-up'));
            ?>
            </td>
            <td align="center">
			<?php
                echo $this->Html->link($this->Html->image('icons/pencil.png'), '/groups/edit/' . $item['Group']['id'], array('title' => 'Hiệu chỉnh nhóm làm việc', 'data-mode' => "ajax", 'data-action' => 'dialog', 'escape' => false));
				echo '&nbsp;';
				echo $this->Html->link($this->Html->image('icons/cross.png'), '/groups/delete', array('title' => 'Click để xóa nhóm làm việc này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa nhóm làm việc này không ?', 'data-id' => $item['Group']['id'], 'data-target' => 'phong-list', 'escape' => false, 'class' => 'delete-item'));
            ?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
    </tbody>
    </table>
</div>    
<?php
    endif;
?>
</div>
<script>
	$(document).ready(function(){
		BIN.doListing();
		$('img.move-up').click(function(){
			var id = $(this).attr('rel');
			$.ajax({
					type:		'GET',
					url:		BIN.baseURL + 'groups/move/up/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="groups/index" data-target="groups-list">');
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
			
				return false;
		});
		
		$('img.move-down').click(function(){
			var id = $(this).attr('rel');
			$.ajax({
					type:		'GET',
					url:		BIN.baseURL + 'groups/move/down/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="groups/index" data-target="groups-list">');
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
				return false;
		});
	});
</script>