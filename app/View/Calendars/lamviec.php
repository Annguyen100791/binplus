<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Xem lịch công tác</h1></div>
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
	<?php
		echo $this->Html->css(array(
				'/js/libs/fullcalendar/fullcalendar',
		));
		
		echo $this->Html->script(
			array(
				  'libs/fullcalendar/fullcalendar.min',
				  )
		);
	?>
    
    	<div style="padding:10px">
            <div style="float:left">
                <?php if($this->Layout->check_permission('LichCongTac.tao_lichcongtac')):?>
                <a class="button" style="font-weight:bold" href="/calendars/add_lichlamviec" data-mode="ajax" data-action="dialog" data-width="450" title="Thêm mới Lịch công tác">Thêm mới Lịch công tác</a>
                <?php endif;?>
            </div>
            <div style="float:right">
                <form id="form-phamvi">
				<?php
					echo $this->Form->input('pham_vi', array('class' => 'text-input', 'label' => false, 'div' => false, 'options' => $ds_phamvi, 'id' => 'pham_vi', 'empty' => 'Tất cả', 'value' => $phamvi));
                	//pr($phamvi);die();
				
				?>
                <button class="button" type="submit">Xem</button>
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <div id="table-content">
            <div id='calendar'></div>
            <div style=" padding:10px 0"><span style="font-weight:bold; text-decoration:underline;">Chú thích lịch công tác</span></div>
            <div>
                <div style="width:30px; height:15px; background-color:#aed7ff; border:1px solid #999; float:left; margin-right:5px"></div>
                <div style="float:left; margin-right:5px">Viễn thông Đà Nẵng</div>
                <div style="width:30px; height:15px; background-color:#ffff99; border:1px solid #999; float:left; margin-right:5px"></div>
                <div style="float:left; margin-right:5px">Phòng ban thuộc Viễn thông</div>
                <div style="width:30px; height:15px; background-color:#ffdbd9; border:1px solid #999; float:left; margin-right:5px"></div>
                <div style="float:left; margin-right:5px">Trung tâm trực thuộc</div>
                <div style="width:30px; height:15px; background-color:#ffaf99; border:1px solid #999; float:left; margin-right:5px"></div>
                <div style="float:left; margin-right:5px">Phòng ban thuộc Trung tâm</div>
            </div>
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