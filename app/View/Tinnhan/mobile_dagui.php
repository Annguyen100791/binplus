<?php
	if(!empty($ds)):
		$this->Paginator->options( 
			array('url'		=> 	$this->passedArgs)); 
		foreach($ds as $item):
	?>
		<li>
			<a href="javascript:viewTinnhanDagui(<?php echo $item['Tinnhan']['id'] ?>)" style="padding-left:80px">
            	<img src="/img/mobile/email_write.png" style="padding:10px" />
				<h5 style="font-size:13px"><?php echo $item['Tinnhan']['tieu_de'] ?></h5>
                <p><?php echo  $this->Time->format('d-m-Y H:i:s', $item['Tinnhan']['ngay_gui'])?></p>
			</a>
            <a href="#" onClick="return deleteTinnhanDagui(<?php echo $item['Tinnhan']['id'] ?>)" data-role="button" data-icon="trash" data-iconpos="notext">Xóa tin nhắn này</a>
		</li>
	<?php
		endforeach;
	?>
    	<script>
				var prev = $('<?php echo $this->Paginator->prev(); ?>').find('a').attr('href');
				var next = $('<?php echo $this->Paginator->next(); ?>').find('a').attr('href');
				
				if(prev == undefined)
				{
					$('#dagui-prev').button('disable');
				}else
				{
					$('#dagui-prev').button('enable');
					$('#dagui-prev').attr('href', prev);
				}
				if(next == undefined)
				{
					$('#dagui-next').button('disable');
				}else
				{
					$('#dagui-next').button('enable');
					$('#dagui-next').attr('href', next);
				}
            </script>
    <?php
	else:
		echo '<li>Hiện tại chưa có tin nhắn nào.</li>';
	endif;
?>