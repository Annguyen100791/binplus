<div style="float:left">
                    <b>Đơn vị chủ trì: &nbsp;
					<?php 
						echo $phong_chutri;
					?></b>
                    </div>
              		  <div style="float:left; padding-left:10px">
                      <?php					   					  
                      echo $this->Html->link('Thay đổi ', '/vanban/duyet_vb_dvchutri_edit/'.$vb['Vanban']['id'], array('title' => 'Click để thay đổi đơn vị chủ trì ','class'=>'button', 'escape' => false, 'data-mode' => 'ajax', 'data-action' => 'dialog'));
					  ?>
                     
                 	</div>
                    
                     <div style="clear:both"></div>
                 