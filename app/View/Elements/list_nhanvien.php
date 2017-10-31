<div id="nhanvien-list">
    <ul>
        <li class="level-root" style="padding-left:0!important">
        	<div class="phong">
            	<div class="checkbox"><input class="check-all" type="checkbox" title="Click để chọn/ bỏ chọn tất cả nhân viên trong danh sách" /></div>
                <div class="text"><span style="font-weight:bold; font-size:16px">Họ tên Nhân viên</span></div>
                <div class="toggle collapse" title="Click để thu hẹp"></div>
                <div style="clear:both"></div>
            </div>
        	 
        </li>
        <?php
            if(!empty($ds)):
                $class = 'root';
                for($i = 0; $i < count($ds); $i++):
					if(empty($ds[$i]['Phong']['thuoc_phong'])) // parent is NULL
					{
						$class = 'root' . $ds[$i]['Phong']['id']; 
					}
					else
					{
						$class = $class . $ds[$i]['Phong']['tree_prefix'];
					}
			?>
            	<li class="<?php echo $class ?>" style="padding-left:<?php echo $ds[$i]['Phong']['padding'] ?>px">
                	<div class="phong">
                    	<div class="checkbox"><input type="checkbox" class="check-all" title="click để chọn/ bỏ chọn tất cả nhân viên thuộc phòng"></div>
                        <div class="text">
                        	<?php 
								echo $ds[$i]['Phong']['tree_prefix'] . $ds[$i]['Phong']['ten_phong'] 
							?>
                        </div>
                        <div class="toggle collapse" title="Click để thu hẹp"></div>
                        <div style="clear:both"></div>
                    </div>
                    <?php
						if(!empty($ds[$i]['Nhanvien'])):
					?>
                    	<ul class="nhanvien">
                        <?php
							foreach($ds[$i]['Nhanvien'] as $nhanvien):
						?>
                        	<li>
                            	<input type="checkbox" name="data[Chitiettinnhan][][nguoinhan_id]" value="<?php echo $nhanvien['Nhanvien']['id'] ?>" <?php echo (!empty($tinnhan) && isset($tinnhan['Tinnhan']['nguoigui_id']) && $nhanvien['Nhanvien']['id'] == $tinnhan['Tinnhan']['nguoigui_id']) ? 'checked' : '' ?> />
                                <span>
                                	<a href="#" class="select_nv" title="Click để chọn nhân viên này">
                                    <?php 
										if($nhanvien['Nhanvien']['nguoi_quanly'] == 1)
											echo '<b>' . $nhanvien['Nhanvien']['full_name'] . '</b>';
										else
											echo $nhanvien['Nhanvien']['full_name'] ;
									?>
                                    </a>
                                </span>
                            </li>
                        <?php
							endforeach;
						?>
                        </ul>
                    <?php
						endif;
					?>
                </li>
            <?php
                endfor;
            endif;
        ?>
    </ul>
    
</div>	
<script>
	$(document).ready(function(){
		
		$('.toggle').click(function(){
			
			var first = $(this).parent().parent(); 								// li
			
			if($(this).hasClass('collapse'))	// hide
			{
				$(this).removeClass('collapse');
				$(this).addClass('expand');
				$(this).attr('title', 'Click để mở rộng');
				
				$(first).find("ul").hide();
				
				if(first.attr('class') == 'level-root')
				{
					$(first).parent().find('>li').each(function(){
						$('[class^="' + $(this).attr('class') + '-"]').hide();
					});
					$(first).parent().find('ul').hide();
					
					$('.toggle').removeClass('collapse').addClass('expand').attr('title', 'Click để mở rộng');
				}
				else{
					$('[class^="' + $(first).attr('class') + '-"]').hide();
				}
				
			}else	// show
			{
				$(this).removeClass('expand');
				$(this).addClass('collapse');
				$(this).attr('title', 'Click để thu hẹp')
				
				
				$(first).find("ul").show();
				
				if(first.attr('class') == 'level-root')
				{
					$(first).parent().find('>li').each(function(){
						$('[class^="' + $(this).attr('class') + '-"]').show();
					});
					$(first).parent().find('ul').show();
					$('.toggle').removeClass('expand').addClass('collapse').attr('title', 'Click để thu hẹp');
				}
				else{
					$('[class^="' + $(first).attr('class') + '-"]').show();
				}
			}
			
		});
		
		$('#nhanvien-list .check-all').click(function(){
			var checked = $(this).attr('checked');
			var first = $(this).parent().parent().parent(); 	
			
			$('input[type=checkbox]', first).attr('checked', checked);
			
			
			if(first.attr('class') == 'level-root')
				$('input[type=checkbox]', '#nhanvien-list').attr('checked', checked);
			else{
				$('[class^="' + $(first).attr('class') + '-"]').each(function(){
					$('input[type=checkbox]', this).attr('checked', checked);
				});
			}
		});
		
		$('#nhanvien-list .select_nv').click(function(){
			var row = $(this).parent().parent();
			var obj = row.find("input[type=checkbox]");
			obj.attr("checked", !obj.attr("checked"));
			return false;
		});
		
		$('input').each(function(){
			if($(this).attr('title'))					 
				$(this).qtip({
							 show: 'mouseover',
							 hide: 'mouseout',
							 position:	{at: 'top center', my: 'bottom center'},
							 style:	{classes:"ui-tooltip-rounded ui-tooltip-shadow"}
							 });
		});
	});
</script>