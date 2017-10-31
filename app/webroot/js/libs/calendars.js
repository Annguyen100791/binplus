// JavaScript Document

var Calendars = {
	lamviec:	function(){
		var phamvi = $('#pham_vi').val();
		//alert(phamvi);
		var event_source;
		var ds_phamvi = [];
		$("#pham_vi option").each(function()
		{
			// add $(this).val() to your list
			if($(this).val() != '')
			ds_phamvi.push($(this).val());
		});
		//alert(ds_phamvi);
		if($.inArray('0', ds_phamvi) >= 0)
		{
			event_source = [
								{
									url: BIN.baseURL + 'calendars/lamviec_ds/0',	// toàn viễn thông
									type: 'POST',
									error: function() {
										
									},
									color: '#aed7ff',   // a non-ajax option
									textColor: 'black' // a non-ajax option
								},
								{
									url: BIN.baseURL + 'calendars/lamviec_ds/1',	// phòng ban thuộc Viễn thông
									type: 'POST',
									error: function() {
										
									},
									color: '#ffff99',   // a non-ajax option
									textColor: 'black' // a non-ajax option
								}
								,
								{
									url: BIN.baseURL + 'calendars/lamviec_ds/2',	// trung tâm
									type: 'POST',
									error: function() {
										
									},
									color: '#ffdbd9',   // a non-ajax option
									textColor: 'black' // a non-ajax option
								},
								{
									url: BIN.baseURL + 'calendars/lamviec_ds/3',	// phòng ban thuộc Trung tâm
									type: 'POST',
									error: function() {
										
									},
									color: '#ffaf99',   // a non-ajax option
									textColor: 'black' // a non-ajax option
								}
								];	
			
			
			if(phamvi == '0')								
				event_source.splice(1);
			else if(phamvi == '1')
				{
					var tmp;
					tmp = event_source.splice(0);	
					//alert(tmp);
					event_source = tmp.splice(1,1);
				}
			else if(phamvi == '2')
					{
						var tmp;
						tmp = event_source.splice(0,2);
						//alert(tmp);
						event_source.splice(1, 1);
					}
				
			else if(phamvi == '3')
				event_source.splice(0, 3);
		}else
		{
			event_source = [
								{
									url: BIN.baseURL + 'calendars/lamviec_ds/0',	// toàn viễn thông
									type: 'POST',
									error: function() {
										
									},
									color: '#aed7ff',   // a non-ajax option
									textColor: 'black' // a non-ajax option
								}
								];	
		}
		
		$('#form-phamvi').submit(function(){
			location = BIN.baseURL + 'calendars/lamviec/' + $('#pham_vi').val();
			return false;
		});					
		
		var opts = {
				container: '#calendar', 
				eventSources: event_source
				};
		BIN.init_calendar(opts);
	},
	birthday:	function(){
		var event_source = [{
						url: BIN.baseURL + 'calendars/birthday_list',
						type: 'POST',
						data: {
						},
						error: function() {
							alert('Đã phát sinh lỗi!');
						},
						color: '#FFEBD7',   // a non-ajax option
						textColor: 'black' // a non-ajax option
					}];							   
		BIN.init_calendar({container: '#calendar', eventSources: event_source});
	}
};


$(document).ready(function(){
	BIN.executeFunctionByName(Calendars, params.action, null);
});