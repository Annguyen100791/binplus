<!--  start table-content  -->
    <!--  start message -->
    <?php
		echo $this->Session->flash();
	?>
    <!--  end message -->

<?php
	//pr($ds);die();
	if(!empty($ds)):
	
	$this->Paginator->options( 
            array('url'		=> 	$this->passedArgs,  
				)); 
?>
<div class="table-content data">
	<table id="search-data">
        <thead>
        <tr>
        	<th width="1px">STT</th>
            <th width="110px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="60px"><?php echo $this->Paginator->sort('Vanban.chieu_di', 'Chiều VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="110px"><?php echo $this->Paginator->sort('Vanban.nguoi_ky', 'Người ký', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="110px"><?php echo $this->Paginator->sort('Vanban.nguoi_ky', 'Người duyệt ', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="1px"></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		$f_quanly = $this->Layout->check_permission('VanBan.quanly');
		foreach($ds as $item):
	?>
    	<tr>
        	<td style="text-align:center!important"><?php echo $stt++; ?></td>
            <td>
            <?php
				//pr($item);die();
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td><?php echo $chieu_di[$item['Vanban']['chieu_di']] ?></td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
			?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            <td><?php echo $item['Vanban']['nguoi_ky'] ?></td>
            <td><?php echo $item['Vanban']['nguoi_duyet'] ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td>
            <?php
				if($f_theodoi && empty($item['Theodoivanban']['id']))
					echo $this->Html->link($this->Html->image('icons/star.png', array('class' => 'icon-button')), '/vanban/danhdau_theodoi_ajax/id:' . $item['Vanban']['id'], array('escape' => false, 'title' => 'Theo dõi văn bản này', 'class' => 'follow'));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <div style="float:left; padding: 20px 0">
        <a class="button" href="#" id="btn-expexcel-search" target="_blank"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a>
    </div>
    <?php if($this->Paginator->params['paging']['Vanban']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'))?>
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