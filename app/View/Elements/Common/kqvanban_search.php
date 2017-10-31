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
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('Vanban.nguoi_duyet', 'Người duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="180px"><?php echo $this->Paginator->sort('Vanban.nguoi_ky', 'Đơn vị phối hợp', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.vb_ngayhoanthanh', 'Ngày duyệt', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
             <th width="80px"><?php echo $this->Paginator->sort('Vanban.vb_ngayhoanthanh', 'Ngày hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'vanban-search-content'));?></th>
             <th width="80px"><?php echo $this->Paginator->sort('Vanban.ket_qua', 'Kết quả', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'gap-list-content'));?></th>
            <th width="80px"><?php echo $this->Paginator->sort('Vanban.hoan_thanh', 'Hoàn thành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'gap-list-content'));?></th>
            <th width="1px">Thao tác</th>
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
           <!-- <td><?php echo $item['Phong']['ten_phong'] ?></td> -->
            <td>
			<?php

				foreach($item['Phongphoihop'] as $i):
					echo $i['DSPhong']['ten_phong'].'<br>';
				endforeach;
			?>
            </td>
            <td align="center">
            <?php
				if(!empty($item['Vanban']['ngay_duyet']))
					echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_duyet'])
			?>
            </td>
            <td>
            <?php
				if($item['Vanban']['vb_gap'] == 1)
					echo $this->Time->format("d-m-Y", $item['Vanban']['vbgap_ngayhoanthanh'])


			?>
            </td>
            <td align="center">
            <?php
        		$tt = array();
				if(!empty($item['Ketquavanban']))
				{
					foreach($item['Ketquavanban'] as $i_kq)
					{
						//if($i_kq['nguoi_capnhat_id']  ==  $this->Session->read('Auth.User.nhanvien_id') || $i_kq['nguoi_nhan_id']  ==  $this->Session->read('Auth.User.nhanvien_id') || $this->Session->read('Auth.User.nhanvien_id') == 681 || $this->Session->read('Auth.User.nhanvien_id') == 683 )
						if($i_kq['nguoi_capnhat_id']  ==  $this->Session->read('Auth.User.nhanvien_id'))
							$tt = $i_kq;
					}
				if(!empty($tt))
					echo $this->Html->link("Đã báo cáo", '/vanban/view_ketquabc/' . $item['Vanban']['id'], array('tip-position' => 'bottom left', 'title' =>'</p><i>(Click để xem chi tiết )</i>'));
				}
				/*if(!empty($item['Ketquavanban'])):
					echo $this->Html->link("Đã báo cáo", '/vanban/view_ketquabc/' . $item['Vanban']['id'], array('tip-position' => 'bottom left', 'title' =>'</p><i>(Click để xem chi tiết )</i>'));
				endif;*/
			?>
            </td>
			<td>
            	 <?php
				 	$t = 0;
				 	if(!empty($item['Ketquavanban']) && !empty($tt))
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
				?>
            </td>
            <td>
            <?php
				if($this->Session->read('Auth.User.donvi_id') != '')
					$donvi_id = $this->Session->read('Auth.User.donvi_id');
				else
					$donvi_id = $this->Session->read('Auth.User.phong_id');

				if($this->Session->read('Auth.User.nhanvien_id')== 683) // phát sinh từ khi chia nhóm P.THHC
					$donvi_id = 20;
				$t = true;
				if(!empty($item['Ketquavanban']))
				{
					for ($i = 0; $i < count($item['Ketquavanban']); $i++)
					{
						if($item['Ketquavanban'][$i]['capnhat_mucdo'] == 10 && $item['Ketquavanban'][$i]['nguoi_capnhat_id'] == $this->Session->read('Auth.User.nhanvien_id')){
  							$t = false;
              }

					}

				}
				if($t && ($item['Vanban']['phongchutri_id'] == $donvi_id && ($this->Session->read('Auth.User.chucdanh_id') == 16 || $this->Session->read('Auth.User.chucdanh_id') == 30 || $this->Session->read('Auth.User.nhanvien_id') == 804)))
						//echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/' . $item['Vanban']['id'], array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));
				echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/'.'id:' . $item['Vanban']['id']. '/'. 'type:search_kq' , array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));
				
				//if(($t['capnhat_mucdo']< 10 || empty($item['Ketquavanban'])) && $item['Vanban']['phongchutri_id'] == $donvi_id &&($this->Session->read('Auth.User.chucdanh_id') == 16 || $this->Session->read('Auth.User.chucdanh_id') == 30 || $this->Session->read('Auth.User.nhanvien_id') == 804))
						//echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/' . $item['Vanban']['id'], array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));

				/*if($item['Vanban']['phongchutri_id'] == $donvi_id &&($this->Session->read('Auth.User.chucdanh_id') == 16 || $this->Session->read('Auth.User.chucdanh_id') == 30))
					echo $this->Html->link($this->Html->image('icons/note_edit.png', array('class' => 'icon-button')), '/vanban/up_result/' . $item['Vanban']['id'], array('title' => 'Cập nhật kết quả xử lý văn bản', 'escape' => false));*/
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
    <div style=" padding:10px 0"><span style="font-weight:bold; text-decoration:underline;">Chú thích</span></div>
    <div>
        <div class="prog-bar-blue" style="width:30px; height:15px; background-color:#fffbcc; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Văn bản gấp chưa trễ tiến độ hoặc đã hoàn thành</div>
        <div class="prog-bar-red" style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Văn bản gấp trễ tiến độ</div>

    </div>
    <div style="clear:both"></div>
</div>
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
