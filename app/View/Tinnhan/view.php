<!--  start page-heading -->

<div id="page-heading">

	<div style="float:left"><h1>Xem chi tiết tin nhắn</h1></div>

    <div style="float:right;padding-right: 20px">

    	<?php 

		if(($this->request->data['Tinnhan']['chuyen_tiep']) && $ds_nguoinhan > 1)

		{

		?>

            <div style="float:right;padding-right: 20px">

                <a href="/tinnhan/compose/replyto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời</a>

                <a href="/tinnhan/compose/replytoall:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời tất cả</a>

                <a href="/tinnhan/compose/forwardto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để chuyển tiếp tin nhắn này cho người khác">Chuyển tiếp</a>

                <a href="/tinnhan/" id="btn-tinnhan-delete" data-id="<?php echo $this->data['Chitiettinnhan']['id'] ?>" class="button" title="Click để xóa tin nhắn này">Xóa</a>

                <a href="/tinnhan/" class="button" title="Click để chuyển sang mục Quản lý tin nhắn">Xem danh sách</a>

            </div>

		<?php

		}

        elseif(($this->request->data['Tinnhan']['chuyen_tiep']) && $ds_nguoinhan = 1)

		{

		?>

			<div style="float:right;padding-right: 20px">

               <a href="/tinnhan/compose/replyto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời</a>

               <a href="/tinnhan/compose/forwardto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để chuyển tiếp tin nhắn này cho người khác">Chuyển tiếp</a>

               <a href="/tinnhan/" id="btn-tinnhan-delete" data-id="<?php echo $this->data['Chitiettinnhan']['id'] ?>" class="button" title="Click để xóa tin nhắn này">Xóa</a>

               <a href="/tinnhan/" class="button" title="Click để chuyển sang mục Quản lý tin nhắn">Xem danh sách</a>

            </div>

        <?php

		}

		elseif(($this->request->data['Tinnhan']['chuyen_tiep']) == false && $ds_nguoinhan > 1)

		{

		?>

        	<div style="float:right;padding-right: 20px">

            	<a href="/tinnhan/compose/replyto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời</a>

                <a href="/tinnhan/compose/replytoall:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời tất cả</a>

                <a href="/tinnhan/" id="btn-tinnhan-delete" data-id="<?php echo $this->data['Chitiettinnhan']['id'] ?>" class="button" title="Click để xóa tin nhắn này">Xóa</a>

                <a href="/tinnhan/" class="button" title="Click để chuyển sang mục Quản lý tin nhắn">Xem danh sách</a>

            </div>

		<?php	

		}

		elseif(($this->request->data['Tinnhan']['chuyen_tiep']) == false && $ds_nguoinhan = 1)

		{

		?>	

			<div style="float:right;padding-right: 20px">

            	<a href="/tinnhan/compose/replyto:<?php echo $this->data['Tinnhan']['id'] ?>" class="button" title="Click để trả lời tin nhắn này">Trả lời</a>

                <a href="/tinnhan/" id="btn-tinnhan-delete" data-id="<?php echo $this->data['Chitiettinnhan']['id'] ?>" class="button" title="Click để xóa tin nhắn này">Xóa</a>

                <a href="/tinnhan/" class="button" title="Click để chuyển sang mục Quản lý tin nhắn">Xem danh sách</a>

            </div>

		<?php

        }

		?>

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

    <div id="content-table-inner" style="padding:20px">

		<?php

			echo $this->Form->create('Chitiettinnhan');

		?>

        <table width="95%" cellpadding="4" cellspacing="4" style="line-height:20px">

        	<tr>

            	<td width="150px"><label>Người gửi : </label></td>

                <td><?php echo $this->Html->link($this->data['Tinnhan']['Nguoigui']['full_name'], '/nhanvien/view/' . $this->data['Tinnhan']['nguoigui_id'], array('title' => 'Xem chi tiết nhân viên')); ?></td>

            </tr>

            <?php if(count($this->data['Tinnhan']['Chitiettinnhan']) > 1): ?>

            <tr>

            	<td><label>Người nhận : </label></td>

                <td>

                <?php

					echo $this->Html->link('Nhiều người', '/tinnhan/view_nguoinhan/' . $this->data['Tinnhan']['id'], array('title' => 'Xem danh sách người nhận', 'data-mode' => 'ajax', 'data-action' => 'dialog'));

				?>

                </td>

            </tr>

            <?php endif;?>

            <tr>

            	<td><label>Ngày gửi : </label></td>

                <td>

                	<?php

						echo $this->Time->format("d-m-Y H:i:s", $this->data['Tinnhan']['ngay_gui']);

					?>

                </td>

            </tr>

            <tr>

            	<td><label>Tiêu đề : </label></td>

                <td>

                	<?php

						echo $this->data['Tinnhan']['tieu_de']

					?>

                </td>

            </tr>

            <tr>

            	<td style="vertical-align:top"><label>Nội dung : </label></td>

                <td>

                <div id="message-content">

                   <?php

					echo $this->data['Tinnhan']['noi_dung']

				?>

                </div>

                </td>

            </tr>
             <?php if(!empty($this->data['Tinnhan']['noi_dung_capnhat'])){
			?>
            <tr>
            	<td style="vertical-align:top"><label style="color:#009"><b>Nội dung cũ:</b> </label></td>
                <td>
                <div id="message-content" style="color:#009">
                   <?php
					echo $this->data['Tinnhan']['noi_dung_capnhat']
				?>
                </div>
                </td>
            </tr>
            <?php
			} ?>

            <tr style="color:#F00">

            	<?php 

					if($this->data['Tinnhan']['chuyen_tiep'] <> 1)

					{

				?>

                <td style="vertical-align:top"><label>Lưu ý : </label></td>

                <td>

                <div>

                   <?php

					echo "Tin nhắn có đánh dấu không chuyển tiếp!"

				?>

                </div>

                </td>

            	<?php } ?>

            </tr>

            <tr>

            	<td></td>

               </tr>

            <?php if(!empty($this->data['Tinnhan']['FileTinnhan'])): ?>

            <tr>

            	<td valign="top"><label>File đính kèm : </label></td>

                <td style="padding:5px 0">

                <?php

					foreach($this->data['Tinnhan']['FileTinnhan'] as $file):

						

						//pr($file);die();

						/*echo $this->Html->link($file['file_path'], Configure::read('TinNhan.attach_path') . $file['file_path'], array('target' => '_blank'));*/

						echo $this->Html->link($file['file_path'], 'get_files/' . $file['id']);

						echo '<BR>';

					endforeach;

				?>

                </td>

            </tr>

            <?php endif;?>

				<?php

                  //if(!empty($related_messages) && $chuyen_tiep = 1 ):

				  if(!empty($related_messages)):

                ?>

    				 <tr>

                     <td><label>Các tin nhắn liên quan : </label></td>

                     <td></td>

                     </tr>

                     <?php

                        for( $i = 0; $i < count($related_messages); $i++ ):

                        if(isset($related_messages[$i+1]) && $related_messages[$i]['Tinnhan']['ngay_gui'] == $related_messages[$i+1]['Tinnhan']['ngay_gui'])

							continue;

                     ?>

                    <tr>

                        <td style="vertical-align:top; text-align:right; padding-top:10px"><strong><?php echo $this->Html->link($related_messages[$i]['Nguoigui']['full_name'], '/nhanvien/view/' . $related_messages[$i]['Tinnhan']['nguoigui_id']) ?></strong></td>

                        <td>

                        

                            <div class="bbcode_container">

                                <div class="bbcode_quote">

                                    <div class="bbcode_quote_container"></div>

                                    <div class="bbcode_postedby">

                                        <div style="float:left"><img src="/img/icons/quote_icon.png" /> <strong><?php echo $related_messages[$i]['Tinnhan']['tieu_de'] ?> </strong> 

                                        </div> 

                                        <div style="float:right; font-weight:10px; color:#666666; padding-right:10px"><?php echo $this->Time->format('H:i:s d-m-Y', $related_messages[$i]['Tinnhan']['ngay_gui']) ?></div>

                                        <div style="clear:both"></div>

                                    </div>

                                    <div class="message message-hide">

                                        <?php 

                                            echo $related_messages[$i]['Tinnhan']['noi_dung']

                                        ?>

                                    </div>

                                </div>

                            </div>

                        

                        </td>

                    </tr>

                    <?php

                        endfor;

                        endif;

					?>

        </table>

		</form>

        <script type="text/javascript">

        	jQuery(document).ready(function(){

				jQuery(".bbcode_quote").on("click",function(e){

					var container = jQuery(this).parent(".bbcode_container");

					var message = container.find(".message");	

					if(message.hasClass("message-hide"))

					{

						message.removeClass("message-hide").addClass("message-show");

					}

					else if (message.hasClass("message-show"))

					{

						message.removeClass("message-show").addClass("message-hide");

					}

				});	

			});

        </script>

        <style>

			.bbcode_quote {cursor:pointer;}

			.message-hide {max-height: 0px;overflow: hidden;padding: 0px !important;}

			.message-show {max-height: none;overflow:auto;padding:10px;}

		</style>

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