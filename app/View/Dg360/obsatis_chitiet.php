<?php
	if(!empty($data)):
?>
<div style="padding-top:20px" class="data table-content">
	<table id="nhanvien-data">
        <thead>
        <tr>
            <th width="40%">Tổng số ý kiến</th>
            <th width="120px" style="text-align:center!important">Số ý kiến hài lòng</th>
            <th width="120px" style="text-align:center!important">Tỷ lệ hài lòng (%)</th>
            <th width="120px" style="text-align:center!important">Tỷ lệ không hài lòng (%)</th>
        </tr>
        </thead>
        <tbody>
        <?php
				/*if(empty($ten_phong) || $ten_phong != $item['A']['ten_phong'])
				{
					$ten_phong = $item['A']['ten_phong'];
					$stt = 1;
					echo '<tr class="alt-row">';
					echo '<td colspan="4">';
					echo '<b>' . $ten_phong . '</b>';
					echo '</td>';
					echo '</tr>';
				}*/
				?>
                <tr>
                    
                    <td style="text-align:left!important;font-weight: bold"><?php echo $this->Number->format($data[0][0]['total'])?></td>
                    <td style="text-align:center!important"><?php echo $this->Number->format($data[0][0]['total_satis'])?></td>
                    <td style="text-align:center!important">
                    <?php echo $this->Number->format($data[0][0]['ty_le'],1)?>
                    </td>
                    <td style="text-align:center!important">
                    <?php if ($this->Number->format($data[0][0]['ty_le'],1) > 0)
							echo 100 - $this->Number->format($data[0][0]['ty_le'],1);
						  else 
						  	echo 0 ;
					?>
                    </td>
                </tr>
                <?php
		?>	
        </tbody>
    </table>
</div>

<?php	
	endif;
?>