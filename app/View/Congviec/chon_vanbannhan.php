<form id="form-vanban-search">    
<div class="formPanel" id="search-form" style="float:left">
    <div style="float:left;">
    <?php
		echo $this->Form->input('Vanban.keyword', array(
													  'label'	=>	'Từ khóa',
													  'div'		=>	false,
													  'id'		=>	'keyword',
													  'class'	=>	'text-input',
													  'style'	=>	'width:200px'
													  ));
	?>
    </div>
    <div style="float:left; margin-left:20px">
    <?php
		echo $this->Form->input('Vanban.start', array(
													  'label'	=>	'Phát hành từ ngày',
													  'div'		=>	false,
													  'id'		=>	'start_date',
													  'class'	=>	'text-input',
													  'style'	=>	'width:120px'
													  ));
	?>
    </div>
    <div style="float:left; margin-left:20px">
    <?php
		echo $this->Form->input('Vanban.end', array(
													  'label'	=>	'đến ngày',
													  'div'		=>	false,
													  'id'		=>	'end_date',
													  'class'	=>	'text-input',
													  'style'	=>	'width:120px'
													  ));
	?>
    </div>
<div style="clear:both"></div>
</div>        
    <div style="float:right; padding-top:20px; margin-right:10px">
    	<button class="button" type="submit">Tìm kiếm</button>
        <button class="button btn-close" type="button">Đóng</button>
    </div>
<div style="clear:both"></div>    
<?php
	echo $this->Form->end();
?>

<div id="result-form" style="height:50px; min-height:50px; max-height:200px;overflow:auto">
</div>
<script>
	$(document).ready(function(){
		$('#start_date').datepicker({dateFormat: "dd-mm-yy"});
		$('#end_date').datepicker({dateFormat: "dd-mm-yy"});
		
		$('#form-vanban-search').submit(function(){
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'congviec/chon_vanbannhan',
				cache:		false,
				async:		false,
				data:		$('#form-vanban-search').serialize(),
				success:	function(result)
				{
					$('#result-form').html(result);
					$('#result-form').height(200);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});
	});
</script>