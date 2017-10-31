<table>
    <thead>
    <tr>
        <th width="20%">Họ tên Nhân viên</th>
        <th width="15%">Ngày bắt đầu</th>
        <th width="15%">Ngày kết thúc</th>
        <th width="40%">Nội dung công việc</th>
        <th width="80px">Action</th>
    </tr>
    </thead>
<tbody>
<?php 
	if(!empty($ds)): 
	foreach($ds as $item):
?>
	<tr>
    	<td><?php echo $item['full_name'] ?></td>
        <td><?php echo $item['ngay_batdau'] ?></td>
        <td><?php echo $item['ngay_ketthuc'] ?></td>
        <td><?php echo $item['noi_dung'] ?></td>
        <td></td>
    </tr>
<?php 
	endforeach;
	endif;
?>
</tbody>
</table>