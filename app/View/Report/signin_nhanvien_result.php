<?php
	if(!empty($data)):
?>
<div style="padding-top:20px" class="data table-content">
	<table id="nhanvien-data">
        <thead>
        <tr>
           <th width="1px">STT</th>
           <th width="120px">Ngày giờ truy cập </th>
        </tr>
        </thead>
        <tbody>
        <?php
			$ten_phong = '';
			$stt = 1;
			foreach($data as $item):
		?>
            <tr>
                <td style="text-align:center"><?php echo $stt++ ?></td>
                <td><?php echo $this->Time->format('d-m-Y H:i:s', $item['signin_histories']['signin_date']) ?></td>
               </tr>
                <?php
			endforeach;
		?>	
        </tbody>
    </table>
</div>

<?php	
	endif;
?>