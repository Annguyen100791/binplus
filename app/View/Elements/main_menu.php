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

					if($this->Layout->check_permission('VanBan.VBden'))

					echo '<li>' . $this->Html->link('<span id="mnu-vanban-vbden">Văn bản đến</span>', '/vanban/index_vanbanden', array('title' => 'văn bản đến', 'escape' => false));

					echo '<ul class="secondary-sub">';

						if($this->Layout->check_permission('VanBan.xulyVBden'))

							echo '<li>' . $this->Html->link('<span id="mnu-vanban-vbden-xl">Xử lý</span>', '/vanban/index_vanbanden', array('title' => 'Xử lý văn bản đến', 'escape' => false)) . '</li>';

						if($this->Layout->check_permission('VanBan.tracuu_donvi'))

							echo '<li>' . $this->Html->link('<span id="mnu-vanban-timkq">Tra cứu văn bản theo đơn vị chủ trì</span>', '/vanban/search_kq', array('title' => 'Tra cứu văn bản theo đơn vị chủ trì', 'escape' => false)) . '</li>';

						if($this->Layout->check_permission('VanBan.TreTienDo'))

							echo '<li>' . $this->Html->link('<span id="mnu-vanban-timkq">Trễ tiến độ</span>', '/vanban/index_notice#notice-gap', array('title' => 'Tra cứu văn bản trễ tiến độ', 'escape' => false)) . '</li>';

					echo '</ul></li>'; 

				?>

                </ul>

            </div>

        </li>

    </ul>

    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-congviec">Công việc</span></a>    <!--[if lte IE 6]><table><tr><td><![endif]-->

    <div class="select_sub">

        <ul class="sub">

        	<?php

				if($this->Layout->check_permission('CongViec.thuchien'))

					echo '<li>' . $this->Html->link('<span id="mnu-congviec-duocgiao">Việc được giao</span>', '/congviec/duocgiao/', array('title' => 'Xem danh sách công việc được giao', 'escape' => false)) . '</li>';
					
				if($this->Layout->check_permission('CongViec.khoitao'))

					echo '<li>' . $this->Html->link('<span id="mnu-congviec-dagiao">Việc đã giao</span>', '/congviec/dagiao/', array('title' => 'Xem các công việc đã giao', 'escape' => false)) . '</li>';
				
				if($this->Layout->check_permission('CongViec.khoitao'))

					echo '<li>' . $this->Html->link('<span id="mnu-congviec-nhap">Khởi tạo công việc</span>', '/congviec/add/', array('title' => 'Khởi tạo công việc', 'escape' => false)) . '</li>';
				

				if($this->Layout->check_permission('CongViec.tracuu'))

					echo '<li>' . $this->Html->link('<span id="mnu-congviec-tim">Tra cứu công việc</span>', '/congviec/search/', array('title' => 'Tìm kiếm công việc đã giao và được giao', 'escape' => false)) . '</li>';

				//if($this->Layout->check_permission('VanBan.tracuu_donvi'))

						//echo '<li>' . $this->Html->link('<span id="mnu-vanban-timkq">Tra cứu văn bản theo đơn vị chủ trì</span>', '/vanban/search_kq', array('title' => 'Tra cứu văn bản theo đơn vị chủ trì', 'escape' => false)) . '</li>';

			?>

        </ul>

    </div>

    </li>

    </ul>





    <ul class="select"><li><a href="javascript:void(0)" class="nogo"><span id="mnu-email">Thông báo, Trao đổi</span></a>

    <div class="select_sub">

        <ul class="sub">

        	<?php

				if($this->Layout->check_permission('TinNhan.danhsach'))

					echo '<li>' . $this->Html->link('<span id="mnu-email-quanly">Danh sách</span>', '/tinnhan/index', array('title' => 'Danh sách thông tin trao đổi, thảo luận trực tiếp', 'escape' => false)) . '</li>';

				if($this->Layout->check_permission('TinNhan.soanthao'))

					echo '<li>' . $this->Html->link('<span id="mnu-email-nhap">Soạn thảo</span>', '/tinnhan/compose', array('title' => 'Soạn thảo tin nhắn', 'escape' => false)) . '</li>';

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

        <li><a href="javascript:void(0)" class="nogo"><span id="mnu-baocao">Báo cáo</span></a>

            <div class="select_sub">

                <ul class="sub">

                <?php

					if($this->Layout->check_permission('BaoCao.xem'))

						echo '<li>' . $this->Html->link('<span id="mnu-baocao-nhan">Văn bản thuộc loại báo cáo</span>', '/baocao/index', array('title' => 'Xem danh sách tất cả văn bản', 'escape' => false)) . '</li>';

					if($this->Layout->check_permission('BaoCao.theodoi'))

						echo '<li>' . $this->Html->link('<span id="mnu-baocao-quantrong">Văn bản quan trọng</span>', '/baocao/vtdn_theodoi', array('title' => 'Văn bản quan trọng (đang theo dõi)', 'escape' => false)) . '</li>';

					if($this->Layout->check_permission('BaoCao.gui'))

						echo '<li>' . $this->Html->link('<span id="mnu-baocao-nhap">Nhập văn bản thuộc thể loại báo cáo</span>', '/vanban/add_baocao', array('title' => 'Nhập văn vản thuộc thể loại báo cáo', 'escape' => false)) . '</li>';



				?>

                </ul>

            </div>

        </li>

    </ul>

    <?php endif; ?>



    <?php if($this->Layout->check_group_permission('ThongKe')): ?>

    	<ul class="select">

        	<li>

            	<a href="javascript:void(0)" class="nogo"><span id="mnu-report">Thống kê</span></a>

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

    <?php if($this->Layout->check_group_permission('360') || $this->Layout->check_group_permission('OB')): ?>

    <ul class="select"><li><a href="javascript:void(0)"><span id="mnu-360">360 Đà Nẵng</span></a>

    <div class="select_sub">

        <ul class="sub">

            <?php

                if($this->Layout->check_permission('360.DanhGia'))

                    echo '<li>' . $this->Html->link('<span id="mnu-360-danhgia">Đánh giá 360</span>', '/dg360/index', array('title' => 'Đánh giá 360', 'escape' => false)) . '</li>';

                if($this->Layout->check_permission('360.DanhGia'))

                    echo '<li>' . $this->Html->link('<span id="mnu-360-xem">Xem đánh giá</span>', '/dg360/danhgia', array('title' => 'Xem đánh giá', 'escape' => false)) . '</li>';

                if($this->Layout->check_permission('360.DanhMuc'))

                    echo '<li>' . $this->Html->link('<span id="mnu-360-config">Danh mục 360</span>', 'http://203.210.240.102:5012/', array('title' => 'Danh mục', 'escape' => false,  'target' => '_blank')) . '</li>';

                if($this->Layout->check_permission('360.BaoCao'))

                    echo '<li>'.$this->Html->link('<span id="mnu-360-config">Báo cáo 360</span>','http://203.210.240.102:5012/default.aspx#ViewID=ReportDataV2_ListView&ObjectClassName=DevExpress.Persistent.BaseImpl.ReportDataV2',array('title' => 'Báo cáo','escape' => false, 'target' => '_blank')) .'</li>';

				//////

					if($this->Layout->check_group_permission('OB')):

					echo '<li>' . $this->Html->link('<span id="mnu-ob-ttdh">OB đánh giá TTĐH</span>', '#', array('title' => 'OB đánh giá TTĐH', 'escape' => false));

					echo '<ul class="secondary-sub">';

						if($this->Layout->check_permission('OB.danhgia'))

							echo '<li>' . $this->Html->link('<span id="mnu-ob-danhgia">Đánh giá</span>', '/dg360/ob_ttdh', array('title' => 'Đánh giá TTĐH', 'escape' => false)) . '</li>';

						if($this->Layout->check_permission('OB.xemketqua'))

							echo '<li>' . $this->Html->link('<span id="mnu-ob-xem">Xem kết quả đã đánh giá</span>', '/dg360/view_result', array('title' => 'Xem kết quả đã đánh giá', 'escape' => false)) . '</li>';

						if($this->Layout->check_permission('OB.satis')) 

							echo '<li>' . $this->Html->link('<span id="mnu-ob-baocao">Báo cáo độ hài lòng</span>', '/dg360/ob_satis', array('title' => 'Báo cáo độ hài lòng', 'escape' => false)) . '</li>';

						if($this->Layout->check_permission('OB.unsatis'))

							echo '<li>' . $this->Html->link('<span id="mnu-ob-baocao">Báo cáo các lý do không hài lòng</span>', '/dg360/ob_unsatis', array('title' => 'Báo cáo các lý do không hài lòng', 'escape' => false)) . '</li>';

					echo '</ul></li>'; 

					endif; 

					////////

      ?>

        </ul> 

    </div>

    </li>

    </ul>

    

    <?php endif; ?>

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

