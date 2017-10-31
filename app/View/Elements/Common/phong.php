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
        	<th width="1px"><input class="check-all" type="checkbox" title="Chọn/ bỏ chọn tất cả" /></th>
            <th width="30%">Tên phòng ban/ Đơn vị</th>
            <th width="30%">Địa chỉ</th>
            <th></th>
            <th>Thứ tự</th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
	foreach($ds as $item):
?>
	<tr>
    	<td align="center"><input type="checkbox" value="<?php echo $item['Phong']['id'] ?>" /></td>
        <td>
        <?php
			echo $item['Phong']['tree_prefix'];
			echo $this->Html->link($item['Phong']['ten_phong'], '/phong/edit/' . $item['Phong']['id'], array('title' => 'Hiệu chỉnh phòng ban', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '400'));
		?>
        </td>
        <td>
        <?php
			echo $item['Phong']['dia_chi']
		?>
        </td>
        <td>
        <?php
			echo $item['Phong']['dien_thoai']
		?>
        </td>
        <td>
        <?php
			echo $item['Phong']['tree_prefix'];
			echo $this->Html->image('icons/arrow_down.png', array('rel' => $item['Phong']['id'], 'style' => 'cursor:pointer', 'title' => 'Move down', 'class' => 'move-down'));
			echo $this->Html->image('icons/arrow_up.png', array('rel' => $item['Phong']['id'], 'style' => 'cursor:pointer', 'title' => 'Move up', 'class'	=>	'move-up'));
		?>
        </td>
        <td align="center">
        <?php
			echo $this->Html->link($this->Html->image('icons/pencil.png'), '/phong/edit/' . $item['Phong']['id'], array('title' => 'Hiệu chỉnh phòng ban', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '400', 'escape' => false));
			echo '&nbsp;';
			echo $this->Html->link($this->Html->image('icons/cross.png'), '/phong/delete', array('title' => 'Click để xóa Phòng ban này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Phòng ban này không ?', 'data-id' => $item['Phong']['id'], 'data-target' => 'phong-list', 'escape' => false, 'class' => 'delete-item'));
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
					url:		BIN.baseURL + 'phong/move/up/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="phong/index" data-target="phong-list">');
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
					url:		BIN.baseURL + 'phong/move/down/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="phong/index" data-target="phong-list">');
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