<?php
	if(!empty($data)):
?>
<div style="padding-top:20px" class="data table-content">
	<table id="nhanvien-data">
        <thead>
        <tr>
            <th width="50%">Lý do không hài lòng</th>
            <th width="120px" style="text-align:center!important">Số lượng</th>
            <th width="120px" style="text-align:center!important">Tỷ lệ(%)</th>
        </tr>
        </thead>
        <tbody>
        <?php
				foreach($data as $item):
				?>
                <tr>
                    <td><?php echo $item['B']['TEN_LYDO']?></td>
                    <td style="text-align:center!important; font-weight: bold"><?php echo $item['B']['SO_LUONG']?></td>
                    <td style="text-align:center!important">
                    <?php echo $this->Number->format($item[0]['TYLE'],1)?>
                    </td>
                </tr>
                <?php
				endforeach;
		?>	
        </tbody>
        <thead>
        <tr>
            <th width="50%">Tổng</th>
            <th width="120px" style="text-align:center!important"> <?php if(!empty($total)){echo $total['0']['0']['TOTAL'];}?> </th>
            <th width="120px" style="text-align:center!important">100%</th>
        </tr>
        </thead>
    </table>
</div>

<?php	
	endif;
?>