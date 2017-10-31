<?php

	echo $this->Form->create('Nhanvien', array('id' => 'form-nhanvien-add'));

?>

<div style="padding:10px 50px">

<div class="content-box" id="nhanvien-add">

<!-- Start Content Box -->



	<!-- Begin .content-box-header -->				

    <div class="content-box-header">

        

        <h3>Thông tin Nhân viên <?php echo $this->data['Nhanvien']['full_name'] ?></h3>

        

        <ul class="content-box-tabs">

            <li><a href="#nhanvien-tab1" rel="#nhanvien-tab1" class="default-tab">Thông tin chung</a></li>

            <li><a href="#nhanvien-tab2" rel="#nhanvien-tab2">Thông tin tài khoản</a></li>

            <li><a href="#nhanvien-tab3" rel="#nhanvien-tab3">Quyền hạn trên hệ thống</a></li>

        </ul>

        

        <div class="clear"></div>

        

    </div> 

    <!-- End .content-box-header -->

    

    <div class="content-box-content">

        

		<div class="tab-content default-tab" id="nhanvien-tab1">

        	<div style="float:left; width:66%">

            <?php

				echo $this->Form->input('full_name', array('label'	=>	'Họ tên nhân viên ',

														  'id'		=>	'full_name',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));
								echo '<BR>';

				

				echo $this->Form->input('chucdanh_id', array('label'	=>	'Chức danh',

														  'id'		=>	'chucdanh_id',

														  'options'	=>	$chucdanh,

														  'empty'	=>	'',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('phong_id', array('label'	=>	'Thuộc đơn vị',

														  'id'		=>	'phong_id',

														  'style'	=>	'width:97.5%',

														  'options'	=>	$phong,

														  'class'	=>	'text-input'));
				echo '<BR>';				

				echo '<div class="input select">';

				echo '<label>Ngày sinh</label>';

				echo $this->Form->dateTime('ngay_sinh', 'DMY', null, array(

														  'label'	=>	false,

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


				

			?>
                        <?php

				

				echo $this->Form->input('dia_chi', array('label'	=>	'Địa chỉ',

														  'id'		=>	'dia_chi',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';
				echo'<div style="float:left; width:48%">';

				echo $this->Form->input('dien_thoai', array('label'	=>	'Điện thoại di động',

														  'id'		=>	'dien_thoai',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));
				echo '<BR>';

				
				echo '</div>';
				echo '<div style="float:right; width:48%;">';
				echo $this->Form->input('dien_thoai_noi_bo', array('label'	=>	'Điện thoại nội bộ',

														  'id'		=>	'dien_thoai_noi_bo',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));
				echo '<BR>';
				echo'</div>';				
				echo '<BR>';
				
				echo'<div style="float:left; width:48%">';

				echo $this->Form->input('dien_thoai_nha_rieng', array('label'	=>	'Điện thoại nhà riêng',

														  'id'		=>	'dien_thoai_nha_rieng',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				
				echo '<BR>';
				echo '</div>';				
				echo '<div style="float:right; width:48%">';
				echo $this->Form->input('so_meg', array('label'	=>	'Số MEG',

														  'id'		=>	'so_meg',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));
				echo '<BR>';
				echo'</div>';
				echo '<BR>';			

				echo $this->Form->input('tinh_trang', array('label'	=>	'Tình trạng công tác',

														  'id'		=>	'tinh_trang',

														  'options'	=>	$tinh_trang,

														  'style'	=>	'width:100%',

														  'empty'	=>	false));

				echo '<BR>';

				

			?>

            </div>

            <div style="float:right; width:30%">
           <?php

					if(!empty($this->data['Nhanvien']['anh_the']))

						echo $this->Html->image(Configure::read('NhanVien.avatar_path') . $this->data['Nhanvien']['anh_the'], array('width' => 220));

					else

						echo $this->Html->image('noimage.jpg', array('width' => 120));

				?>	

            </div>

            <div style="clear:both"></div>

		</div> 

      	<!-- End #tab1 -->

        

		<div class="tab-content" id="nhanvien-tab2">

        	<div style="float:left; width:48%">

        	<?php

				echo $this->Form->input('User.username', array('label'	=>	'Tên đăng nhập',

														  'id'		=>	'username',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('User.password', array('label'	=>	'Mật khẩu',

														  'id'		=>	'password',

														  'value'	=>	'',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				echo '<BR>';

				echo $this->Form->input('User.email', array('label'	=>	'Email',

														  'id'		=>	'email',

														  'style'	=>	'width:97.5%',

														  'class'	=>	'text-input'));

				

			?>

            </div>

            <div style="float:right; width:48%; text-align:center; padding-top:20px;">

            	<?php

					if(!empty($this->data['Nhanvien']['anh_the']))

						echo $this->Html->image(Configure::read('NhanVien.avatar_path') . $this->data['Nhanvien']['anh_the'], array('width' => 120));

					else

						echo $this->Html->image('noimage.jpg', array('width' => 120));

				?>

            </div>

            <div style="clear:both"></div>

        </div> 

        <!-- End #tab2 -->        

        

        <div class="tab-content" id="nhanvien-tab3">

        	<div style="width:49%; height:280px; float:left">

        	<?php

				echo $this->Form->input('Nhomquyen',

								array(

									  'label'	=>	'Nhóm quyền',

									  'div'		=>	true,

									  'id'		=>	'nhomquyen_id',

									  'style'	=>	'width:100%;height:250px',

									  'class'	=>	'text-input',

									  'multiple' => true,

									  'options'	=>	$nhomquyen

									  ));

			?>

            </div>

            <div style="width:49%; float:right">

            <label>Chi tiết quyền hạn :</label>

                <div style="height:250px; overflow:auto">

                <?php

					echo '<ul>';

                    foreach($quyen as $k=>$v)

					{

						echo '<ul>';

						echo '<li><b>' . $nhom_chucnang[$k] . '</b>';	

							echo '<ul>';

							foreach($v as $k1=>$v1)

								printf("<li>&nbsp; - %s</li>", $v1);

							echo '</ul>';

						echo '<li>';

						echo '</ul>';

					}

					echo '</ul>';

                ?>

                </div>

            </div>

            <div style="clear:both"></div>

            

        </div> 

        <!-- End #tab3 -->        

        

    </div> <!-- End .content-box-content -->

    <?php if($this->Session->read('Auth.User.nhanvien_id') == $this->data['Nhanvien']['id']): ?>

        <p style="text-align:right!important; padding-right:10px">

        	<a href="/nhanvien/profile" class="button" style="font-weight:bold" data-mode="ajax" data-action="dialog" data-width="400" title="Chỉnh sửa thông tin"><?php echo $this->Html->image('icons/user.png', array('align' => 'left')) ?>&nbsp; Chỉnh sửa thông tin</a>

        	<a href="/users/changepass" class="button" style="font-weight:bold" data-mode="ajax" data-action="dialog" data-width="400" title="Đổi mật khẩu"><?php echo $this->Html->image('icons/key.png', array('align' => 'left')) ?>&nbsp; Đổi mật khẩu</a>

            

        </p>

        <?php endif; ?>

</div>

<?php	

	echo $this->Form->end();

?>            

</div>