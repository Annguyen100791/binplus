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
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Số hiệu VB', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="160px"><?php echo $this->Paginator->sort('Vanban.noi_gui', 'Nơi phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.ngay_duyet', 'Ngày duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="320px"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.phongchutri_id', 'Phòng chủ trì', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th><?php echo $this->Paginator->sort('Vanban.ket_qua', 'Kết quả', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.hoan_thanh', 'Hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'notice-khac-list-content'));?></th>
            <th width="1px"></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = $this->Paginator->params['paging']['Vanban']['limit']*($this->Paginator->params['paging']['Vanban']['page']-1) + 1;
		$f_theodoi = $this->Layout->check_permission('VanBan.theodoi');
		foreach($ds as $item):
	?>
    	<tr >
            <td align="center"><input type="checkbox" value="<?php echo $item['Vanban']['id'] ?>" /></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Click để xem chi tiết văn bản'));
				//if(empty($item['Nhanvanban']['ngay_xem']))
				//{
					//echo $this->Html->image('icons/new.png', array('style' => 'vertical-align:text-top; margin-left: 5px'));
				//}
					
			?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            
            <td align="center">
            <?php
				if(!empty($item['Vanban']['ngay_duyet']))
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_duyet'])
			?>
            </td>
            <td>
            <?php
				//if(empty($item['Nhanvanban']['ngay_xem']))
					//echo '<b>' . $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản')) . '</b>';
				//else
					echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
			?>
            </td>
            <td align="center">
            <?php
				echo $item['Phong']['ten_phong'];
			?>
            </td>
            <td align="center">
            <?php
				if(!empty($item['Ketquavanban'])):
					echo $this->Html->link("Đã báo cáo", '/vanban/view_ketquabc/' . $item['Vanban']['id'], array('tip-position' => 'bottom left', 'title' =>'</p><i>(Click để xem chi tiết )</i>'));
				endif;
			?>
            </td>
			<td>
            	 <?php
				 	$t = 0;
				 	if(!empty($item['Ketquavanban']))
					{
						//$t = $item['Vanban']['Ketquavanban'][0]['capnhat_mucdo'];
						$t = $item['Ketquavanban'][0];
						for ($i = 1; $i < count($item['Ketquavanban']); ++$i)
						{
							if($item['Ketquavanban'][$i]['capnhat_mucdo'] > $t['capnhat_mucdo'])
								//$t = $item['Vanban']['Ketquavanban'][$i]['capnhat_mucdo'];
								$t = $item['Ketquavanban'][$i] ;
						}
						if(!empty($t['ngay_capnhat']) && $this->Time->format('Y-m-d', $t['ngay_capnhat']) <= $item['Vanban']['vbgap_ngayhoanthanh'])
							echo '<div class="prog-bar-blue" style="width: ' . $t['capnhat_mucdo']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $t['capnhat_mucdo']*10 . '%</div></div>';
						else
							echo '<div class="prog-bar-red" style="width: ' . $t['capnhat_mucdo']*10 . '%; text-align: center;"><div class="prog-bar-text">' . $t['capnhat_mucdo']*10 . '%</div></div>';	
					}
					//else 
						//echo '<div class="prog-bar-none" style="width:30%; text-align: center;"></div>';
				?>
            </td>
            <td>
            <?php
				//echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/' . $item['Vanban']['id'], array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));
				echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/'.'id:' . $item['Vanban']['id']. '/'. urlencode("type:index_notice#notice-khac") , array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    <!--<div style="float:left; padding: 20px 0">
        <a href="/vanban/mark_read/type:den" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn đánh dấu văn bản đã chọn hay không ?" data-target="notice-khac-list-content">Đánh dấu văn bản đã đọc</a>
        <!--<a class="button" href="#" id="btn-expexcel-den" target="_blank" title="Xuất kết quả tìm kiếm ra file Excel"><?php //echo $this->Html->image('icons/page_excel.png', array('align' => 'left')) ?> &nbsp;Xuất kết quả ra file Excel</a> 
    </div> -->
    <?php if($this->Paginator->params['paging']['Vanban']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
        <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'notice-khac-list-content'))?>
        <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'notice-khac-list-content'))?>
        <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'notice-khac-list-content')); ?>
        <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'notice-khac-list-content'))?>
        <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'notice-khac-list-content'))?>
    </div>
    <?php endif;?>
</div>
</div>
    <div style=" padding:10px 0"><span style="font-weight:bold; text-decoration:underline;">Chú thích</span></div>
    <div>
        <div class="prog-bar-red" style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Văn bản đến(khác) trễ tiến độ</div>
       <!-- <div class="prog-bar-none" style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Văn bản đến(khác) chưa cập nhật kết quả xử lý</div> -->
    </div>
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