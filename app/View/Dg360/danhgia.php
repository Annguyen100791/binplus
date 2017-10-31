<div id="page-heading"><h1>Đánh giá 360</h1></div>
<table id="content-table" width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody><tr>
	<th rowspan="3" class="sized"><img src="/img/shared/side_shadowleft.jpg" alt="" height="300" width="20"></th>
	<th class="topleft"></th>
	<td id="tbl-border-top">&nbsp;</td>
	<th class="topright"></th>
	<th rowspan="3" class="sized"><img src="/img/shared/side_shadowright.jpg" alt="" height="300" width="20"></th>
</tr>
<tr>
	<td id="tbl-border-left"></td>
	<td id="tbl-360">
	<!--  start content-table-inner -->
	<div id="content-table-inner">

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr valign="top">
			<td>
				<a id="export-excel" href="#" class="button">Xuất Excel</a>
			</td>
		</tr>
	<tr>
	<td style="padding-left:20px">
    <table id="answer-list">
    </table>
		<div id="jqGridPager"></div>

		<script type="text/javascript">
	         $(document).ready(function ($) {
	             $("#answer-list").jqGrid({
	                 colModel: [
	                     { label: 'ID', name: 'ID', key: true, width: 75, hidden: true },
	                     { label: 'Câu hỏi', name: 'question', width: 230, search: true },
	                     //{ label: 'Người đánh giá', name: 'appreciate', width: 150 },
											 {label: 'Phòng ban', name: 'group', width: 230, search: true },
	                     { label: 'Người được đánh giá', name: 'appreciated', width: 0 },
	                     { label:'Ngày đánh giá', name: 'date', width: 150 },
											 { label:'Điểm đánh giá', name: 'mark', width: 150 }
	                 ],
									 grouping:true,
									 groupingView: {
										 groupField: ['group','appreciated', 'date'],
										 groupOrder: ['asc','asc','desc'],
										 groupColumnShow: [true,false,true],
										 groupCollapse: true
									 },
	 							 	 viewrecords: true,
	                 width: 1250,
	                 height: 500,
	                 rowNum: -1,
									 datatype : "local",

									 caption: "Bảng đánh giá",

	             });

							 function fetchData(){
								 var gridData = [];
								 $("#answer-list")[0].grid.beginReq();
								 $.ajax({
									 url: 'http://203.210.240.102:5013/api/answers/<?php echo $username; ?>',
									 method: "GET",
									 dataType: "json",
									 success: function(datatext){
										 var data = JSON.parse(datatext);
										 for (var i = 0; i < data.length; i++) {
										 	var item = data[i];
											gridData.push(item);
										 }
										 $("#answer-list").jqGrid('setGridParam', { data: gridData});
										 $("#answer-list")[0].grid.endReq();
										 $("#answer-list").trigger('reloadGrid');
									 }
								 });
							 }
							 fetchData();

							 $("#export-excel").on("click",function(e){
								 e.preventDefault();
								 var data = $("#answer-list").jqGrid('getGridParam','data');
								 if(data.length == 0)
								 {
								 	alert("Không có dữ liệu");
									 return;
								 }
								 else {
								 	$.post("/dg360/export/",{data: JSON.stringify(data)},function(response){
										// generate file
										//console.log(response);
										window.open("/dg360/download?file=" + encodeURIComponent(response),"_self");
									});
								 }
							 });
					 });


	    </script>

</td>
</tr>
<tr>
<td><img src="/img/shared/blank.gif" alt="blank" height="1" width="695"></td>
<td></td>
</tr>
</tbody></table>

<div class="clear"></div>


</div>
<!--  end content-table-inner  -->
</td>
<td id="tbl-border-right"></td>
</tr>
<tr>
	<th class="sized bottomleft"></th>
	<td id="tbl-border-bottom">&nbsp;</td>
	<th class="sized bottomright"></th>
</tr>
</tbody></table>
