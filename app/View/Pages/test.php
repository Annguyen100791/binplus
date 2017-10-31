<script src="/js/commons/jquery-1.4.4.js"></script>
<ul>
	<li>1</li>
    <li id="cur" class="a">2<span>sub2</span></li>
    <li class="b">3</li>
    <li class="a">4</li>
    <li class="a">5</li>
</ul>
<script>
	$(document).ready(function(){
		$('#cur').nextAll('.a').first().each(function(){
			console.log($(this).html());
		});
	});
</script>
