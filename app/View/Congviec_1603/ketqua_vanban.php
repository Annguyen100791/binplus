<!--  start table-content  -->
<?php
	echo $this->Session->flash();
	if(!empty($data)):
?>
<div class="data table-content" style="padding:10px">
	<table>
        <thead>
        <tr>
            <th width="10%">Số hiệu</th>
            <th width="10%">Ngày phát hành</th>
            <th width="20%">Nơi phát hành</th>
            <th width="60%">Trích yếu</th>
            <th>Chọn</th>
        </tr>
        </thead>
    <tbody>
    <?php
		foreach($data as $item):
	?>
    	<tr>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'], array('title' => 'Xem chi tiết văn bản', 'target' => '_blank'));
			?>
            </td>
            <td align="center">
            <?php
				echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
			?>
            </td>
            <td><?php echo $item['Vanban']['noi_gui'] ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'], array('title' => 'Xem chi tiết văn bản', 'target' => '_blank'));
			?>
            </td>
            <td>
            	<a href="#" rel="<?php echo $item['Vanban']['id'] ?>" class="chon-vanban"><img src="/img/icons/page_white_star.png" title="Click để chọn văn bản này"/></a>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
</div>    
<script>
	$(document).ready(function(){
		BIN.doListing();
		$('.chon-vanban').click(function(){
			var rows = $(this).parent().parent().find('td');// tr
			$('#congviec-vanban').html('<div class="uploaded_item"><div style="float:left">' + rows[3].innerHTML + '</div><div style="float:right"><a href="javascript:void(0)" onClick="unselect()"><img src="/img/closelabel.png" title="Bỏ chọn"></a></div><div style="clear:both"></div><input type="hidden" name=data[Congviec][vanban_id] value="' + $(this).attr('rel') + '"></div>');
			$('#form-vanban-search').find('.btn-close').first().click();
		});
	});
</script>
<?php
	endif;
?>
