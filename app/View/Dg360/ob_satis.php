<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h2>Báo cáo độ hài lòng</h2></div>
    <div style="clear:both"></div>
</div>
<!-- end page-heading -->
    
<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
<tr>
    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
    <th class="topleft"></th>
    <td id="tbl-border-top">&nbsp;</td>
    <th class="topright"></th>
    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
</tr>
<tr>
    <td id="tbl-border-left"></td>
    <td>
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner">
		<div>
    	<form id="form-report" method="post" action="/dg360/ob_satis">
        	<table width="100%">
                <tr style="height:37px">
                	<td style="text-align:center;  padding-right:10px">
                    	Từ ngày&nbsp;
                        <input name="data[tu_ngay]" id="tu_ngay" class="text-input" style="width:100px" type="text" value="<?php echo $this->Time->format('d-m-Y', time()) ?>"/>
						&nbsp;đến ngày&nbsp;
                        <input name="data[den_ngay]" id="den_ngay" class="text-input" style="width:100px" type="text"  value="<?php echo $this->Time->format('d-m-Y', time()) ?>"/>
                        &nbsp;
                        <input class="button" type="button" value="Thống kê" id="btn-report" />
                        <button class="button" type="button" id="btn-export-excel">Xuất Excel</button>
                    </td>
                </tr>
            </table>
        </form>	
    </div>
    
    <div id="report-content">
	</div>
	<!--  end content-table-inner ............................................END  -->
    </td>
    <td id="tbl-border-right"></td>
</tr>
<tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
</tr>
</table>
<script>
	$('#tu_ngay').datepicker({dateFormat: "dd-mm-yy"});
	$('#den_ngay').datepicker({dateFormat: "dd-mm-yy"});
	$('#form-report').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[tu_ngay]':	'required',
				'data[den_ngay]':	'required',
			},
			messages:{
				'data[tu_ngay]'	:	'',
				'data[den_ngay]'	: ''
			}
		});
	$('#btn-report').click(function(e){
			e.preventDefault();
			$('#form-report').attr('action', BIN.baseURL + 'dg360/ob_satis');
			$('#report-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		  '/dg360/ob_satis',
				data:		{tu_ngay: $('#tu_ngay').val(), den_ngay: $('#den_ngay').val()},
				success:	function(result)
				{
					$('#report-content').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});	
	$('#btn-export-excel').click(function(e){
			e.preventDefault();
			$('#form-report').attr('action', BIN.baseURL + 'dg360/satis_excel');
			$('#form-report').submit();
		});
</script>
