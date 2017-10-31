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
	<tbody><tr valign="top">
	<td style="padding-left:20px">
    <div id="dg-360">
		<div id="dg-360-user">
				<h3>Cán bộ được đánh giá</h3>
				<ul>

				</ul>
    </div>
		<div id="dg-360-surveys">
			<form class="answer-form" id="answer-form" action="#" method="post">
			<ul>

			</ul>
			</form>
		</div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($){
      $.ajax({
        url: 'http://203.210.240.102:5013/api/survey/appreciated/users/<?php echo $username; ?>',
        method: "GET",
        dataType: "json",
				beforeSend: function(){
					$('#dg-360').append('<div class="loading-sc"></div>');
				},
        success: function(response){
					var obj = JSON.parse(response);
					console.log(obj);
					if(obj.length > 0) {
						for(var i = 0; i <= obj.length - 1; i++)
						{
							var $groupli = $('<li class="groupli"></li>');
							$groupli.append('<a class="group-title">' + obj[i].name + '</a>');
							if(obj[i].users.length > 0){
								$subuser = $('<ul class="sub-user"></ul>');
								for (var j = 0; j < obj[i].users.length; j++) {
									$subuser.append('<li><a class="get-question" href="#" data-aid="' + obj[i].users[j].ID + '">' + obj[i].users[j].name + '</a></li>');
								}
								$groupli.append($subuser);
							}
							else
							{
								$groupli.append('<p class="unavailable"><small>Đã đánh giá hết</small></p>');
							}
							$('#dg-360-user > ul').append($groupli);
						}
					}
					else {
						$('#dg-360-user').append('<div class="notification attention png_bg"><a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="" alt=""></a><div>Không có đánh giá nào cần thực hiện. </div></div>');
					}
          $(".loading-sc").fadeOut(400,function(){$(".loading-sc").remove()});
        }
      });
			var current;
			$('#dg-360-user').on("click", ".get-question",function(e){
				e.preventDefault();
				if($(this).hasClass('done'))
					return;
				$('.get-question').removeClass('active');
				current = this;
				$(this).addClass('active');
				$('.notification').remove();
				$('#dg-360-surveys ul').html('');
				var appreciate_id;
				var appreciated_id = $(this).data('aid');
				$('#dg-360').append('<div class="loading-sc"></div>');
				$.get('http://203.210.240.102:5013/api/user/<?php echo $username; ?>',function(data){
					var obj = JSON.parse(data);
					appreciate_id = obj.userID;
					$('#answer-form ul').append('<input name="appreciate_userid" type="hidden" value="' + appreciate_id + '" />');
					$('#answer-form ul').append('<input name="appreciated_userid" type="hidden" value="' + appreciated_id + '" />');
					$.ajax({
						url: 'http://203.210.240.102:5013/api/survey/questions/' + appreciate_id + '/' + appreciated_id,
						method: "GET",
		        dataType: "json",
						success: function(response) {
							var data = JSON.parse(response);
							console.log(data);
							for (var i = 0; i < data.length; i++) {
								var survey = data[i];
								var $li = $('<li></li>');

								$li.append('<h3>Bộ câu hỏi: ' + survey.surveyName + '</h3>');

								var $question_list = $('<ol class="question-list"></ol>');
								for (var i = 0; i < survey.questions.length; i++) {
									var quest = survey.questions[i];
									var $questli = $('<li></li>');
									$questli.append('<p class="question">' + quest.question + '</p>');
									$questli.append('<p class="form-control"><input class="question-radio" required type="radio" name="question[' + quest.ID + ']" value="' + quest.mark_a + '" /> Yếu</p>');
									$questli.append('<p class="form-control"><input class="question-radio" checked="checked" required type="radio" name="question[' + quest.ID + ']" value="' + quest.mark_b + '" /> Trung bình</p>');
									$questli.append('<p class="form-control"><input class="question-radio" required type="radio" name="question[' + quest.ID + ']" value="' + quest.mark_c + '" /> Khá</p>');
									$questli.append('<p class="form-control"><input class="question-radio" required type="radio" name="question[' + quest.ID + ']" value="' + quest.mark_d + '" /> Tốt</p>');
									$questli.append('<p class="form-control"><input class="question-radio" required type="radio" name="question[' + quest.ID + ']" value="custom" /><input class="question-custom" type="number" name="question[' + quest.ID + '][custom]" min="0" max="10" step="0.1" value="" /> <small class="error"><em>Có thể cho điểm cụ thể tại đây!</em></small></p>');
									$question_list.append($questli);
								}

								$li.append($question_list);
								$('#dg-360-surveys ul').append($li);

							}
							$(".loading-sc").fadeOut(400,function(){$(".loading-sc").remove()});
							if(survey.questions.length > 0)
								$('#answer-form ul').append('<li><p><input id="answer-submit" type="submit" class="btn btn-submit" value="Gửi đánh giá" /></p></li>');
						}
					});

				});

			});

			$('#dg-360-surveys').on("click","#answer-submit",function(e){
				$('.notification').remove();
				e.preventDefault();
				if(! $('#answer-form').valid()) return false;
				$('#dg-360').append('<div class="loading-sc"></div>');
				var data = $("#answer-form").serialize();
				$.post('http://203.210.240.102:5013/api/answer',data,function(){
					$(".loading-sc").fadeOut(400,function(){$(".loading-sc").remove()});
					$('#dg-360-surveys ul').find('input').attr("disabled","disabled");
					$('#dg-360-surveys ul').find('input[type="submit"]').remove();
					$('#dg-360-surveys').prepend('<div class="notification success png_bg"><a href="#" class="close"><img src="/img/icons/cross_grey_small.png" title="" alt=""></a><div>Gửi đánh giá thành công.</div></div>');
					if(current != null){
						$(current).removeAttr('href');
						$(current).removeClass('active');
						$(current).addClass('done');
					}
				});
			});
			$('#dg-360-surveys').on("click",".question-radio",function(e){
				if($(this).val() != 'custom'){
					var parentline =  $(this).parents("li")[0];
					$(parentline).find(".question-custom").val('');
					$(parentline).find(".question-custom").removeAttr("required");
				}
				else{
					var parentline =  $(this).parents("li")[0];
					$(parentline).find(".question-custom").attr("required","required");
				}
			});
			$('#dg-360-surveys').on("focus",".question-custom",function(e){
				$(this).parent().find('input[type="radio"]').attr("checked","checked");
				$(this).attr("required","required");
			});
		});
    </script>

	</td>
	<td>
    <div id="dg-360-questions">

    </div>

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
