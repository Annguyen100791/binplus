<?php
	if(!empty($ds)):
		$this->Paginator->options( 
			array('url'		=> 	$this->passedArgs)); 
		foreach($ds as $item):
	?>
		<li>
			<a href="javascript:viewVanban(<?php echo $item['Vanban']['id'] ?>)" style="padding-left:80px">
            	<img src="/img/mobile/note.png" style="padding:10px" />
				<h4 style="font-size:13px"><?php printf("%s - %s", $item['Vanban']['so_hieu'], $item['Vanban']['trich_yeu']) ?></h4>
				<p><?php printf("Ngày phát hành: %s - Nơi phát hành: %s", $this->Time->format('d-m-Y', $item['Vanban']['ngay_phathanh']), $item['Vanban']['noi_gui']) ?></p>
			</a>
		</li>
	<?php
		endforeach;
		
		?>
        	<script>
				var prev = $('<?php echo $this->Paginator->prev(); ?>').find('a').attr('href');
				var next = $('<?php echo $this->Paginator->next(); ?>').find('a').attr('href');
				
				if(prev == undefined)
				{
					$('#prev').button('disable');
				}else
				{
					$('#prev').button('enable');
					$('#prev').attr('href', prev);
				}
				if(next == undefined)
				{
					$('#next').button('disable');
				}else
				{
					$('#next').button('enable');
					$('#next').attr('href', next);
				}
            </script>
        <?php
	else:
		echo '<li>Hiện tại chưa có văn bản nào.</li>';
	endif;
?>