<div data-role="page" id="tinnhan-compose">
	<div data-role="header" data-ajax="false">
		<h1>Soạn thảo Tin nhắn</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
                <li><a href="/mobile/calendars" data-icon="calendar">Lịch</a></li>
            </ul>
        </div>
	</div>
	<div data-role="content">	
    	<?php
			echo $this->Form->create('Tinnhan', array('id' => 'tinnhan-compose-form'));
		?>
    	<!-- SECTION MESSAGE CONTENT -->
		<div data-role="collapsible" data-content-theme="c" data-collapsed="false" data-mini="true">
            <h3>Nội dung </h3>
            	
                <div data-role="fieldcontain">
                    <label for="tieu_de" class="ui-hidden-accessible">Tiêu đề :</label>
                    <?php
                    	$title = '';
                        if(isset($tinnhan))
                        {
                            if( isset( $tinnhan['Tinnhan']['tieu_de'] ) )
                            {
                                if( isset( $tinnhan['Tinnhan']['nguoigui_id'] ) )	// reply
                                    $title = 'Trả lời: ' . $tinnhan['Tinnhan']['tieu_de'];
                                else
                                    $title = 'Forwarded: ' . $tinnhan['Tinnhan']['tieu_de'];
                                
                            }
                        }
                    ?>
                    <input type="text" name="data[Tinnhan][tieu_de]" id="tieu_de" required="required"  placeholder="Nhập vào tiêu đề tin nhắn" value="<?php  echo $title?>">
                </div>
                
                <div data-role="fieldcontain">
                    <label for="noi_dung" class="ui-hidden-accessible">Nội dung :</label>
                    <?php
                    	$content = (isset($tinnhan) && isset($tinnhan['Tinnhan']['noi_dung'])) ? $tinnhan['Tinnhan']['noi_dung'] : '';
                        //if(!empty($signature))
                        //    $content .= '<BR><p style="padding:20px 0">' . $signature . '</p>';
                    ?>
                    <textarea id="noi_dung" name="data[Tinnhan][noi_dung]" required="required"  placeholder="Nhập vào nội dung tin nhắn"><?php echo $content ?></textarea>
                </div>
                <button type="button" id="tinnhan-submit" data-theme="e" name="submit" value="submit-value">Gửi tin nhắn</button>
        </div>
        <!-- SECTION RECEIVER -->
        <div data-role="collapsible" data-content-theme="c" data-collapsed="false" data-mini="true">
        	<h3>Người nhận tin nhắn</h3>
            <?php
            	if(!empty($ds)):
			?>
            	<div data-role="fieldcontain">
                    <input type="checkbox" id="checkall" class="check-all" />
                    <label for="checkall">Chọn / bỏ chọn tất cả nhân viên</label>
                </div>
            <?php
				foreach($ds as $phong):
			?>
            	<div data-role="collapsible" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-inset="false" data-mini="true">
                	<h2><?php echo $phong['Phong']['ten_phong'] ?></h2>
                    <div data-role="fieldcontain">
                    	<input type="checkbox" id="checkall-<?php echo $phong['Phong']['id'] ?>" class="check-all" />
                        <label for="checkall-<?php echo $phong['Phong']['id'] ?>">Chọn / bỏ chọn tất cả nhân viên thuộc phòng</label>
                    </div>
                    <?php
						if(!empty($phong['Nhanvien'])):
							echo '<fieldset data-role="controlgroup">';
							foreach($phong['Nhanvien'] as $nhanvien):
						?>
                        	<input type="checkbox" name="data[Chitiettinnhan][][nguoinhan_id]" id="checkbox-<?php echo $nhanvien['Nhanvien']['id']?>" value="<?php echo $nhanvien['Nhanvien']['id']?>" class="custom" <?php echo (!empty($tinnhan) && isset($tinnhan['Tinnhan']['nguoigui_id']) && $nhanvien['Nhanvien']['id'] == $tinnhan['Tinnhan']['nguoigui_id']) ? 'checked' : '' ?> />
							<label for="checkbox-<?php echo $nhanvien['Nhanvien']['id']?>" title="Click để chọn người này"><?php echo $nhanvien['Nhanvien']['full_name'] ?></label>
                        <?php
							endforeach;
							echo '</fieldset>';
						endif;
					?>
                </div>
            <?php
				endforeach;
				endif;
            ?>
        </div>
        </form>
	</div>
	<div data-role="footer">
		<h4><?php echo Configure::read('Mobile.footer') ?></h4>
	</div>
</div>