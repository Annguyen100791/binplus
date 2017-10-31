<?php

	echo $this->Html->script(
		array(
			  'libs/tinymce/tinymce.min'
			  )
	);

	echo $this->Form->create('Obdanhgia', array('id' => 'form-ob-submit'));
?>
<div class="content-box-header" style="align:left">
        <h3>OB đánh giá Trung tâm Điều hành</h3>
        <div class="clear"></div>
</div>
<div class="formPanel">
  <label for="lbl_option" class="lbl_option"><input type="radio" value="1" name="ket_qua" id="ket_qua_yes" checked="checked"> Hài lòng</label>
  <label for="lbl_option" class="lbl_option"><input type="radio" value="0" name="ket_qua" id="ket_qua_no"> Không hài lòng</label>

</div>

<div class="formCheckbox">

<?php
	//pr($ly_do);die();
foreach($ly_do as $item )
{
	?>
	<p style="width:97%;">
<input type="checkbox" name="data[Obdanhgia][lydo_id][]" style="margin-right:10px;" value="<?php echo $item['Oblydo']['id']; ?>" />
		<?php echo $item['Oblydo']['ten_lydo'];  ?>

	</p>
<?php
}
?>
</div>
    <div style="text-align:right!important" class="dialog-footer">
    	<!--<button class="button" type="submit">Gửi nhận xét</button> -->
        <a href="" class='button' id='btn-nhanxet' >Gửi nhận xét</a>
    </div>
<?php
	echo $this->Form->end(null);
?>

<script>
	$(document).ready(function(){
		//console.log(jQuery('#form-ob-submit'));
		if (jQuery('#ket_qua_no').attr("checked")) {
			jQuery('.formCheckbox').addClass('show');
		}
		else {
			jQuery('.formCheckbox').removeClass('show');
		}

		jQuery('input[name="ket_qua"]').on('change', function(e){
			if (jQuery(this).val() == 0) {
				jQuery('.formCheckbox').addClass('show');
			}
			else {
				jQuery('.formCheckbox').removeClass('show');
			}
		});
		
		$('#btn-nhanxet').click(function(e){
			e.preventDefault();
			$('#form-ob-submit').attr('action', BIN.baseURL + ' dg360/ob_ttdh');
			if(confirm("Bạn chắc chắn với kết quả nhận xét này ko?"))
				$('#form-ob-submit').submit();
			else
				return false;
		});
		
	});
</script>
<style>
	.formPanel > * {
		display: inline-block;
		line-height: 1.5em;
	}
	.formPanel > * > * {
		display: inline;
		vertical-align: middle;
	}
	.lbl_option{
		margin-left: 30px;
   		margin-top: -20px;
		}
	.formCheckbox {
		max-height: 0;
		overflow: hidden;
		transition: 0.3s all;
		position: relative;
		left: 175px;

		border-radius: 3% / 11%;
		width: calc(75% - 245px);

	}
	.formCheckbox.show {
		max-height: 300px;
		padding:15px;
		border: 1px solid #ccc;
		margin-bottom:15px;
	}
</style>
