<!--  start forgotbox ................................................................................... -->
<div id="forgotbox">
    <div id="forgotbox-text">Vui lòng cung cấp Tên đăng nhập và Email đã đăng ký. Chúng tôi sẽ hướng dẫn cách lấy lại mật khẩu.</div>
    <!--  start forgot-inner -->
    <div id="forgot-inner">
    <?php 
		echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'forgot'), 'id' => 'forgotForm'));
		echo $this->Session->flash();
	?>
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th>Tên đăng nhập</th>
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
        <th>Email</th>
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
    <?php echo $this->Html->link('Quay lại đăng nhập', '/users/login', array('class' => 'back-login')) ?>
</div>
<!--  end forgotbox -->