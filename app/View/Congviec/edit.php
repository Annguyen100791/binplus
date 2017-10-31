<?php

	echo $this->Html->script(

		array(

			  'libs/tinymce/tinymce.min'

			  )

	);

?>

<style>

.dynatree-container{

	height: 560px!important; overflow:auto

}

</style>

<!--  start page-heading -->



<div id="page-heading">

	<div style="float:left"><h1>Chỉnh sửa công việc</h1></div>

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

	echo $this->Session->flash();

	$nguoinhan = array();

	if(!empty($dsnguoinhan))

		foreach($dsnguoinhan as $item)

			array_push($nguoinhan, $item['Congviec']['nguoinhan_id']);

				
	
	echo $this->Form->create('Congviec', array('id' => 'form-congviec-edit')); 

	echo $this->Form->hidden('id', array('id' => 'congviec_id'));
	echo $this->Form->hidden('nv_selected', array('id' => 'nv_selected', 'value' => implode(',', $nguoinhan)));
?>

    <div id="compose-list-content">

    	<div class="content-box" style="width:62%; float:left">

            <div class="tab-content default-tab" id="congviec-noidung">

            <?php

					echo $this->Session->flash();

                    echo '<p>';

                    echo $this->Form->input('ten_congviec',

                        array(

                              'label'	=>	'Tên công việc <span class="required">*</span>',

                              'div'		=>	false,

                              'class'	=>	'text-input',

                              'style'	=>	'width:97.5%',

                              'id'		=>	'ten_congviec'

                              ));

                    echo '</p>';

                    

            ?>
			<?php if(!empty($this->data['Congviec']['vanban_id'])): 
			echo $this->Form->hidden('vanban_id', array('id' => 'vanban_id', 'value' => $data['Congviec']['vanban_id']));
			?>

       
        <?php endif;?>
            	<p>

                    <label>Giao việc theo văn bản</label>
					<div style="border:1px solid #CCC; padding:10px;-moz-border-radius: 4px; -webkit-border-radius: 4px;			border-radius: 4px; line-height:1.5em;">

            	<?php 

					echo $this->Html->link(

						sprintf("<b>Trích yếu </b>: %s <BR><b>Số hiệu </b>: %s, <b>Ngày phát hành </b>: %s, <b>Nơi phát hành </b>: %s", 

							$this->data['Vanban']['trich_yeu'], 

							$this->data['Vanban']['so_hieu'], 

							$this->Time->format('d-m-Y', $this->data['Vanban']['ngay_phathanh']), $this->data['Vanban']['noi_gui']),

						'/vanban/view/' . $this->data['Congviec']['vanban_id'] . '#xem_chitiet',

						array('escape' => false, 'title' => 'Click để xem chi tiết văn bản', 'target' => '_blank')

					); 

				?>

            </div>
                    <div id="congviec-vanban"></div>

                    <div id="chonvanban-container">

                        <a class="button" href="/congviec/chon_vanban" data-mode="ajax" data-action="dialog" title="Chọn văn bản" data-width="800">Sửa văn bản</a>

                    </div>

                </p>

                <div>

                    <div style="float:left; width:27%">

                    <?php

                        echo $this->Form->input('tinhchat_id',

                        array(

                              'label'	=>	'Tính chất công việc',

                              'div'		=>	false,

                              'class'	=>	'text-input',

                              'style'	=>	'width:97.5%',

                              'empty'	=>	'',

                              'options'	=>	$tinhchat,

                              'id'		=>	'tinhchat_id'

                              ));

                    ?>

                    </div>

                    <div style="float:left; width:27%">

					<?php

                        echo $this->Form->input('loaicongviec_id',

                        array(

                              'label'	=>	'Phân loại công việc <span class="required">*</span>',

                              'div'		=>	false,

                              'class'	=>	'text-input',

                              'style'	=>	'width:97.5%',

                              'options'	=>	$loai_congviec,

                              'id'		=>	'loaicongviec_id'

                              ));

                    ?>

                    </div>
					<div style="float:left; width:180px; margin-left:10px">

                        <label>Ngày bắt đầu <span class="required">*</span></label>

                        <input <?php if($this->data['Congviec']['mucdo_hoanthanh']==10){echo 'disabled="disabled"';} ?> name="data[Congviec][ngay_batdau]" id="ngay_batdau" style="width:160px" class="text-input" value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_batdau']) ?>" />

                    </div>

                    <div style="float:left; width:180px; margin-left:10px">

                        <label>Ngày hoàn thành <span class="required">*</span></label>

                        <input <?php if($this->data['Congviec']['mucdo_hoanthanh']==10){echo 'disabled="disabled"';} ?> name="data[Congviec][ngay_ketthuc]" id="ngay_ketthuc" style="width:160px" class="text-input"  value="<?php echo $this->Time->format('d-m-Y', $this->data['Congviec']['ngay_ketthuc']) ?>"/>

                    </div>

                    

                    <div style="clear:both"></div>

                </div>

            <p>

				<div style="float:left">

				<?php

                    echo $this->Form->input('nguoinhan_id', 

                        array(

                              'label'	=>	'Nhân sự chịu trách nhiệm chính <span class="required">*</span>',

                              'div'		=>	false,

                              'class'	=>	'text-input',

                              'id'		=>	'nguoinhan_id',

                              'style'	=>	'min-width:330px; width:auto',

                              'options'	=>	$nv,

                              'empty'	=>	'-- chọn cán bộ / nhân viên chịu trách nhiệm chính --'

                              )

                    );

                ?>

                </div>

                <div style="float:left; padding-left: 30px"> 

                    <label>&nbsp;</label>

                    <input type="checkbox" name="data[Congviec][giaoviec_tiep]" <?php echo !empty($this->data['Congviec']['giaoviec_tiep']) ? 'checked' :'' ?> /> <b>Được phép giao việc cho người khác ?</b>

                </div>

                <div style="clear:both"></div>

            </p>

            <p>

                <?php

					echo $this->Form->input('noi_dung',

					array(

						  'label'	=>	'Nội dung <span class="required">*</span>',

						  'div'		=>	false,

						  'class'	=>	'text-input',

						  'rows'	=>	3,

						  'style'	=>	'width:97.5%',

						  'id'		=>	'noi_dung'

						  ));

				?>

            </p>

            </div>
            

        </div> <!-- End .content-box -->

        <div class="content-box" style="width:36%; float:right" id="congviec_nguoinhanviec">

        </div> <!-- End .content-box -->

        <div class="clear"></div>

    

    </div>
<div style="padding:10px">

        <a class="button btn-congviec-edit" href="#">Lưu thông tin</a>

    </div>
	</div>

    <!--  end content-table-inner ............................................END  -->

    <?php

	echo $this->Form->end();

?>

    </td>

    <td id="tbl-border-right"></td>

</tr>

<tr>

    <th class="sized bottomleft"></th>

    <td id="tbl-border-bottom">&nbsp;</td>

    <th class="sized bottomright"></th>

</tr>

</table>
<script>

	

	$(document).ready(function(){

		<?php

			if(!empty($vanban)):

		?>

			$('#congviec-vanban').html('<div class="uploaded_item"><div style="float:left"><?php echo $this->Html->link($vanban['Vanban']['trich_yeu'], '/vanban/view/' . $vanban['Vanban']['id'], array('title' => 'Xem chi tiết', 'target' => '_blank')) ?></div><div style="float:right"><a href="javascript:void(0)" onClick="unselect()"><img src="/img/closelabel.png" title="Bỏ chọn"></a></div><div style="clear:both"></div><input type="hidden" name=data[Congviec][vanban_id] value="<?php echo $vanban['Vanban']['id'] ?>"></div>');

			$('#chonvanban-container').hide();

		<?php

			endif;

		?>

		
		BIN.doUpdate('<a href="nhanvien/nhanviennhan/CongViec.khoitao" data-target="congviec_nguoinhanviec" data-title="Thêm người nhận việc">');

		

		

	});

</script>


