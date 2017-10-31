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
	<table id="all-data">
        <thead>
        <tr>
        	<th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="80px">Số đến</th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_nhap', 'Ngày nhập', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="90px"><?php echo $this->Paginator->sort('Vanban.chieu_di', 'Chiều VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="40%"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'));?></th>
            <th width="1px"></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Nhanvanban']['limit']*($this->Paginator->params['paging']['Nhanvanban']['page']-1) + 1;
		$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Nhanvanban']['id'] ?>" /></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
				if(empty($item['Nhanvanban']['ngay_xem']))
					echo $this->Html->image('icons/new.png', array('style' => 'vertical-align:text-top; margin-left: 5px'));
			?>
            </td>
            <td>
            	<?php echo $item['Vanban']['so_hieu_den'] ?>
            </td>
            <td align="center">
            <?php
					echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
				?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            <td align="center">
            <?php
					echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_nhap'])
				?>
            </td>
            <td>
            	<?php
					echo $chieu_di[$item['Vanban']['chieu_di']];
				?>
            </td>
            <td>
            <?php
				if(empty($item['Nhanvanban']['ngay_xem']))
					echo '<b>' . $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản')) . '</b>';
				else
					echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            
            <td>
            <?php
				if($f_theodoi && empty($item['Vanban']['Theodoivanban']))
					echo $this->Html->link($this->Html->image('icons/star.png', array('class' => 'icon-button')), '/vanban/danhdau_theodoi/type:all/target:all-ttvt5-list-content/id:' . $item['Vanban']['id'], array('escape' => false, 'title' => 'Theo dõi văn bản này', 'class' => 'follow'));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <!--<div style="float:left; padding: 20px 0">
        <a href="/vanban/mark_read/type:all" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn đánh dấu văn bản đã chọn hay không ?" data-target="all-ttvt5-list-content">Đánh dấu văn bản đã đọc</a>
    </div>-->
    <?php if($this->Paginator->params['paging']['Nhanvanban']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'all-ttvt5-list-content'))?>
    </div>
    <?php endif;?>
    <div style="clear:both"></div>
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