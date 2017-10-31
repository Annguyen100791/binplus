<?php 
	echo $this->Form->create('Vanban', array('id' => 'form-donvichutri_edit')) ;
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('phongchutri_id', array('id' => 'phongchutri_id', 'value' =>$phongchutri_id ));
?>

<div class="formPanel">

<p>	

	<label>Đơn vị chủ trì : <span class="required"></span></label>

	<?php

        echo $this->Form->input('Vanban.phongchutri_id', array('label'	=>	false,
															'id'		=> 'donvichutri_edit_phongchutri_id',
															'value' => $phongchutri_id,
                                                         'div'		=>	false,

                                                         'options'	=>	$dsphong));

    ?>

</p>

</div>    

    <div style="text-align:right!important" class="dialog-footer">

    	<button class="button" type="submit">Cập nhật</button>

        <button class="button btn-close" type="button">Đóng</button>

    </div>

<?php echo $this->Form->end();?>
<script>
	$(document).ready(function(e) {
		//$("#phongchutri_id").focus();
		$('#form-donvichutri_edit').submit(function(event){
			event.preventDefault();
			var form = event.target;
			$.ajax({
				type:		'POST',
				url:		$(this).attr('action'),
				dataType:	'json',
				data:		$(form).serialize(),
				success:	function(result)
				{
					$('#dvchutri_name').html($('#donvichutri_edit_phongchutri_id option:selected').text());
					$('#phongchutri_id').val($('#donvichutri_edit_phongchutri_id').val());
					
					var tree = $("#nhanvien-ds").dynatree("getTree");
					var _p1 = function(){
						return new Promise(function(resolve,reject){
							if(typeof document.tempSelectedTree != 'undefined' && document.tempSelectedTree.length > 0)
							{
								for(var i = 0; i < document.tempSelectedTree.length; i++)
								{
									var node = tree.getNodeByKey('nv-' + document.tempSelectedTree[i]);
									//if(typeof node != 'undefined' && node != null)
										//node.select(false);
									if(i == document.tempSelectedTree.length - 1)
									{
										resolve();
									}
								}
							}
							else
							{
								resolve();
							}
						});
					}
					_p1().then(function(){
						var nvs = result;
						var temp = [];
						var lvl2 = null;
						document.tempSelectedTree = [];
						var txt = '</br>';
						for(var i = 0;i < nvs.length;i++)
						{
							temp.push(nvs[i].b.id);
						}
						//console.log(temp);
						for(var j = 0;j < temp.length;j++)
						{
							var node = tree.getNodeByKey('nv-' + temp[j]);
							if(typeof node != 'undefined' && node != null)
							{
								node.select(false);
								node.select(true);
								while(node.getLevel() > 2)
								{
									node = node.getParent();
								}
								if(lvl2 == null)
									lvl2 = node;
							}
							
						}
						document.tempSelectedTree = temp;	
						document.lvl2ActiveNode = lvl2;

							jQuery('.ui-dialog').remove();
							jQuery('.ui-widget-overlay').remove();	
							jQuery('.dialog').remove();
					});
					
					
				},
				error:		function(result)/**/
				{
					//console.log(result);
				},
				complete: function(){
					
				}
			});
			
			

		});
    });
</script>