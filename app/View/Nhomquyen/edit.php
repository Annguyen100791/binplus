<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Hiệu chỉnh Nhóm Quyền hạn</h1></div>
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
    <div class="table-content">
<?php
	echo $this->Form->create('Nhomquyen', array('id'	=>	'form-nhomquyen-edit'));
	echo $this->Form->hidden('id');
?>
    <p>
    <?php
		echo $this->Form->input('ten_nhomquyen',
								array(
									  'label'	=>	'Tên Nhóm Quyền hạn <span class="required">*</span>',
									  'div'		=>	false,
									  'class'	=>	'text-input',
									  'style'	=>	'width:97.5%',
									  'id'		=>	'ten_nhomquyen'
									  ));
	?>
    </p>
    <p style="padding-top:10px">
        <table id="quyen-data">
        	<thead>
        	<tr  class="alt-row">
            	<th width="1%"><input type="checkbox" /></th>
                <th style="font-weight:bold" width="70%">Danh sách quyền hạn <span class="required">*</span></th>
                <th style="font-weight:bold" width="30%">Phạm vi quyền hạn</th>
            </tr>
            </thead>
            <tbody>
            <?php
			$prefix = '';
			for($i = 0; $i < count($quyen); $i++):
				$s = substr($quyen[$i]['Quyen']['tu_khoa'], 0, strpos($quyen[$i]['Quyen']['tu_khoa'], '.'));
				if($prefix == '' || $prefix != $s)
				{
				?>
                	<tr>
                    	<td></td>
                        <td colspan="2" style="font-weight:bold"><input type="checkbox" /> &nbsp;&nbsp;<?php echo $ten_quyen[$s] ?></td>
                    </tr>
                <?php
				}
				?>
                	<tr>
                    	<td></td>                        
						<td style="padding-left:20px"><input name="data[QuyenNhomquyen][quyen_id][<?php echo $quyen[$i]['Quyen']['id'] ?>]" type="checkbox" <?php echo (array_key_exists($quyen[$i]['Quyen']['id'], $this->data['Quyen'])) ? 'checked' : '' ?> value="<?php echo $quyen[$i]['Quyen']['id'] ?>" /> &nbsp;&nbsp;<?php echo $quyen[$i]['Quyen']['ten_quyen'] ?></td>
                        <td>
						<?php 
							$selected = (array_key_exists($quyen[$i]['Quyen']['id'], $this->data['Quyen'])) ? $this->data['Quyen'][$quyen[$i]['Quyen']['id']] : null;
							echo '<select name=data[QuyenNhomquyen][pham_vi][' . $quyen[$i]['Quyen']['id'] . ']>';
								foreach($pham_vi as $k=>$v)
									printf("<option value='%s' %s>%s</option>", $k, (!is_null($selected) && $k == $this->data['Quyen'][$quyen[$i]['Quyen']['id']]) ? 'selected' : '', $v);
							echo '</select>';
//							echo $this->Form->input('QuyenNhomquyen.pham_vi', array('label' => false, 'div' => false, 'options' => $pham_vi, 'value' => $selected)); 
						?>
                        </td>
                    </tr>
                <?php
				$prefix = substr($quyen[$i]['Quyen']['tu_khoa'], 0, strpos($quyen[$i]['Quyen']['tu_khoa'], '.'));
			endfor;
			?>
            </tbody>
        </table>
    </p>
    <p style="text-align:right!important; padding:10px"><input class="button" type="submit" value="Lưu dữ liệu" /></p>
<?php
	echo $this->Form->end();
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