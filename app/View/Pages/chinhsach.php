<?php 
	if(!empty($chinhsach)): 
	$this->Paginator->options( 
	array('update'	=>	'#news-chinhsach',  
		  'url'		=> 	$this->passedArgs,  
		  'before' 	=> $this->Js->get('#news-chinhsach')->effect('fadeOut', array('buffer' => false)),
		  'complete' => $this->Js->get('#news-chinhsach')->effect('fadeIn', array('buffer' => false))
		));
?>
<h3><?php echo $chinhsach[0]['Chinhsach']['tieu_de'] ?></h3>

<?php echo nl2br($chinhsach[0]['Chinhsach']['noi_dung']); ?>
<?php
	if($this->Paginator->params['paging']['Chinhsach']['pageCount'] > 1):
	
		echo '<div id="pagination">' . $this->Paginator->prev('< Mới hơn', array('class' => 'number', 'escape' => false)) . $this->Paginator->next('   Trước đó >', array('class' => 'number', 'escape' => false)) . '</div>';
	echo $this->Js->writeBuffer(); 
	endif;
?>
<?php else: ?>
	Hiện tại chưa có chính sách mới.
<?php endif;?>