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
    	<tr <?php if($item['loai_nguoinhan']==2){echo 'style="background-color:#d5ffce"';}?>>
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
	
	<?php	
		foreach($bcc as $itbcc):		
			if($itbcc['nguoinhan_id']==$this->Session->read('Auth.User.nhanvien_id') or $data['Tinnhan']['nguoigui_id']==$this->Session->read('Auth.User.nhanvien_id')){
	?>
    	<tr style="background-color:#CCFFFF">
    		<td style="text-align:right!important"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo $this->Html->link($itbcc['Nguoinhan']['full_name'], '/tinnhan/compose/sendto:' . $itbcc['nguoinhan_id'], array('title' => 'Click để gửi tin nhắn cho nhân viên này'));
			?>
            </td>
            <td>
            <?php
				if(!empty($item['ngay_nhan']))
					echo $this->Html->image('icons/tick_circle.png', array('title' => $this->Time->format('H:i:s d-m-Y', $itbcc['ngay_nhan'])));
			?>
            </td>
        </tr>
    <?php
			}
		endforeach;	
	?>
	
	</tbody>
    </table>
    </div>
</div>
 <div style="padding-left:20px; padding-bottom:20px"><span style="font-weight:bold; text-decoration:underline;">Chú thích</span></div>
    <div style="padding-left:20px; padding-bottom:20px">
        <div style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Người nhận Cc</div>
        <div style="width:30px; height:15px; background-color:#CCFFFF; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Người nhận Bcc</div>
    </div>
<div style="text-align:right!important" class="dialog-footer">
    <button class="button btn-close" type="button">Đóng</button>
</div>
<script>
	$(document).ready(function(){
		$('.data tr:odd').addClass("alt-row");
	});
</script>