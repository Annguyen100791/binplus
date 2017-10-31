var Report = {
	signin:	function(){
		$('#tu_ngay').datepicker({dateFormat: "dd-mm-yy"});
		$('#den_ngay').datepicker({dateFormat: "dd-mm-yy"});
		// validate

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

		$('#btn-export-excel').click(function(e){
			e.preventDefault();
			$('#form-report').attr('action', BIN.baseURL + 'report/signin_excel');
			$('#form-report').submit();
		});	
		
		$('#btn-report').click(function(e){
			e.preventDefault();
			$('#form-report').attr('action', BIN.baseURL + 'report/signin');
			$('#report-content').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		  '/report/signin',
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
		
	},
	signin_nhanvien:	function(){
		$('#tu_ngay').datepicker({dateFormat: "dd-mm-yy"});
		$('#den_ngay').datepicker({dateFormat: "dd-mm-yy"});
		// validate

		$('#form-report-nhanvien').validate({
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

		$('#btn-export-excel-nv').click(function(e){
			e.preventDefault();
			$('#form-report-nhanvien').attr('action', BIN.baseURL + 'report/signin_nhanvien_excel');
			$('#form-report-nhanvien').submit();
		});	
		
		$('#btn-report-nhanvien').click(function(e){
			e.preventDefault();
			$('#form-report-nhanvien').attr('action', BIN.baseURL + 'report/signin_nhanvien');
			$('#report-content-nhanvien').html('<img src="/img/circle_ball.gif">');
			$.ajax({
				type:		'POST',
				url:		  '/report/signin_nhanvien',
				data:		{tu_ngay: $('#tu_ngay').val(), den_ngay: $('#den_ngay').val()},
				success:	function(result)
				{
					$('#report-content-nhanvien').html(result);
				},
				error:		function(result)
				{
					alert('Error');
				}
			});	
			return false;
		});	
		
	}
	
};


$(document).ready(function(){
	BIN.executeFunctionByName(Report, params.action, null);
});