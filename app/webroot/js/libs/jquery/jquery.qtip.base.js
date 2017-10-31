/* Cached from: js/base.js on Tue, 09 Aug 2011 08:31:21 -0700 */

$(document).ready(function()
{
	$.extend(true, $.fn.qtip.defaults,{style:{classes:"ui-tooltip-rounded ui-tooltip-shadow"}});
	var d = {
			links : {
					position : { 
								at : "top center",
								my : "bottom center",
								viewport : $(window)
								}
					}
		};
	$('a[title!=""]').qtip(d.links);

});