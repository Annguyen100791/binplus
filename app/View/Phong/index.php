<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Quản lý Phòng ban</h1></div>
    <div style="float:right;padding-right: 16px">
    	<ul class="shortcut-buttons-set">
        	<li><a class="shortcut-button" href="/phong/add" data-mode="ajax" data-action="dialog" data-width="400" title="Nhập mới Phòng ban"><img src="/img/icons/add.png" alt="icon" /></a></li>
            <li><a class="shortcut-button" href="/phong/delete" title="Xóa các Phòng ban đã chọn" data-mode="ajax" data-action="delete-all" data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn xóa các Phòng ban đã chọn hay không ?" data-target="phong-list"><img src="/img/icons/delete.png"/></a></li>
            <li><a class="shortcut-button" href="/phong/" title="Reload danh sách" data-mode="ajax" data-action="update" data-target="phong-list"><img src="/img/icons/refresh.png" alt="Reload danh sách" /></a></li>
        </ul>
        <div style="clear:both"></div>
    </div>
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
		<div id="phong-list" class="table-content">
        	<?php echo $this->element('Common' . DS . 'phong') ?>
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