<?php
	if(!empty($ds)):
		$this->Paginator->options( 
			array('url'		=> 	$this->passedArgs)); 
		App::uses('Sanitize', 'Utility');
		foreach($ds as $item):
	?>
		<li>
			<a href="/mobile/congviec/view/<?php echo $item['Chitietcongviec']['congviec_id'] ?>" style="padding-left:80px" data-ajax="false" title="click để xem chi tiết">
            	<img src="/img/mobile/task.png" style="padding:10px" />
				<h3><?php echo $item['Chitietcongviec']['ten_congviec'] ?></h3>
                <p>
                	<?php
						echo $this->Text->truncate($item['Chitietcongviec']['noi_dung'], 300, array('ending' => '...', 'exact' =>  true, 'html' => false));
					?>
                </p>
                <p>
                	<?php
						printf("Từ ngày: %s, đến ngày: %s, giao việc cho %s", 
								$this->Time->format('d-m-Y', $item['Chitietcongviec']['ngay_batdau']),
								$this->Time->format('d-m-Y', $item['Chitietcongviec']['ngay_ketthuc']),
								$item['NguoiNhanviec']['full_name']);
					?>
                </p>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $item['Chitietcongviec']['mucdo_hoanthanh']*10; ?>%</span>
			</a>
            <?php if($item['Chitietcongviec']['mucdo_hoanthanh'] < 10):?>
           		<a href="#" onClick="return capnhatTiendo(<?php echo $item['Chitietcongviec']['id'] ?>)" data-role="button" data-icon="edit" data-iconpos="notext">Cập nhật tiến độ hoàn thành công việc</a>
            <?php endif;?>
		</li>
	<?php
		endforeach;
	?>
    	<script>
				var prev = $('<?php echo $this->Paginator->prev(); ?>').find('a').attr('href');
				var next = $('<?php echo $this->Paginator->next(); ?>').find('a').attr('href');
				
				if(prev == undefined)
				{
					$('#congviec-duocgiao-prev').button('disable');
				}else
				{
					$('#congviec-duocgiao-prev').button('enable');
					$('#congviec-duocgiao-prev').attr('href', prev);
				}
				if(next == undefined)
				{
					$('#congviec-duocgiao-next').button('disable');
				}else
				{
					$('#congviec-duocgiao-next').button('enable');
					$('#congviec-duocgiao-next').attr('href', next);
				}
            </script>
    <?php
	else:
		echo '<li>Hiện tại chưa có công việc nào.</li>';
	endif;
?>