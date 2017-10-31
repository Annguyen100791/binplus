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
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="30%"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.nguoi_duyet', 'Người duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="180px"><?php echo $this->Paginator->sort('Phongchutri.ten_phong', 'Đơn vị chủ trì', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width:auto><?php echo $this->Paginator->sort('Vanban.nguoi_ky', 'Đơn vị phối hợp', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('Vanban.vb_ngayhoanthanh', 'Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="60px">Thao tác</th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
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
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
           	<td><?php echo $item['Vanban']['nguoi_duyet'] ?></td>
            <td><?php echo $item['Phong']['ten_phong'] ?></td>
            <td>
			<?php 
				
				foreach($item['Phongphoihop'] as $i):
					echo $i['DSPhong']['ten_phong'].'<br>';
				endforeach;
			?>
            </td>
            <td align="center">
            <?php
				if($item['Vanban']['vb_gap'] == 1)
					echo $this->Time->format("d-m-Y", $item['Vanban']['vbgap_ngayhoanthanh'])
			?>
            </td>
            <td>
            <?php
				echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/' . $item['Vanban']['id'], array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));
				
				
			?>
            </td>
            
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <div style="float:left; padding: 20px 0">
        <a class="button" href="#" id="btn-expexcel-searchkq" target="_blank"><?php echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a>
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