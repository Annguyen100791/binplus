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
            <th width="30%">Tên chức danh</th>
            <th width="30%">Mô tả</th>
            <th>Thứ tự</th>
            <th width="80px">Hiển thị</th>
            <th width="80px">Action</th>
        </tr>
        </thead>
    <tbody>
<?php	
	$up = null;
	
	for($i = 0; $i < $size = count($ds); $i++):
?>
	<tr>
    	<td align="center"><input type="checkbox" value="<?php echo $ds[$i]['Chucdanh']['id'] ?>" /></td>
    	<td align="center"><?php echo $i+1 ?></td>
        <td>
        <?php
			echo $this->Html->link($ds[$i]['Chucdanh']['ten_chucdanh'], '/chucdanh/edit/' . $ds[$i]['Chucdanh']['id'], array('title' => 'Chỉnh sửa chức danh', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '400'));
		?>
        </td>
        <td>
        <?php
			echo $ds[$i]['Chucdanh']['mo_ta']
		?>
        </td>
        <td>
        <?php
			if(isset($ds[$i+1]))
				echo $this->Html->image('icons/arrow_down.png', array('rel' => $ds[$i]['Chucdanh']['id'], 'style' => 'cursor:pointer', 'title' => 'Move down', 'class' => 'move-down'));
			if(isset($ds[$i-1]))
				echo $this->Html->image('icons/arrow_up.png', array('rel' => $ds[$i]['Chucdanh']['id'], 'style' => 'cursor:pointer', 'title' => 'Move up', 'class'	=>	'move-up'));
		?>
        </td>
        <td align="center">
        <?php
            if(empty($ds[$i]['Chucdanh']['enabled']))
				echo $this->Html->link($this->Html->image('icons/cross_circle.png'), '/chucdanh/active/' . $ds[$i]['Chucdanh']['id'], array('title' => 'Click để cho phép hiển thị', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
            else
				echo $this->Html->link($this->Html->image('icons/tick_circle.png'), '/chucdanh/active/' . $ds[$i]['Chucdanh']['id'], array('title' => 'Click để không hiển thị trên trang chủ', 'data-mode' => 'ajax', 'data-action' => 'toggle', 'escape' => false));
        ?>
        </td>
        
        <td align="center">
        <?php
			echo $this->Html->link($this->Html->image('icons/pencil.png'), '/chucdanh/edit/' . $ds[$i]['Chucdanh']['id'], array('title' => 'Chỉnh sửa chức danh', 'data-mode' => "ajax", 'data-action' => 'dialog', 'data-width' => '400', 'escape' => false));
			echo '&nbsp;';
			echo $this->Html->link($this->Html->image('icons/cross.png'), '/chucdanh/delete', array('title' => 'Xóa Chức danh này', 'data-mode' => "ajax", 'data-action' => 'delete-item', 'data-confirm' => 'Bạn có muốn xóa Chức danh này không ?', 'data-id' => $ds[$i]['Chucdanh']['id'], 'data-target' => 'chucdanh-list', 'escape' => false));
		?>
        </td>
    </tr>
        <?php
	endfor;
	
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
					url:		BIN.baseURL + 'chucdanh/move/up/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="chucdanh/index" data-target="chucdanh-list">');
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
					url:		BIN.baseURL + 'chucdanh/move/down/' + id,
					cache:		false,
					async:		false,
					dataType:	'json',
					success:	function(result)
					{
						BIN.doUpdate('<a href="chucdanh/index" data-target="chucdanh-list">');
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