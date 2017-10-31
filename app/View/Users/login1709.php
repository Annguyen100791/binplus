<!--  start loginbox ................................................................................. -->
	<div id="loginbox">
	
	<!--  start login-inner -->
	<div id="login-inner">
    <?php 
		echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'id' => 'loginForm'));
		echo $this->Session->flash();
	?>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Tên Đăng nhập</th>
			<td>
            <?php 
				echo $this->Form->input('username',
						array(	'class'			=>	'login-inp',
								'autocomplete'	=>	'off',
								'label'			=>	false,
								'div'			=>	false,
								'id'			=>	'username'
						)
					);
			?>
            </td>
		</tr>
		<tr>
			<th>Mật khẩu</th>
			<td>
            <?php 
				echo $this->Form->input('password',
						array(
							'class'		=>	'login-inp',
							'label'		=>	false,
							'div'		=>	false,
							'type'		=>	'password',
							'id'		=>	'password'
							)
				);
			?>
            </td>
		</tr>
		<tr>
			<th></th>
			<td valign="top"><input type="checkbox" name="data[User][remember_me]" class="checkbox-size" id="login-check" /><label for="login-check" style="display:inline!important;">Ghi nhớ đăng nhập</label></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" value="" class="submit-login"  /></td>
		</tr>
		</table>
    <?php
		echo $this->Form->end(null);
	?>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
    <a href="" class="forgot-pwd">Quên mật khẩu ?</a>
 </div>
 <!--  end loginbox -->	
 
 <!--  start forgotbox ................................................................................... -->
<div id="forgotbox">
    <div id="forgotbox-text">Vui lòng cung cấp Tên đăng nhập và Email đã đăng ký.</div>
    <!--  start forgot-inner -->
    <div id="forgot-inner">
    <?php 
		echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'forgot'), 'id' => 'forgotForm'));
	?>
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th>Tên đăng nhập:</th>
        <td>
        <?php 
			echo $this->Form->input('username',
					array(	'class'			=>	'login-inp',
							'autocomplete'	=>	'off',
							'label'			=>	false,
							'div'			=>	false,
							'id'			=>	'username'
					)
				);
		?>
        </td>
    </tr>
    <tr>
        <th>Email:</th>
        <td>
        <?php 
			echo $this->Form->input('email',
					array(	'class'			=>	'login-inp',
							'autocomplete'	=>	'off',
							'label'			=>	false,
							'div'			=>	false,
							'id'			=>	'email'
					)
				);
		?>
        </td>
    </tr>
    <tr>
        <th> </th>
        <td><input type="submit" class="button" value="Nhận lại mật khẩu"  /></td>
    </tr>
    </table>
    <?php echo $this->Form->end(null); ?>
    </div>
    <!--  end forgot-inner -->
    <div class="clear"></div>
    <a href="" class="back-login">Quay lại đăng nhập</a>
</div>
<!--  end forgotbox -->
<script>
	$(document).ready(function(){
		
		if($.browser.msie && // IE
			($.browser.version).split('.', 1) < 7)
		{
			alert('Bạn đang dùng trình duyệt Internet Explorer phiên bản thấp hơn 7.0. Chúng tôi khuyến khích bạn nâng cấp trình duyệt để có thể sử dụng chương trình được tốt hơn');
			
		}/*else if($.browser.mozilla && $.browser.version < '3.0')
		{
			alert('Bạn đang dùng trình duyệt FireFox phiên bản thấp hơn 3.0. Chúng tôi khuyến khích bạn nâng cấp trình duyệt để có thể sử dụng chương trình được tốt hơn');
		}*/
		
		if(screen.width < 1024)
			alert('Bạn đang dùng màn hình có độ phân giải thấp hơn 1024 x 768 pixel. Chúng tôi khuyến khích bạn nâng độ phân giải màn hình để có thể sử dụng chương trình được tốt hơn');
	});
</script>