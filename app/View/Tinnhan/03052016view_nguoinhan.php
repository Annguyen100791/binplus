<div id="table-content" class="formPanel">
<div style="height:300px; overflow:auto" class="data table-content">
	<table>
        <thead>
        <tr>
            <th width="1px">STT</th>
            <th width="400px">Họ và tên</th>
            <th width="1px"></th>
        </tr>
        </thead>
    <tbody>
    <?php
		$stt = 1;
		foreach($nguoinhan as $item):
	?>
    	<tr>
    		<td style="text-align:right!important"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($item['Nguoinhan']['full_name'], '/tinnhan/compose/sendto:' . $item['nguoinhan_id'], array('title' => 'Click để gửi tin nhắn cho nhân viên này'));
			?>
            </td>
            <td>
            <?php
				if(!empty($item['ngay_nhan']))
					echo $this->Html->image('icons/tick_circle.png', array('title' => $this->Time->format('H:i:s d-m-Y', $item['ngay_nhan'])));
			?>
            </td>
        </tr>
    <?php
		endforeach;
	?>
	</tbody>
    </table>
    </div>
</div>
<div style="text-align:right!important" class="dialog-footer">
    <button class="button btn-close" type="button">Đóng</button>
</div>
<script>
	$(document).ready(function(){
		$('.data tr:odd').addClass("alt-row");
	});
</script>