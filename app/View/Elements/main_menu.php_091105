<div class="table" id="menu-bar">
		
    <ul class="select">
    	<li><a href="javascript:void(0)" class="nogo"><span id="mnu-home">Trang chủ</span></a>
            <div class="select_sub">
                <ul class="sub">
                    <li><a href="/"><span id="mnu-home-dashboard">Hôm nay, ngày <?php echo date('d-m-Y') ?></span></a></li>
                </ul>
            </div>
    	</li>
    </ul>
    
    <ul class="select">
        <li><a href="javascript:void(0)" class="nogo"><span id="mnu-vanban">Văn bản</span></a>
            <div class="select_sub">
                <ul class="sub">
                <?php
					if($this->Layout->check_permission(array('VanBan.quanly', 'VanBan.nhan')))
						echo '<li>' . $this->Html->link('<span id="mnu-vanban-nhan">Nhận văn bản</span>', '/vanban/index', array('title' => 'Xem danh sách văn bản gửi cần đọc', 'escape' => false)) . '</li>';
					
					if($this->Layout->check_permission(array('VanBan.quanly', 'VanBan.gui')))
						echo '<li>' . $this->Html->link('<span id="mnu-vanban-nhap">Nhập mới văn bản</span>', '/vanban/add', array('title' => 'Nhập và gửi văn bản', 'escape' => false)) . '</li>';
						
					if($this->Layout->check_permission(array('VanBan.quanly', 'VanBan.sua')))
						echo '<li>' . $this->Html->link('<span id="mnu-vanban-sua">Chỉnh sửa văn bản</span>', '/vanban/sua', array('title' => 'Tìm kiếm và chỉnh sửa văn bản đã gửi', 'escape' => false)) . '</li>';
						
					if($this->Layout->check_permission(array('VanBan.quanly', 'VanBan.tracuu')))
						echo '<li>' . $this->Html->link('<span id="mnu-vanban-tim">Tra cứu văn bản</span>', '/vanban/search', array('title' => 'Tìm kiếm văn bản', 'escape' => false)) . '</li>';
				?>
                </ul>
            </div>
        </li>
    </ul>
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-congviec">Công việc</span></a>    <!--[if lte IE 6]><table><tr><td><![endif]-->
    <div class="select_sub">
        <ul class="sub">
        	<?php
				if($this->Layout->check_permission('CongViec.khoitao'))
					echo '<li>' . $this->Html->link('<span id="mnu-congviec-nhap">Khởi tạo công việc</span>', '/congviec/add/', array('title' => 'Khởi tạo công việc', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('CongViec.khoitao'))
					echo '<li>' . $this->Html->link('<span id="mnu-congviec-dagiao">Việc đã giao</span>', '/congviec/dagiao/', array('title' => 'Xem các công việc đã giao', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('CongViec.thuchien'))
					echo '<li>' . $this->Html->link('<span id="mnu-congviec-duocgiao">Việc được giao</span>', '/congviec/duocgiao/', array('title' => 'Xem danh sách công việc được giao', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('CongViec.tracuu'))
					echo '<li>' . $this->Html->link('<span id="mnu-congviec-tim">Tra cứu công việc</span>', '/congviec/search/', array('title' => 'Tìm kiếm công việc đã giao và được giao', 'escape' => false)) . '</li>';
			?>
        </ul>
    </div>
    </li>
    </ul>
    
    
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-email">Trao đổi, chỉ đạo</span></a>
    <div class="select_sub">
        <ul class="sub">
        	<?php
				if($this->Layout->check_permission('TinNhan.danhsach'))
					echo '<li>' . $this->Html->link('<span id="mnu-email-quanly">Danh sách thông tin</span>', '/tinnhan/index', array('title' => 'Danh sách thông tin chỉ đạo, trao đổi trực tiếp', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('TinNhan.soanthao'))
					echo '<li>' . $this->Html->link('<span id="mnu-email-nhap">Soạn thảo thông tin</span>', '/tinnhan/compose', array('title' => 'Soạn thảo tin nhắn', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('ThongBao.xem'))
					echo '<li>' . $this->Html->link('<span id="mnu-notice">Thông tin mới</span>', '/tintuc/index', array('title' => 'Thông tin mới', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('ChinhSach.xem'))
					echo '<li>' . $this->Html->link('<span id="mnu-vanban-sua">Chính sách mới</span>', '/chinhsach/index', array('title' => 'Thông tin mới', 'escape' => false)) . '</li>';
			?>
        </ul>
    </div>
    </li>
    </ul>
    
    
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-lich">Lịch</span><!--[if IE 7]><!--></a><!--<![endif]-->
    <div class="select_sub">
        <ul class="sub">
        	<?php
				/*
				if($this->Layout->check_permission('LichCongTac.danhsach'))
					echo '<li>' . $this->Html->link('<span id="mnu-lich-tatca">Xem lịch</span>', '/calendars/index', array('title' => 'Xem toàn bộ lịch', 'escape' => false)) . '</li>';
				(\*/
				if($this->Layout->check_permission('LichCongTac.xem_lichcongtac'))
					echo '<li>' . $this->Html->link('<span id="mnu-lich-congtac">Lịch công tác</span>', '/calendars/lamviec', array('title' => 'Lịch công tác', 'escape' => false)) . '</li>';
				/*
				if($this->Layout->check_permission('LichCongTac.xem_lichlamviec'))
					echo '<li>' . $this->Html->link('<span id="mnu-lich-lamviec">Lịch làm việc</span>', '/calendars/lamviec', array('title' => 'Lịch làm việc', 'escape' => false)) . '</li>';
				*/
					
			?>
        </ul>
    </div>
    </li>
    </ul>
    
    
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-nhanvien">Nhân sự</span></a>
    <div class="select_sub">
        <ul class="sub">
        	<?php
				if($this->Layout->check_permission('NhanSu.danhsach'))
					echo '<li>' . $this->Html->link('<span id="mnu-nhanvien-ds">Danh sách cán bộ / nhân viên</span>', '/nhanvien/nhansu/', array('title' => 'Danh sách cán bộ / nhân viên', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('LichCongTac.birthday'))
					echo '<li>' . $this->Html->link('<span id="mnu-birthday">Danh sách sinh nhật</span>', '/calendars/birthday', array('title' => 'Danh sách sinh nhật', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('NhanSu.online'))
					echo '<li>' . $this->Html->link('<span id="mnu-nhanvien-online">Cán bộ / nhân viên đang online</span>', '/nhanvien/online', array('title' => 'Danh sách sinh nhật', 'escape' => false)) . '</li>';

			?>
        </ul>
    </div>
    </li>
    </ul>
    <?php if($this->Layout->check_group_permission('BaoCao')): ?>
    	<ul class="select">
        	<li>
            	<a href="javascript:void(0)" class="nogo"><span id="mnu-report">Báo cáo</span></a>
                <div class="select_sub">
                	<ul class="sub">
                    <?php
						echo '<li>' . $this->Html->link('<span id="mnu-report-signin">Truy cập hệ thống toàn Viễn thông </span>', '/report/signin', array('title' => 'Thống kê số lần truy cập website của toàn Viễn thông', 'escape' => false)) . '</li>';
						echo '<li>' . $this->Html->link('<span id="mnu-report-signin-nhanvien">Truy cập hệ thống của Nhân viên </span>', '/report/signin_nhanvien', array('title' => 'Thống kê chi tiết việc truy cập website của Nhân viên', 'escape' => false)) . '</li>';
					?>
                    </ul>
                </div>
            </li>
        </ul>
    <?php endif;?>
    <?php if($this->Layout->check_group_permission('HeThong')): ?>
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-admin">Hệ thống</span></a>
    <div class="select_sub">
        <ul class="sub">
        	<?php
				if($this->Layout->check_permission('HeThong.nhanvien'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-nhanvien">Nhân sự</span>', '/nhanvien/index', array('title' => 'Quản lý nhân sự', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('HeThong.phong'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-donvi">Phòng/ Đơn vị</span>', '/phong/index', array('title' => 'Danh sách phòng/ đơn vị', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('HeThong.chucdanh'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-chucdanh">Chức danh</span>', '/chucdanh/index', array('title' => 'Danh sách chức danh', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('HeThong.quyen'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-quyen">Đặc quyền</span>', '/quyen/index', array('title' => 'Danh sách đặc quyền', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('HeThong.nhomquyen'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-nhomquyen">Nhóm đặc quyền</span>', '/nhomquyen/index', array('title' => 'Danh sách nhóm đặc quyền', 'escape' => false)) . '</li>';
			?>
        </ul>
    </div>
    </li>
    </ul>
    
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-danhmuc">Danh mục</span></a>
    <div class="select_sub">
        <ul class="sub">
            <?php
				if($this->Layout->check_permission('HeThong.loaivanban'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-loaivanban">Loại văn bản</span>', '/loaivanban/index', array('title' => 'Danh mục loại văn bản', 'escape' => false)) . '</li>';
				/*
				if($this->Layout->check_permission('HeThong.tinhchatvanban'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-tinhchatvanban">Tính chất văn bản</span>', '/tinhchatvanban/index', array('title' => 'Danh mục tính chất văn bản', 'escape' => false)) . '</li>';
				*/
				if($this->Layout->check_permission('HeThong.tinhchatcongviec'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-tinhchatcongviec">Tính chất công việc</span>', '/tinhchatcongviec/index', array('title' => 'Danh mục tính chất công việc', 'escape' => false)) . '</li>';
				if($this->Layout->check_permission('HeThong.tinhchatcongtac'))
					echo '<li>' . $this->Html->link('<span id="mnu-admin-tinhchatcongtac">Tính chất công tác</span>', '/tinhchatcongtac/index', array('title' => 'Danh mục tính chất công tác', 'escape' => false)) . '</li>';
			?>
        </ul>
    </div>
    </li>
    </ul>
    
    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-config">Cấu hình</span></a>
    <div class="select_sub">
        <ul class="sub">
        	<?php
			if($this->Layout->check_permission('HeThong.setting'))
				echo '<li>' . $this->Html->link('<span id="mnu-admin-config">Cấu hình website</span>', '/settings/prefix/Site/', array('title' => 'Cấu hình website', 'escape' => false)) . '</li>';
			?>
        </ul>
    </div>
    </li>
    </ul>
    <?php endif;?>
    <div class="clear"></div>
    </div>
    <div class="clear"></div>