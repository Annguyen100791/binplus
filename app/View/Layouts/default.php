<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo $title_for_layout; ?></title>



<?php



	echo $this->Html->css(array(

			'reset',

			'screen',

			'invalid',

			'validate.form',

			'/js/libs/qtip/jquery.qtip.min',

			'jquery-ui.min',

			'/js/libs/select2/select2',

			'custom',

			'ui.jqgrid.css',

	));



	echo $this->Html->script(

		array(

			  'libs/jquery/jquery.min',

			  'libs/jquery/jquery.blockUI',

			  'libs/jquery/jquery.form',

			  'libs/jquery/jquery.validate.min',

			  'libs/jquery/bbq.jquery',

			  'libs/qtip/jquery.qtip',

			  'libs/qtip/imagesloaded.pkg.min',

			  'libs/jquery/jquery-ui',

			  'libs/jquery/jquery.ui.datepicker-vi',

			  'libs/select2/select2.min',

			  'libs/select2/select2_locale_vi',

		  	  'libs/jquery/jquery.qtip.base',

		  	  'jquery.jqGrid.min.js',

			  )

	);



	echo $this->fetch('meta');

	echo $this->fetch('css');

	echo $this->fetch('script');



	echo $this->Layout->js_init();

?>

<!--[if IE]>

<link rel="stylesheet" media="all" type="text/css" href="/css/pro_dropline_ie.css" />

<![endif]-->

<style>

.qtip-titlebar{

    overflow: visible;

}

</style>

</head>

<body>

<!-- Start: page-top-outer -->

<div id="page-top-outer">



<!-- Start: page-top -->

<div id="page-top">



	<!-- start logo -->

	<div id="logo">

        <?php echo Configure::read('Site.company_name') ?>

    </div>

	<!-- end logo -->



</div>

<!-- End: page-top -->



</div>

<!-- End: page-top-outer -->



<div class="clear">&nbsp;</div>



<!--  start nav-outer-repeat................................................................................................. START -->

<div class="nav-outer-repeat">

<!--  start nav-outer -->

<div class="nav-outer">



		<!-- start nav-right -->

		<div id="nav-right">



			<div class="nav-divider">&nbsp;</div>

			<div class="showhide-account"><span id="mnu-account" title="Click để xem chi tiết"><?php echo $this->Session->read('Auth.User.username') ?></span></div>

			<div class="clear">&nbsp;</div>



			<!--  start account-content -->

			<div class="account-content">

			<div class="account-drop-inner">

				<a href="/users/changepass" id="acc-settings" data-mode="ajax" data-action="dialog" data-width="400px" title="Đổi mật khẩu">Đổi mật khẩu</a>

				<div class="clear">&nbsp;</div>

				<div class="acc-line">&nbsp;</div>

				<a href="/nhanvien/view" id="acc-details">Thông tin cá nhân</a>

                <div class="clear">&nbsp;</div>

				<div class="acc-line">&nbsp;</div>

				<a href="/groups" id="acc-project">Nhóm làm việc</a>

				<div class="clear">&nbsp;</div>

				<div class="acc-line">&nbsp;</div>

				<a href="/tinnhan/" id="acc-inbox">Tin nhắn</a>

				<div class="clear">&nbsp;</div>

				<div class="acc-line">&nbsp;</div>

				<a href="<?php echo $this->Html->webroot('/users/logout') ?>" id="acc-logout">Logout</a>

                <div class="clear">&nbsp;</div>

			</div>

			</div>

			<!--  end account-content -->



		</div>

		<!-- end nav-right -->





		<!--  start nav -->

		<div class="nav">

			<?php

				echo $this->element('main_menu', array(), array('cache' => array('config' => 'views', 'key' => $this->Session->read('Auth.User.nhanvien_id'))));

				//echo $this->element('main_menu');

			?>

		</div>

		<!--  start nav -->



</div>

<div class="clear"></div>

<!--  start nav-outer -->

</div>

<!--  start nav-outer-repeat................................................... END -->



 <div class="clear"></div>



<!-- start content-outer ........................................................................................................................START -->

<div id="content-outer">

<!-- start content -->

<div id="content">



	<?php

		echo $content_for_layout;

	?>



</div>

<!--  end content -->

<div class="clear">&nbsp;</div>

</div>

<!--  end content-outer........................................................END -->



<div class="clear">&nbsp;</div>



<!-- start footer -->

<div id="footer">

	<!--  start footer-left -->

	<div id="footer-left">



	Bản quyền BIN Plus 2012 thuộc Trung tâm CNTT - Viễn thông Đà Nẵng.</div>

	<!--  end footer-left -->

	<div class="clear">&nbsp;</div>

</div>

<!-- end footer -->
<!-- <script type="text/javascript">

window.addEventListener('load', function(){
	if(window.location.href.indexOf('dg360') == -1) {
		document.cookie = 'CakeCookie[360cookie]' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/;';
		document.cookie = 'CakeCookie[return_url]' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/;';
	}
});
Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}
if(jQuery != undefined) {
  jQuery(document).ready(function($){
		var alertPopup = function(alert){
			if(window.location.href.indexOf('dg360') != -1) {
				return;
			}

			var popupcontainer = document.getElementById('popupcontainer');
			var popupinner = {};
			var popupdiv = {};
			var popupContent = {};
			if(popupcontainer == null) {

				var popupcontainer = document.createElement("DIV");
				popupcontainer.className = "popup-container dimground";
				popupcontainer.id = "popupcontainer";
				document.body.appendChild(popupcontainer);

				var popupTitle = document.createElement("H2");
				popupTitle.innerHTML = "Thông báo";

				popupinner = document.createElement("DIV");
				popupinner.className = "popup-inner";
				popupinner.id = 'popup-inner';
				popupinner.appendChild(popupTitle);

				popupdiv = document.createElement("DIV");
				popupdiv.className = "alert-popup popup";
				popupdiv.id = "alert-popup";
				popupdiv.appendChild(popupinner);
				popupcontainer.appendChild(popupdiv);
				popupContent = document.createElement("DIV");
				popupContent.id = "popup-content";
				popupinner.appendChild(popupContent);
			}
			else {
				popupContent = document.getElementById('popup-content');

				popupinner = document.getElementById('popup-inner');
				popupdiv = document.getElementById('alert-popup');
			}
			//360
			if(alert.type == '360') {
				var popupP = document.createElement("P");
				popupP.innerHTML = 'Bạn còn <strong>' + alert.num + '</strong> người chưa đánh giá. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				var popupLink = document.createElement("A");
				popupLink.setAttribute("href","/dg360/index");
				popupLink.innerHTML = "Đánh giá 360";
				popupLink.className = "button";
				popupContent.insertBefore(popupP,popupContent.firstChild);
				popupContent.appendChild(popupLink);
			}
			//end 360
			//ob
			else if (alert.type == 'ob_nv') {
				var popupOBP = document.createElement("P");
				popupOBP.innerHTML = 'Bạn chưa đánh giá OB. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				var popupOBLink = document.createElement("A");
				popupOBLink.setAttribute("href","/dg360/ob_ttdh");
				popupOBLink.innerHTML = "Đánh giá OB";
				popupOBLink.className = "button";
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
				popupContent.appendChild(popupOBLink);
			}
			else if (alert.type == 'ob_tt') {
				var popupOBP = document.createElement("P");
				var innerHTML = "";
				innerHTML = 'Còn <span class="tooltip-holder"><strong style="color:#008e01;">' + alert.num + '</strong>'
				if(typeof alert.nvs != 'undefined' && alert.nvs.length > 0)
				{
					innerHTML += '<span class="tooltips"><span class="innertooltips">';
					for (var i = 0; i < alert.nvs.length; i++) {
						innerHTML +=  alert.nvs[i][0].User.fullname + '</br>';
					}
					innerHTML += '</span></span>';
				}

				innerHTML += '</span> người trong Tổ bạn chưa đánh giá OB. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				popupOBP.innerHTML = innerHTML;
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
			}
			else if (alert.type == 'ob_gd') {
				var popupOBP = document.createElement("P");
				var innerHTML = "";
				innerHTML = 'Còn <span class="tooltip-holder"><strong style="color:#008e01;">' + alert.num + '</strong>'
				if(typeof alert.nvs != 'undefined' && alert.nvs.length > 0)
				{
					innerHTML += '<span class="tooltips"><span class="innertooltips">';
					for (var i = 0; i < alert.nvs.length; i++) {
						innerHTML +=  alert.nvs[i].A.ten_phong + '</br>';
					}
					innerHTML += '</span></span>';
				}

				innerHTML += '</span> tổ thuộc Trung tâm bạn chưa đánh giá OB. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				popupOBP.innerHTML = innerHTML;
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
			}

			//OB
			if(alert.day < alert.lastday && !document.getElementById('tat-thong-bao')) {
				var popupCancel = document.createElement("A");
				popupCancel.setAttribute("href","#");
				popupCancel.className = "button";
				popupCancel.innerHTML = "Bỏ qua";
				popupCancel.id = "tat-thong-bao";
				popupContent.appendChild(popupCancel);
			}
			document.body.style.overflow = "hidden";
		};
    $.ajax({
      url: 'http://203.210.240.102:5013/api/survey/alert/<?php echo $this->session->read('Auth.User.username'); ?>',
      method: "GET",
      dataType: "json",
      success: function(data){
        var alert = JSON.parse(data);
				if(alert.result == 0)
				{
					//alertPopup(alertNum);
					return;
				}
				else if (document.cookie.indexOf('CakeCookie[popupexpire]=1') == -1) {
						alertPopup({num:alert.result, type: '360', day: alert.day, lastday: 7});

				}
				else {
					return;
				}
      }
    });

		$.get('/dg360/alertOB',function(data,status,xhr) {
			var alertData = JSON.parse(data);
			if(document.cookie.indexOf('CakeCookie[popupobexpire]=1') != -1) {
				return;
			}
			if(alertData.nv.num != 0) {
				alertPopup({type: "ob_nv", num: alertData.nv.num, nvs: alertData.nv.ds, day: alertData.day, lastday: alertData.lastday});
			}
			if(alertData.tt.num != 0) {
				alertPopup({type: "ob_tt",num: alertData.tt.num, nvs: alertData.tt.ds, day: alertData.day, lastday: alertData.lastday});
			}
			if(alertData.gd.num != 0) {
				alertPopup({type: "ob_gd",num: alertData.gd.num, nvs: alertData.gd.ds, day: alertData.day, lastday: alertData.lastday});
			}

			//Candidate tooltips


		});

	jQuery(document).on("click", "#tat-thong-bao",function(e){
		e.preventDefault();
		var cd = new Date();
		cd.setHours(6);
		cd.setMinutes(0);
		cd.setSeconds(0);
		cd.setMilliseconds(0);
		document.cookie = 'CakeCookie[popupobexpire]=1; expires=' + cd.addDays(1).toUTCString() + ';path=/';
		document.cookie = 'CakeCookie[popupexpire]=1; expires=' + cd.addDays(1).toUTCString() + ';path=/';
		document.body.style.overflow = "auto";
		jQuery("#popupcontainer").remove();
	});
	});
}
</script>  -->


<script type="text/javascript">

window.addEventListener('load', function(){
	if(window.location.href.indexOf('dg360') == -1) {
		document.cookie = 'CakeCookie[360cookie]' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/;';
		document.cookie = 'CakeCookie[return_url]' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/;';
	}
});
Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}
if(jQuery != undefined) {
  jQuery(document).ready(function($){
		var alertPopup = function(alert){
			if(window.location.href.indexOf('dg360') != -1) {
				return;
			}

			var popupcontainer = document.getElementById('popupcontainer');
			var popupinner = {};
			var popupdiv = {};
			var popupContent = {};
			if(popupcontainer == null) {

				var popupcontainer = document.createElement("DIV");
				popupcontainer.className = "popup-container dimground";
				popupcontainer.id = "popupcontainer";
				document.body.appendChild(popupcontainer);

				var popupTitle = document.createElement("H2");
				popupTitle.innerHTML = "Thông báo";

				popupinner = document.createElement("DIV");
				popupinner.className = "popup-inner";
				popupinner.id = 'popup-inner';
				popupinner.appendChild(popupTitle);

				popupdiv = document.createElement("DIV");
				popupdiv.className = "alert-popup popup";
				popupdiv.id = "alert-popup";
				popupdiv.appendChild(popupinner);
				popupcontainer.appendChild(popupdiv);
				popupContent = document.createElement("DIV");
				popupContent.id = "popup-content";
				popupinner.appendChild(popupContent);
			}
			else {
				popupContent = document.getElementById('popup-content');

				popupinner = document.getElementById('popup-inner');
				popupdiv = document.getElementById('alert-popup');
			}
			//360
			if(alert.type == '360') {
				var popupP = document.createElement("P");
				popupP.innerHTML = 'Bạn còn <strong>' + alert.num + '</strong> người chưa đánh giá. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				var popupLink = document.createElement("A");
				popupLink.setAttribute("href","/dg360/index");
				popupLink.innerHTML = "Đánh giá 360";
				popupLink.className = "button";
				popupContent.insertBefore(popupP,popupContent.firstChild);
				popupContent.appendChild(popupLink);
			}
			//end 360
			//ob
			else if (alert.type == 'ob_nv') {
				var popupOBP = document.createElement("P");
				popupOBP.innerHTML = 'Bạn chưa đánh giá OB. Vui lòng click vào link bên dưới để đánh giá trước khi tiếp tục dùng BIN.';
				var popupOBLink = document.createElement("A");
				popupOBLink.setAttribute("href","/dg360/ob_ttdh");
				popupOBLink.innerHTML = "Đánh giá OB";
				popupOBLink.className = "button";
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
				popupContent.appendChild(popupOBLink);
			}
			else if (alert.type == 'ob_tt') {
				var popupOBP = document.createElement("P");
				var innerHTML = "";
				innerHTML = 'Còn <span class="tooltip-holder"><strong style="color:#008e01;">' + alert.num + '</strong>'
				if(typeof alert.nvs != 'undefined' && alert.nvs.length > 0)
				{
					innerHTML += '<span class="tooltips"><span class="innertooltips">';
					for (var i = 0; i < alert.nvs.length; i++) {
						innerHTML +=  alert.nvs[i][0].User.fullname + '</br>';
					}
					innerHTML += '</span></span>';
				}

				innerHTML += '</span> người trong Tổ bạn chưa đánh giá OB. Vui lòng đôn đốc, nhắc nhở cán bộ chưa đánh giá.';
				popupOBP.innerHTML = innerHTML;
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
			}
			else if (alert.type == 'ob_gd') {
				var popupOBP = document.createElement("P");
				var innerHTML = "";
				innerHTML = 'Còn <span class="tooltip-holder"><strong style="color:#008e01;">' + alert.num + '</strong>'
				if(typeof alert.nvs != 'undefined' && alert.nvs.length > 0)
				{
					innerHTML += '<span class="tooltips"><span class="innertooltips">';
					for (var i = 0; i < alert.nvs.length; i++) {
						innerHTML +=  alert.nvs[i].A.ten_phong + '</br>';
					}
					innerHTML += '</span></span>';
				}

				innerHTML += '</span> tổ thuộc Trung tâm bạn chưa đánh giá OB. Vui lòng đôn đốc, nhắc nhở cán bộ chưa đánh giá.';
				popupOBP.innerHTML = innerHTML;
				popupContent.insertBefore(popupOBP,popupContent.firstChild);
			}

			//OB
			//if(alert.day < alert.lastday && !document.getElementById('tat-thong-bao')) {
			//Điều kiện để xuất hiện nút bỏ qua
			if( (alert.type == "ob_nv" || alert.type == "360" )
			 && alert.day < alert.lastday
			 && !document.getElementById('tat-thong-bao')) {
					var popupCancel = document.createElement("A");
					popupCancel.setAttribute("href","#");
					popupCancel.className = "button";
					popupCancel.innerHTML = "Bỏ qua";
					popupCancel.id = "tat-thong-bao";
					popupContent.appendChild(popupCancel);
			}

			if(!alert.batbuoc
			&& (alert.type == 'ob_tt' || alert.type == 'ob_gd')
			&& !document.getElementById('tat-thong-bao') )
			{
				var popupCancel = document.createElement("A");
				popupCancel.setAttribute("href","#");
				popupCancel.className = "button";
				popupCancel.innerHTML = "Bỏ qua";
				popupCancel.id = "tat-thong-bao";
				popupContent.appendChild(popupCancel);
			}
			document.body.style.overflow = "hidden";
		};
    $.ajax({
      url: 'http://203.210.240.102:5013/api/survey/alert/<?php echo $this->session->read('Auth.User.username'); ?>',
      method: "GET",
      dataType: "json",
      success: function(data){
        var alert = JSON.parse(data);
				if(alert.result == 0)
				{
					//alertPopup(alertNum);
					return;
				}
				else if (document.cookie.indexOf('CakeCookie[popupexpire]=1') == -1) {
						alertPopup({num:alert.result, type: '360', day: alert.day, lastday: 7});

				}
				else {
					return;
				}
      }
    });

		$.get('/dg360/alertOB',function(data,status,xhr) {
			var alertData = JSON.parse(data);
			console.log(alertData);
			if(document.cookie.indexOf('CakeCookie[popupobexpire]=1') != -1) {
				return;
			}
			var batbuoc = false;
			if(alertData.nv.num != 0) {
				batbuoc = true;
				alertPopup({type: "ob_nv", num: alertData.nv.num, nvs: alertData.nv.ds, day: alertData.day, lastday: alertData.lastday, batbuoc: batbuoc});
			}
			if(alertData.tt.num != 0) {
				alertPopup({type: "ob_tt",num: alertData.tt.num, nvs: alertData.tt.ds, day: alertData.day, lastday: alertData.lastday, batbuoc: batbuoc});
			}
			if(alertData.gd.num != 0) {
				alertPopup({type: "ob_gd",num: alertData.gd.num, nvs: alertData.gd.ds, day: alertData.day, lastday: alertData.lastday, batbuoc: batbuoc});
			}

			//Candidate tooltips


		});

	jQuery(document).on("click", "#tat-thong-bao",function(e){
		e.preventDefault();
		var cd = new Date();
		cd.setHours(6);
		cd.setMinutes(0);
		cd.setSeconds(0);
		cd.setMilliseconds(0);
		document.cookie = 'CakeCookie[popupobexpire]=1; expires=' + cd.addDays(1).toUTCString() + ';path=/';
		document.cookie = 'CakeCookie[popupexpire]=1; expires=' + cd.addDays(1).toUTCString() + ';path=/';
		document.body.style.overflow = "auto";
		jQuery("#popupcontainer").remove();
	});
	});
}
</script>

</body>

</html>
