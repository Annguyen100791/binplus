<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Tìm kiếm và hiệu chỉnh văn bản đã gửi</h1></div>
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
    	<form id="form-vanban-sua" method="post">
        	<table width="100%">
            	<tr style="height:37px">
                	<td style="text-align:right; padding-right:10px" width="40%">Từ khóa : </td>
                    <td width="60%">
                    <?php
						echo $this->Form->input('Vanban.keyword', array(
                                                          'label'	=>	false,
                                                          'div'		=>	false,
                                                          'class'	=>	'text-input'
                                                          ));
					?>
                    </td>
                </tr>
                
                <tr style="height:37px">
                	<td style="text-align:right; padding-right:10px">Loại văn bản :</td>
                    <td>
                    <?php
						echo $this->Form->input('Vanban.loaivanban_id', array(
                                                          'label'	=>	false,
                                                          'div'		=>	false,
                                                          'class'	=>	'text-input',
														  'options'	=>	$loaivanban,
														  'empty'	=>	''
                                                          ));
						echo ' &nbsp; Chiều đến / đi : ';
						echo $this->Form->input('Vanban.chieu_di', array(
                                                          'label'	=>	false,
                                                          'div'		=>	false,
                                                          'class'	=>	'text-input',
														  'options'	=>	$chieu_di,
														  'empty'	=>	''
                                                          ));																  
					?>
                    </td>
                </tr>
                <tr style="height:37px">
                	<td style="text-align:right;  padding-right:10px">
                    <?php
						 echo $this->Form->input('Vanban.ngay', array('label' => false,
                                                         'div'	=>	false,
                                                         'empty'	=>	' .. Chọn ngày tìm kiếm .. ',
                                                         'options'	=>	array('ngay_phathanh' 	=> 'Tìm theo ngày phát hành',
                                                                              'ngay_nhan'		=> 'Tìm theo ngày đến')));
					?>
                    </td>
                    <td>
                    <?php
						echo $this->Form->day('Vanban.ngay_phathanh', array('empty' => 'Ngày'));
						echo "-";
						echo $this->Form->month('Vanban.ngay_phathanh', array('empty' => 'Tháng'));
						echo "-";
						echo $this->Form->year('Vanban.ngay_phathanh', 2000, date("Y"), array('empty' => false, 'value' => date("Y")));
						?>
                    </td>
                </tr>
                <tr style="height:37px">
                	<td></td>
                    <td><input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-vanban-sua" /></td>
                </tr>
            </table>
        </form>	
    </div>
    
    <div id="vanban-sua-content">
    	<?php echo $this->element('Common' . DS . 'vanban_sua') ?>
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