<?php 
	if(!empty($thongtin)): 
	$this->Paginator->options( 
	array('update'	=>	'#news-content',  
		  'url'		=> 	$this->passedArgs,  
		  'before' 	=> $this->Js->get('#news-content')->effect('fadeOut', array('buffer' => false)),
		  'complete' => $this->Js->get('#news-content')->effect('fadeIn', array('buffer' => false))
		)); 
?>
<h3><?php echo $thongtin[0]['Tintuc']['tieu_de'] ?></h3>

<?php echo nl2br($thongtin[0]['Tintuc']['noi_dung']); ?>
<?php
	if($this->Paginator->params['paging']['Tintuc']['pageCount'] > 1):
	
		echo '<div id="pagination">' . $this->Paginator->prev('< Mới hơn', array('class' => 'number', 'escape' => false)) . $this->Paginator->next('   Trước đó >', array('class' => 'number', 'escape' => false)) . '</div>';
	echo $this->Js->writeBuffer(); 
	endif;
?>
<?php else: ?>
	Hiện tại chưa có thông tin mới.
<?php endif;?>