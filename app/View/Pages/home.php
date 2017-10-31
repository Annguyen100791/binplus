<div id="page-heading"><h1>Trang tin</h1></div>

<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
<tr>
	<th rowspan="3" class="sized"><img src="/img/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
	<th class="topleft"></th>
	<td id="tbl-border-top">&nbsp;</td>
	<th class="topright"></th>
	<th rowspan="3" class="sized"><img src="/img/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
</tr>
<tr>
	<td id="tbl-border-left"></td>
	<td>
	<!--  start content-table-inner -->
	<div id="content-table-inner">
	
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td style="padding-left:20px">
	<!-- BEGIN CONTENT -->
    	
        <div id="home-content">
            <!--   -->
            <?php if($this->Session->read('Auth.User.nhanvien_id') == 681 || $this->Session->read('Auth.User.nhanvien_id') == 784){?>
            <div>
                <div style="background:url(/img/icons/cake.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/birthday" title="Xem các tháng khác">Sinh nhật trong ngày hôm nay</a></h2>
                </div>
                <div>
                    <div style="float:left; vertical-align:top">
                        <img src="/img/shared/bir.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	<?php if(!empty($birthday)): ?>
                            <h3>Chúc mừng sinh nhật các cán bộ / nhân viên</h3>
                            <ul class="greyarrow" style="margin-left:20px!important">
                                <?php
                                    foreach($birthday as $item):
                                ?>
                                    <li>
                                    	<div style="float:left; width:150px; font-weight:bold;"><?php echo $this->Html->link($item['Nhanvien']['full_name'], '/tinnhan/compose/sendto:' . $item['Nhanvien']['id'], array('title' => 'Click để gửi tin nhắn cho nhân viên này')) ?></div>
                                        <div style="float:left; font-weight:bold; color:red">( <?php echo $this->Time->format('d-m-Y', $item['Nhanvien']['ngay_sinh']) ?> )</div>
                                        <div style="clear:both"></div>
                                    </li>
                                <?php
                                    endforeach;
                                ?>
                            </ul>
                        <?php 
                            else:
                                echo 'Không có cán bộ / nhân viên nào có sinh nhật trong ngày hôm nay.';
                            endif;
                        ?>
                    </div>
                    <div style="clear:both"></div>
                </div>
            
       </div>
       		<div class="lines-dotted-long"></div>
            <?php } ?>
            <!--   -->
            
            <div style="background:url(/img/icons/new.gif) left top no-repeat; padding-left:30px">
                <h2><a href="http://vienthongdanang.com.vn" title="Link đến trang VTĐN">Chính sách mới về dịch vụ VT-CNTT</a></h2>
            </div>
            <div style="display: table">
                    <div style="display: table-cell; vertical-align:top;">
                	<img src="/img/shared/chinhsach.png" />
                	</div>
                    <div id="news-chinhsach" style="display: table-cell; vertical-align:top; padding:0 30px; text-align:justify">
                        <img src="/img/circle_ball.gif" />
                    </div>
                    <div style="clear:both"></div>
            </div>
        
        	<div class="lines-dotted-long"></div>
 		</div>   
        
        <div id="home-content">
            <div style="background:url(/img/icons/new.gif) left top no-repeat; padding-left:30px">
                <h2><a href="http://vhdn.vnpt.vn" title="Link đến trang văn hóa VNPT">Văn hóa VNPT</a></h2>
            </div>
            <div style="display: table">
                    <div style="display: table-cell; vertical-align:top;">
                	<img src="/img/shared/tbao.png" />
                	</div>
                    <div id="news-content" style="display: table-cell; vertical-align:top; padding:0 30px; text-align:justify">
                        <img src="/img/circle_ball.gif" />
                    </div>
                    <div style="clear:both"></div>
            </div>
        
        	<div class="lines-dotted-long"></div>
 		</div>
        
           
        
            <!-- Lịch làm việc toàn Viễn thông -->
			<?php
				if(!empty($lichlamviec)):
			?>
            <div>
                <div style="background:url(/img/icons/calendar_view_day.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/lamviec/0" title="Xem toàn bộ lịch làm việc">Lịch làm việc Viễn thông Đà Nẵng </a></h2>
                </div>
                <div>
                	<div style="float:left; vertical-align:top">
                        <img src="/img/shared/lich.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	
							<ul class="greyarrow" style="margin-left:20px!important">
						<?php
							foreach($lichlamviec as $item):
								echo '<li>';
								echo $this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' : ';
								echo $this->Html->link($item['Lichlamviec']['tieu_de'], '/calendars/view_lichlamviec/' . $item['Lichlamviec']['id'], array('data-mode' => 'ajax', 'data-action' => 'dialog', 'title' => 'Chi tiết lịch công tác'));
								echo '</li>';
							endforeach;
						?>
							</ul>
						
                    </div>
                    <div style="clear:both"></div>
                </div>
                
            </div>
            
            <div class="lines-dotted-long"></div>
            
            <?php
				endif;
			?>
           <!-- Lịch làm việc của phòng ban thuộc Viễn thông --> 
           
           <?php
				if(!empty($lichphong_vt)):
			?>
            <div>
                <div style="background:url(/img/icons/calendar_view_day.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/lamviec/1" title="Xem toàn bộ lịch làm việc">Lịch làm việc <?php echo $phong_ban['Phong']['ten_phong'] ?> </a></h2>
                </div>
                <div>
                	<div style="float:left; vertical-align:top">
                        <img src="/img/shared/lich.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	
							<ul class="greyarrow" style="margin-left:20px!important">
						<?php
							foreach($lichphong_vt as $item):
								echo '<li>';
								echo $this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' : ';
								echo $this->Html->link($item['Lichlamviec']['tieu_de'], '/calendars/view_lichlamviec/' . $item['Lichlamviec']['id'], array('data-mode' => 'ajax', 'data-action' => 'dialog', 'title' => 'Chi tiết lịch công tác'));
								echo '</li>';
							endforeach;
						?>
							</ul>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
            <div class="lines-dotted-long"></div>
            <?php
				endif;
			?>
            <!-- Lịch làm việc của trung tâm trực thuộc -->
            <?php
				if(!empty($lichtrungtam)):
			?>
            <div>
                <div style="background:url(/img/icons/calendar_view_day.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/lamviec/2" title="Xem toàn bộ lịch làm việc">Lịch làm việc <?php echo $phong['Phong']['ten_phong'] ?> </a></h2>
                </div>
                <div>
                	<div style="float:left; vertical-align:top">
                        <img src="/img/shared/lich.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	
							<ul class="greyarrow" style="margin-left:20px!important">
						<?php
							foreach($lichtrungtam as $item):
								echo '<li>';
								echo $this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' : ';
								echo $this->Html->link($item['Lichlamviec']['tieu_de'], '/calendars/view_lichlamviec/' . $item['Lichlamviec']['id'], array('data-mode' => 'ajax', 'data-action' => 'dialog', 'title' => 'Chi tiết lịch công tác'));
								echo '</li>';
							endforeach;
						?>
							</ul>
						
                    </div>
                    <div style="clear:both"></div>
                </div>
                
            </div>
            
            <div class="lines-dotted-long"></div>
            
            <?php
				endif;
			?>
		<!-- Lịch làm việc của phòng ban thuộc trung tâm -->
			<?php
				if(!empty($lichphong_tt)):
			?>
            <div>
                <div style="background:url(/img/icons/calendar_view_day.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/lamviec/3" title="Xem toàn bộ lịch làm việc">Lịch làm việc <?php echo $phong_ban['Phong']['ten_phong'] ?> </a></h2>
                </div>
                <div>
                	<div style="float:left; vertical-align:top">
                        <img src="/img/shared/lich.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	
							<ul class="greyarrow" style="margin-left:20px!important">
						<?php
							foreach($lichphong_tt as $item):
								echo '<li>';
								echo $this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' : ';
								echo $this->Html->link($item['Lichlamviec']['tieu_de'], '/calendars/view_lichlamviec/' . $item['Lichlamviec']['id'], array('data-mode' => 'ajax', 'data-action' => 'dialog', 'title' => 'Chi tiết lịch công tác'));
								echo '</li>';
							endforeach;
						?>
							</ul>
						
                    </div>
                    <div style="clear:both"></div>
                </div>
                
            </div>
            
            <div class="lines-dotted-long"></div>
            
            <?php
				endif;
			?>
       <?php if($this->Session->read('Auth.User.nhanvien_id') != 681 && $this->Session->read('Auth.User.nhanvien_id') != 784){?>
       <div>
                <div style="background:url(/img/icons/cake.png) left top no-repeat; padding-left:30px">
                    <h2><a href="/calendars/birthday" title="Xem các tháng khác">Sinh nhật trong ngày hôm nay</a></h2>
                </div>
                <div>
                    <div style="float:left; vertical-align:top">
                        <img src="/img/shared/bir.png" />
                    </div>
                    <div style="float:left; padding-left:30px">
                    	<?php if(!empty($birthday)): ?>
                            <h3>Chúc mừng sinh nhật các cán bộ / nhân viên</h3>
                            <ul class="greyarrow" style="margin-left:20px!important">
                                <?php
                                    foreach($birthday as $item):
                                ?>
                                    <li>
                                    	<div style="float:left; width:150px; font-weight:bold;"><?php echo $this->Html->link($item['Nhanvien']['full_name'], '/tinnhan/compose/sendto:' . $item['Nhanvien']['id'], array('title' => 'Click để gửi tin nhắn cho nhân viên này')) ?></div>
                                        <div style="float:left; font-weight:bold; color:red">( <?php echo $this->Time->format('d-m-Y', $item['Nhanvien']['ngay_sinh']) ?> )</div>
                                        <div style="clear:both"></div>
                                    </li>
                                <?php
                                    endforeach;
                                ?>
                            </ul>
                        <?php 
                            else:
                                echo 'Không có cán bộ / nhân viên nào có sinh nhật trong ngày hôm nay.';
                            endif;
                        ?>
                    </div>
                    <div style="clear:both"></div>
                </div>
            
       </div>
       <?php } ?>
            
            
    <!-- END CONTENT -->
	

	</td>
	<td>

	<!--  start related-activities -->
    <div id="related-activities">
    	<!--  start related-act-top -->
        <div id="related-act-top">
        <a href="javascript:void(0)" title="Click để cập nhật thông tin" id="btn-refresh-notice"><img src="/img/forms/header_related_act.png" width="271" height="43" alt="" /></a>
        </div>
        <!-- end related-act-top -->
        
        <!--  start related-act-bottom -->
        <div id="related-act-bottom">
        	<!--  start related-act-inner -->
        	<div id="related-act-inner">
            </div>
            <!-- end related-act-inner -->
        	<div class="clear"></div>
        </div>
    </div>
	<!-- end related-activities -->

</td>
</tr>
<tr>
<td><img src="/img/shared/blank.gif" width="695" height="1" alt="blank" /></td>
<td></td>
</tr>
</table>
 
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
</table>

<div class="clear">&nbsp;</div>