<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Cán bộ/ nhân viên đang online</h1></div>
    <div style="clear:both"></div>
</div>
<!-- end page-heading -->
    
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
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner">
	<div>
    	<?php if(!empty($online)): ?>
               	<?php
					foreach($online as $item)
					{
						if($item['Nhanvien']['id'] == $this->Session->read('Auth.User.nhanvien_id'))
						//echo $this->Html->link('<b>' . $item['User']['fullname'] . '</b>', '/nhanvien/view/' . $item['Nhanvien']['id'], array('title' => 'Xem chi tiết nhân viên', 'escape' => false)) . ' - ';
							echo '<b>' . $item['User']['fullname'] . '</b> - ';
						else
							echo $this->Html->link($item['User']['fullname'], '/tinnhan/compose/sendto:' . $item['Nhanvien']['id'], array('title' => 'Click để gửi tin nhắn cho nhân viên này')) . ' - ';
					}
				?>
                <?php 
                 else:
                    echo 'Không có cán bộ / nhân viên nào đang online.';
                 endif;
               ?>
    </div>
    </div>
    <!--  end content-table-inner ............................................END  -->
    </td>
    <td id="tbl-border-right"></td>
</tr>
<tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
</tr>
</table>               