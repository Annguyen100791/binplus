<div align="right" style="padding-bottom:10px">
<form id="form-unread-search" onsubmit="doSubmit(); return false;">
    Tìm theo nội dung : 
    <?php
        echo $this->Form->input('Tinnhan.keyword', array(
                                                      'label'	=>	false,
                                                      'div'		=>	false,
                                                      'class'	=>	'text-input'
                                                      ));
    ?>
    <a class="button" href="#" id="btn-unread-search">Tìm kiếm nhanh</a>
</form>
</div>
<div class="content-box-content" id="unread-list-content" style="padding:5px 0px">
    <img src="/img/circle_ball.gif" />
</div>
<script>
	function	doSubmit()
	{
		$.ajax({
				type:		'POST',
				url:		root + 'vanban/unread_ajax',
				cache:		false,
				async:		false,
				data:		$('#form-unread-search').serialize(),
				success:	function(result)
				{
					$('#unread-list-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
	}
	
	$(document).ready(function(){
		//jQuery('a[rel*=facebox]').facebox();
		
		updateContent(root + 'vanban/unread_ajax', null, 'unread-list-content');
		
		$('#btn-unread-search').click(function(){
			doSubmit();
			return false;
		});
		
		$('#btn-unread-delete').click(function(){
			var ids = '';
			$('#unread-data input[type=checkbox]').each(function(){
				if(this.checked && this.value != 'on')
					ids = ids + this.value + ',';
			});
			if(ids == '')
			{
				alert('Vui lòng chọn trong danh sách.');
				return false;
			}							  
													  
			if(confirm('Bạn có muốn xóa các dòng đã chọn ?'))
			{
				
				$.ajax({
					type:		'POST',
					url:		root + 'vanban/unread_delete',
					cache:		false,
					async:		false,
					dataType:	'json',
					data:	{v_id: ids},
					success:	function(result)
					{
						updateContent(root + 'vanban/unread_ajax', null, 'unread-list-content');
					},
					error:		function(result)
					{
						alert(result.message);
					}
				});	
				return false;
			}
		});
	});
</script>