// JavaScript Document

function	refreshNotice()
	{
		BIN.doUpdate($('<a href="pages/notice" data-target="related-act-inner"></a>'));
	}

var Pages = {
	home:	function(){
		$('#btn-refresh-notice').click(function(){
			refreshNotice();
		});
		BIN.doUpdate($('<a href="pages/chinhsach" data-target="news-chinhsach"></a>'));
		BIN.doUpdate($('<a href="pages/thongtin" data-target="news-content"></a>'));
		refreshNotice();
		
		setInterval('refreshNotice()', 300000);	// 5 minutes
	}
};


$(document).ready(function(){
	BIN.executeFunctionByName(Pages, params.action, null);
});