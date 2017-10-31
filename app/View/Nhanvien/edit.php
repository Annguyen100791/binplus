<?php

	echo $this->Html->script(

		array(

			  'libs/jquery/ajaxupload.3.5'

			  )

	);

	echo $this->Form->hidden('avatar_path', array('value' => Configure::read('NhanVien.avatar_path'), 'id' => 'avatar_path'));

	echo $this->Form->create('Nhanvien', array('id' => 'form-nhanvien-edit'));

	echo $this->Form->hidden('id');

	echo $this->Form->hidden('User.id');

?>

<div style="padding:10px 50px">

<div class="content-box" id="nhanvien-edit">

<!-- Start Content Box -->



	<!-- Begin .content-box-header -->				

    <div class="content-box-header">

        

        <h3>Hiệu chỉnh Nhân viên</h3>

        

        <ul class="content-box-tabs">

            <li><a href="#nhanvien-tab1" class="default-tab">Thông tin chung</a></li>

            <li><a href="#nhanvien-tab2">Thông tin tài khoản</a></li>

            <li><a href="#nhanvien-tab3">Gán quyền cho nhân viên</a></li>

        </ul>

        

        <div class="clear"></div>

        

    </div> 

    <!-- End .content-box-header -->

    

    <div class="content-box-content">

        

		<div class="tab-content default-tab" id="nhanvien-tab1">

        	<div style="float:left; width:48%">

        	<?php

				echo $this->Form->input('ho_ten', array('label'	=>	'Họ tên nhân viên <span style="color:red">*</span>',

														  'id'		=>	'ho_ten',

														  'style'	=>	'width:97.5%',

														  'value'	=>	$this->data['Nhanvien']['full_name'],

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo '<div class="input select">';

				echo '<label>Ngày sinh</label>';

				echo $this->Form->dateTime('ngay_sinh', 'DMY', null, array('label'	=>	false,

														  'id'		=>	'ngay_sinh', 'empty' => false,

														  'maxYear'	=>	date("Y"), 'minYear'	=>	1900));

				echo '</div>';

				echo '<BR>';

				echo $this->Form->input('gioi_tinh', array('label'	=>	'Giới tính',

														  'id'		=>	'gioi_tinh',

														  'options'	=>	$gioi_tinh,

														  'style'	=>	'width:97.5%',

														  'empty'	=>	''));

				echo '<BR>';

				

				echo $this->Form->input('chucdanh_id', array('label'	=>	'Chức danh <span style="color:red">*</span>',

														  'id'		=>	'chucdanh_id',

														  'options'	=>	$chucdanh,

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('phong_id', array('label'	=>	'Công tác tại phòng ban/ đơn vị <span style="color:red">*</span>',

														  'id'		=>	'phong_id',

														  'style'	=>	'width:97.5%',

														  'options'	=>	$phong,

														  'class'	=>	'text-input'));

				

			?>

            	<BR />

            	<label title="<b>Người quản lý</b> là người chịu trách nhiệm xử lý công việc của phòng/ đơn vị">Là người quản lý ?</label>

            <?php

				echo $this->Form->input('nguoi_quanly', array('label'	=>	false,

														  'id'		=>	'nguoi_quanly',

														  'options'	=>	array(0 => 'Không', 1 => 'Có'),

														  'style'	=>	'width:97.5%',

														  'empty'	=>	false));

			?>
            <label title="<b>Đang công tác</b>: được phép sự dụng hệ thống<BR><b>Nghỉ công tác</b>: không sử dụng hệ thống">Tình trạng công tác</label>

            <?php

				echo $this->Form->input('tinh_trang', array('label'	=>	false,

														  'id'		=>	'tinh_trang',

														  'options'	=>	$tinh_trang,

														  'style'	=>	'width:97.5%',

														  'empty'	=>	false));

			?>	

            </div>

            <div style="float:right; width:48%">

            <?php

				

				echo $this->Form->input('dia_chi', array('label'	=>	'Địa chỉ',

														  'id'		=>	'dia_chi',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';
				echo $this->Form->input('cho_o_hien_nay', array('label'	=>	'Chỗ ở hiện nay',

														  'id'		=>	'cho_o_hien_nay',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('dien_thoai', array('label'	=>	'Điện thoại',

														  'id'		=>	'dien_thoai',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('dien_thoai_noi_bo', array('label'	=>	'Điện thoại nội bộ',

														  'id'		=>	'dien_thoai_noi_bo',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('dien_thoai_nha_rieng', array('label'	=>	'Điện thoại nhà riêng',

														  'id'		=>	'dien_thoai_nha_rieng',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';
				echo $this->Form->input('so_meg', array('label'	=>	'Số MEG',

														  'id'		=>	'so_meg',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				

			?>	

            	

            </div>

            <div style="clear:both"></div>

		</div> 

      	<!-- End #tab1 -->

        

		<div class="tab-content" id="nhanvien-tab2">

        	<div style="float:left; width:48%">

            	<label title="<b>Tên đăng nhập</b> dùng đăng nhập hệ thống. Không được phép điều chỉnh">Tên đăng nhập<span style="color:red">*</span></label>

        	<?php

				echo $this->Form->input('User.username', array('label'	=>	false,

														  'id'		=>	'username',

														  'style'	=>	'width:97.5%; font-weight:bold',

														  'disabled'	=>	true,

														  'value'	=>	$this->data['User']['username'],

														  'class'	=>	'text-input'));

				echo '<BR>';

			?>

            	<label title="<b>Mật khẩu mới</b> nếu muốn đổi mật khẩu thì nhập cho mục này">Mật khẩu mới (ít nhất 6 ký tự)</label>

            <?php

				echo $this->Form->input('User.password1', array('label'	=>	false,

														  'id'		=>	'password1',

														  'style'	=>	'width:97.5%',

														  'type'	=>	'password',

														  'class'	=>	'text-input'));

				echo '<BR>';

			?>

            	<label title="<b>Xác nhận mật khẩu</b> với mật khẩu mới muốn đổi">Xác nhận mật khẩu</label>

            <?php

				echo $this->Form->input('User.password2', array('label'	=>	false,

														  'id'		=>	'password2',

														  'style'	=>	'width:97.5%',

														  'type'	=>	'password',

														  'class'	=>	'text-input'));

				echo '<BR>';

			?>

            	<label title="<b>Email</b> dùng để liên lạc, được dùng để nhận lại mật khẩu khi cần.">Email <span class="required">*</span></label>

            <?php

				echo $this->Form->input('User.email', array('label'	=>	false,

														  'id'		=>	'email',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				

			?>

            </div>

            <div style="float:right; width:48%; text-align:center; padding-top:20px;">

            	<div id="preview">

            		<?php

						if(!empty($this->data['Nhanvien']['anh_the']))

							echo $this->Html->image(Configure::read('NhanVien.avatar_path') . $this->data['Nhanvien']['anh_the']);

						else

							echo $this->Html->image('noimage.jpg', array('width' => 120));

					?>

                </div>

                <div style="padding-top:5px"><input class="button" type="button" id="btn-upload-image" value="Upload hình thẻ" /></div>

            </div>

            <div style="clear:both"></div>

        </div> 

        <!-- End #tab2 -->        

        

        <div class="tab-content" id="nhanvien-tab3">

        	<div style="width:100%; height:200px">

            <label title="<b>Quyền hạn</b> là những chức năng mà người sử dụng được phép trên hệ thống. <BR>Người sử dụng có thể có nhiều hơn 1 nhóm quyền">Chọn Nhóm quyền <span class="required">*</span> (Ctrl+click để chọn nhiều nhóm)</label>

        	<?php

				echo $this->Form->input('Nhomquyen',

								array(

									  'label'	=>	false,

									  'div'		=>	true,

									  'id'		=>	'nhomquyen_id',

									  'style'	=>	'width:100%;height:170px',

									  'class'	=>	'text-input',

									  'multiple' => true,

									  'options'	=>	$nhomquyen

									  ));

			?>

            </div>

        </div> 

        <!-- End #tab3 -->        

        

    </div> <!-- End .content-box-content -->

    

</div>

<p style="text-align:right!important"><input class="button" type="submit" value="Lưu thay đổi" /></p>

<?php	

	echo $this->Form->end();

?>            

</div>