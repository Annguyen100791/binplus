<?php
	if(!empty($data)):
?>
<div style="padding-top:20px" class="data table-content">
	<table id="nhanvien-data">
        <thead>
        <tr>
            <th width="30px">STT</th>
            <th width="60%">Họ tên Nhân viên</th>
            <th width="120px" style="text-align:center!important">Số lần truy cập</th>
            <th width="120px" style="text-align:center!important">Lần sau cùng</th>
        </tr>
        </thead>
        <tbody>
        <?php
			$ten_phong = '';
			$stt = 1;
			foreach($data as $item):
				
				if(empty($ten_phong) || $ten_phong != $item['A']['ten_phong'])
				{
					$ten_phong = $item['A']['ten_phong'];
					$stt = 1;
					echo '<tr class="alt-row">';
					echo '<td colspan="4">';
					echo '<b>' . $ten_phong . '</b>';
					echo '</td>';
					echo '</tr>';
				}
				?>
                <tr>
                	<td style="text-align:center!important"><?php echo $stt++; ?></td>
                    <td><?php echo $item[0]['ho_ten']?></td>
                    <td style="text-align:center!important; font-weight: bold"><?php echo $this->Number->format($item[0]['total'])?></td>
                    <td style="text-align:center!important">
                    <?php
						if(!empty($item[0]['last_signin_date']))
							echo $this->Time->format('d-m-Y H:i:s', $item[0]['last_signin_date']);
					?>
                    </td>
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