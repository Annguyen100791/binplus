<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Tra cứu văn bản theo đơn vị chủ trì</h1></div>
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
    	<form id="form-vanban-search" method="post" action="/vanban/search_kq">
        	<table width="100%">
				
                <tr style="height:37px" >
                	<td style="text-align:right; padding-right:10px;" width="40%">Đơn vị chủ trì :</td>
                    <td width="60%">
                    <?php
						echo $this->Form->input('Vanban.phongchutri_id', array(
                                                          'label'	=>	false,
                                                          'div'		=>	false,
                                                          'class'	=>	'text-input',
														  'options'	=>	$dsphong,
														  'empty'	=>	''
                                                          ));
 					?>
                    </td>
                </tr>
                <tr style="height:37px">
                 	<td style="text-align:right;  padding-right:10px">Văn bản phát hành : </td>
                    <td>
                    <?php 
						echo 'từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'unread-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'unread-ngay-ketthuc'));
					?>
                    </td>
                 </tr>
                <tr style="height:37px">
                	<td></td>
                    <td><input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-vanban-search" /></td>
                </tr>
            </table>
        </form>	
    </div>
    
    <div id="vanban-search-content">
    	<?php echo $this->element('Common' . DS . 'kqvanban_search') ?>
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