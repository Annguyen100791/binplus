<form id="form-tinhchatcongviec-search">    
<div class="formPanel">
    <p>
    	<label>Từ khóa : </label>
        <input name="keyword" id="keyword" class="text-input" style="width:97%" />
    </p>
    
</div>    
<div style="text-align:right!important" class="dialog-footer">
    <button class="button" type="submit">Tìm kiếm</button>
    <button class="button btn-close" type="button">Đóng</button>
</div>
</form>
<script>
	$(document).ready(function(){
		
		$('#form-tinhchatcongviec-search').submit(function(){
			var $this = this;
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'tinhchatcongviec/',
				data:		$('#form-tinhchatcongviec-search').serialize(),
				success:	function(result)
				{
					$($this).find('.btn-close').first().click();
					$('#tinhchatcongviec-list').html(result);
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