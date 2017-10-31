<div id="table-content" class="formPanel">

<div style="height:300px; overflow:auto" class="data table-content">

	<table>

        <thead>

        <tr>

            <th width="1px">STT</th>

            <th width="300px">Họ và tên</th>

            <th width="200px">Phân công</th>
            <th width="200px">Mức độ hoàn thành</th>
            <th width="200px">Ngày cập nhật</th>
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

				echo $item['NguoiNhanviec']['full_name'];

			?>            </td>

            <td><span style="color:#FF0000; text-align:center"><?php if($item['Congviec']['parent_id']==''){echo 'Chịu trách nhiệm chính'; } ?></span></td>
            <td align="center"> <?php

				echo ($item['Congviec']['mucdo_hoanthanh']*10).'%';

			?> </td>
            <td align="center">

            <?php
			if(!empty($item['Congviec']['ngay_capnhat'])){
				$ngaycapnhat = new DateTime($item['Congviec']['ngay_capnhat']);
				echo date_format($ngaycapnhat,"d-m-Y");
			}
				
				

			?>            </td>
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
 <div>
       
        <div style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>
        <div style="float:left; margin-right:5px">Người chịu trách nhiệm chính</div>
       
    </div>

<script>

	$(document).ready(function(){

		$('.data tr:odd').addClass("alt-row");

	});

</script>