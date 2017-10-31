<!-- Xem chi tiết Công việc PAGE -->

<div data-role="page" id="congviec-chitiet">
	<div data-role="header" data-ajax="false">
		<h1>Xem chi tiết công việc</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" class="ui-btn-active" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div class="content-primary">
        
        	<!-- collapsible CONTENT -->
            
    		<div data-role="collapsible" data-theme="b" data-content-theme="c" data-collapsed="false">
            	<h3>Công việc chính</h3>
                <ul data-role="listview">
                	<li style="font-weight:normal"><strong>Tên công việc: </strong><?php echo $data['Congviec']['ten_congviec'] ?></li>
                    <li style="font-weight:normal"><strong>Nội dung: </strong><?php echo $data['Congviec']['noi_dung'] ?></li>
                    <li style="font-weight:normal"><strong>Thời gian thực hiện: </strong> từ ngày <?php echo $this->Time->format('d-m-Y', $data['Congviec']['ngay_batdau']) . ' đến ngày ' . $this->Time->format('d-m-Y', $data['Congviec']['ngay_ketthuc']) ?></li>
                    <?php
						if(!empty($data['Vanban'])):
					?>
                    <li style="font-weight:normal"><strong>Theo văn bản: </strong><?php printf("%s - %s, ngày phát hành: %s, nơi phát hành: %s", $data['Vanban']['so_hieu'], $data['Vanban']['trich_yeu'], $this->Time->format('d-m-Y', $data['Vanban']['ngay_phathanh']), $data['Vanban']['noi_gui']) ?></li>
                    <?php
						endif;
					?>
                    <li style="font-weight:normal"><strong>Nhân viên chịu trách nhiệm chính: </strong><?php echo $data['NguoiChiutrachnhiem']['full_name'] ?></li>
                    <li style="font-weight:normal">
                        <?php 
							if($data['Congviec']['nv_chiutrachnhiem'] == $this->Session->read('Auth.User.nhanvien_id') && $data['Congviec']['mucdo_hoanthanh'] < 10): 
						?>
                        <a href="javascript:updateMainTask(<?php echo $data['Congviec']['id'] ?>)" title="Cập nhật mức độ hoàn thành">
                        	<strong>Mức độ hoàn thành: </strong>
                        	<span class="ui-li-count" style="font-size:12pt"><?php echo $data['Congviec']['mucdo_hoanthanh']*10; ?>%</span>
                        </a>
                        <?php else:?>
                        	<strong>Mức độ hoàn thành: </strong>
                        	<span class="ui-li-count" style="font-size:12pt"><?php echo $data['Congviec']['mucdo_hoanthanh']*10; ?>%</span>
                        <?php endif;?>
                    </li>
                    
                </ul>
            </div>
            <!-- END -->
            
            <!-- begin SUB-->
            <?php if(!empty($detail)):?>
            <div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="c">
            	<h3>Tiến trình thực hiện công việc</h3>
                <ul data-role="listview" data-theme="d" data-divider-theme="d">
                <?php
					foreach($detail as $d):
					$padding_left =  $d['Chitietcongviec']['padding']*2;
				?>
                	<li <?php echo !empty($padding_left) ? $padding_left : '' ?>>
                    	<?php
							if($d['Chitietcongviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id') && $d['Chitietcongviec']['mucdo_hoanthanh'] < 10):
						?>
                        <a href="javascript:updateTask(<?php echo $d['Chitietcongviec']['id'] . ',' . $d['Chitietcongviec']['mucdo_hoanthanh']*10 ?>)" title="Cập nhật mức độ hoàn thành">
                        <?php
							endif;
						?>
                    	<h3><?php echo $d['Chitietcongviec']['ten_congviec'] ?></h3>
                        <p><?php echo $d['Chitietcongviec']['noi_dung'] ?></p>
                        <p>Người giao việc: <strong><?php echo $d['NguoiGiaoviec']['full_name'] ?></strong>, Người nhận việc: <strong><?php echo $d['NguoiNhanviec']['full_name']?></strong></p>
                        <p>Từ ngày <strong><?php echo $this->Time->format('d-m-Y', $d['Chitietcongviec']['ngay_batdau']) ?></strong> đến ngày <strong><?php echo $this->Time->format('d-m-Y', $d['Chitietcongviec']['ngay_ketthuc']) ?></strong></p>
                        
                       	<span class="ui-li-count" style="font-size:12pt"><?php echo $d['Chitietcongviec']['mucdo_hoanthanh']*10; ?>%</span>
                        <?php
							if($d['Chitietcongviec']['nguoinhan_id'] == $this->Session->read('Auth.User.nhanvien_id')):
						?>
                        </a>
                        <?php
							endif;
						?>
                    </li>
                <?php
					endforeach;
				?>
                </ul>
            </div>
            <?php endif;?>
            <!-- -->
        </div>
    </div>
    
    <div data-role="footer">
		<div data-role="controlgroup" data-type="horizontal" id="pagination">
        	<button type="button" id="congviec-dagiao-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="congviec-dagiao-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>


<div data-role="dialog" id="congviec-update-maintask" data-title="Cập nhật mức độ hoàn thành">
	<div data-role="header" data-theme="d">
    	<h1>Cập nhật mức độ hoàn thành</h1>
    </div>
    <div data-role="content" data-ajax="false">
    	<p>Chọn mức độ hoàn thành công việc</p>
        <form id="update-maintask-form">
        	<ul data-role="listview" data-inset="true">
            	<li data-role="fieldcontain">
                    <input type="range" name="data[maintask-mucdohoanthanh]" id="maintask-mucdohoanthanh" value="<?php echo $data['Congviec']['mucdo_hoanthanh']*10 ?>" min="0" max="100"  step="10" />
                </li>
                <li class="ui-body ui-body-b">
                    <fieldset class="ui-grid-a">
                        <div class="ui-block-a"><button type="button" id="maintask-cancel" data-theme="d" data-rel="back">Cancel</button></div>
                        <div class="ui-block-b"><button type="button" id="maintask-submit" data-theme="a">Submit</button></div>
                    </fieldset>
                </li>
            </ul>
        </form>
    </div>
</div>

<div data-role="dialog" id="congviec-update-task" data-title="Cập nhật mức độ hoàn thành">
	<div data-role="header" data-theme="d">
    	<h1>Cập nhật mức độ hoàn thành</h1>
    </div>
    <div data-role="content" data-ajax="false">
    	<p>Chọn mức độ hoàn thành công việc</p>
        <form id="update-task-form">
        	<ul data-role="listview" data-inset="true">
            	<li data-role="fieldcontain">
                    <input type="range" name="data[task-mucdohoanthanh]" id="task-mucdohoanthanh" value="0" min="0" max="100"  step="10" />
                </li>
                <li class="ui-body ui-body-b">
                    <fieldset class="ui-grid-a">
                        <div class="ui-block-a"><button type="button" id="task-cancel" data-theme="d" data-rel="back">Cancel</button></div>
                        <div class="ui-block-b"><button type="button" id="task-submit" data-theme="a">Submit</button></div>
                    </fieldset>
                </li>
            </ul>
        </form>
    </div>
</div>