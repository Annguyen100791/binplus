var Nhomquyen = {
	index:	function(){
	},
	add:	function(){
		$('#form-nhomquyen-add').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nhomquyen][ten_nhomquyen]'	:	'required',
				'data[Nhomquyen][quyen]'			:	'required'	
			},
			messages:{
				'data[Nhomquyen][ten_nhomquyen]'	:	'Vui lòng nhập vào tên Nhóm Quyền hạn',
				'data[Nhomquyen][quyen]'			:	'Vui lòng chọn Quyền hạn trong danh sách'
			}
		});	
		
		$('.table-content tr:odd').addClass("alt-row"); // Add class "alt-row" to even table rows
		$('#ten_nhomquyen').focus();
	},
	
	edit:	function(){
		$('#form-nhomquyen-edit').validate({
			errorElement: 	"em",
			errorClass:		"warning",
			rules:{
				'data[Nhomquyen][ten_nhomquyen]'	:	'required',
				'data[Nhomquyen][quyen]'			:	'required'	
			},
			messages:{
				'data[Nhomquyen][ten_nhomquyen]'	:	'Vui lòng nhập vào tên Nhóm Quyền hạn',
				'data[Nhomquyen][quyen]'			:	'Vui lòng chọn Quyền hạn trong danh sách'
			}
		});	
		
		$('.table-content tr:odd').addClass("alt-row"); // Add class "alt-row" to even table rows
		$('#ten_nhomquyen').focus();
	}
};

$(document).ready(function(){
	BIN.executeFunctionByName(Nhomquyen, params.action, null);
});