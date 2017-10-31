<?php  
	echo $this->Session->flash();
	
?>
<table width="100%" cellpadding="4" cellspacing="4" style="line-height:20px">
<?php
	if(!empty($comments)):
	foreach($comments as $item):
?>
	<tr>
        <td style="vertical-align:top; text-align:right; padding-top:10px; width:160px"><strong><?php echo $this->Html->link($item['NguoiBinhluan']['full_name'], '/nhanvien/view/' . $item['CongviecComment']['nguoibinhluan_id']) ?></strong></td>
        <td>
        
            <div class="bbcode_container">
                <div class="bbcode_quote">
                    <div class="bbcode_quote_container"></div>
                    <div class="bbcode_postedby">
                        <div style="float:left"><img src="/img/icons/quote_icon.png" /></div> 
                        <div style="float:right; font-weight:10px; color:#666666; padding-right:10px"><?php echo $this->Time->format('H:i:s d-m-Y', $item['CongviecComment']['ngay_binhluan']) ?></div>
                        <div style="clear:both"></div>
                    </div>
                    <div class="message">
                        <?php 
                            echo $item['CongviecComment']['noi_dung']
                        ?>
                    </div>
                </div>
            </div>
        
        </td>
    </tr>
<?php
	endforeach;
	endif;
?>
</table>
<script>
	$(document).ready(function(){
		$('#add-comment').show();
	});
</script>