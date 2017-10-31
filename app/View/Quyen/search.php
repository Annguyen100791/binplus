<form id="form-quyen-search">    
<div class="formPanel">
    <p>
        Từ khóa : <input name="ten_quyen" id="s_ten_quyen" class="text-input" />
        <select name="prefix">
            <option value=""> .. Tìm theo .. </option>
            <?php
                if(!empty($prefixs))
                    foreach($prefixs as $k=>$v):
                        printf("<option value='%s'>%s</option>", $k, $v);
                    endforeach;
            ?>
        </select>
    </p>
</div>    
<div style="text-align:right!important" class="dialog-footer">
    <button class="button" type="submit">Tìm kiếm</button>
    <button class="button btn-close" type="button">Đóng</button>
</div>
</form>
<script>
	$(document).ready(function(){
		
		$('#form-quyen-search').submit(function(){
			var $this = this;
			$.ajax({
				type:		'POST',
				url:		BIN.baseURL + 'quyen/index',
				data:		$('#form-quyen-search').serialize(),
				success:	function(result)
				{
					$($this).find('.btn-close').first().click();
					$('#quyen-list').html(result);
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