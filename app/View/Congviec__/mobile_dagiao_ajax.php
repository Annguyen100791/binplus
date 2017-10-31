<?php
	if(!empty($ds)):
		$this->Paginator->options( 
			array('url'		=> 	$this->passedArgs)); 
		App::uses('Sanitize', 'Utility');
		foreach($ds as $item):
	?>
		<li>
			<a href="/mobile/congviec/view/<?php echo $item['Chitietcongviec']['congviec_id'] ?>" style="padding-left:80px" data-ajax="false">
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
            <?php if($item['Chitietcongviec']['mucdo_hoanthanh'] < 10): ?>
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
					$('#congviec-dagiao-prev').button('disable');
				}else
				{
					$('#congviec-dagiao-prev').button('enable');
					$('#congviec-dagiao-prev').attr('href', prev);
				}
				if(next == undefined)
				{
					$('#congviec-dagiao-next').button('disable');
				}else
				{
					$('#congviec-dagiao-next').button('enable');
					$('#congviec-dagiao-next').attr('href', next);
				}
            </script>
    <?php
	else:
		echo '<li>Hiện tại chưa có công việc nào.</li>';
	endif;
?>