<?php
	if(!empty($ds)):
		$this->Paginator->options( 
			array('url'		=> 	$this->passedArgs)); 
		foreach($ds as $item):
	?>
		<li>
			<a href="javascript:viewTinnhan(<?php echo $item['Tinnhan']['id'] ?>)" style="padding-left:80px">
            	<img src="/img/mobile/email_closed.png" style="padding:10px" />
				<h4 style="font-size:13px"><?php echo $item['Tinnhan']['tieu_de'] ?></h4>
				<p><?php printf("Người gửi: %s", $item['Nguoigui']['full_name']) ?></p>
                <p class="ui-li-aside"><?php echo  $this->Time->format('d-m-Y H:i:s', $item['Tinnhan']['ngay_gui'])?></p>
			</a>
            <a href="javascript:showTinnhanOptions(<?php echo $item['Tinnhan']['id'] ?>)" data-icon="question">Lựa chọn thao tác</a>
		</li>
	<?php
		endforeach;
	?>
    	<script>
				var prev = $('<?php echo $this->Paginator->prev(); ?>').find('a').attr('href');
				var next = $('<?php echo $this->Paginator->next(); ?>').find('a').attr('href');
				
				if(prev == undefined)
				{
					$('#chuadoc-prev').button('disable');
				}else
				{
					$('#chuadoc-prev').button('enable');
					$('#chuadoc-prev').attr('href', prev);
				}
				if(next == undefined)
				{
					$('#chuadoc-next').button('disable');
				}else
				{
					$('#chuadoc-next').button('enable');
					$('#chuadoc-next').attr('href', next);
				}
            </script>
    <?php
	else:
		echo '<li>Hiện tại chưa có tin nhắn nào mới.</li>';
	endif;
?>