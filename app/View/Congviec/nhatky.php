<?php
    echo $this->Session->flash();
    if(!empty($ds)):
    
?>
<div style="padding-top:20px" class="data table-content">
    <table>
        <thead>
        <tr>
        	<th width="120px">Thời điểm</th>
            <th width="120px">Người cập nhật</th>
            <th width="120px">Mức độ hoàn thành</th>
            <th width="60%">Ghi chú</th>
        </tr>
        </thead>
    <tbody>
<?php	
	 foreach($ds as $item):
?>
	<tr>
        <td align="center"><?php echo $this->Time->format('d-m-Y H:i:s', $item['CongviecNhatky']['ngay_nhap']) ?></td>
        <td>
        <?php
            echo $item['NguoiCapnhat']['full_name'];
        ?>
        </td>
        <td>
        <?php
            echo $item['CongviecNhatky']['capnhat_mucdo']*10 . '%';
        ?>
        </td>
        <td>
        <?php
           echo nl2br($item['CongviecNhatky']['ghi_chu']);
        ?>
        </td>
    </tr>
        <?php
    endforeach;
    
?>
    </tbody>
    </table>
</div>    
<script>
	$(document).ready(function(){
		BIN.doListing();
	});
</script>
<?php
    endif;
?>
</div>
