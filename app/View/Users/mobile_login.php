
<div data-role="page">
	<div data-role="header">
		<h1>Đăng nhập</h1>
	</div>
	<div data-role="content">	
    	<?php 
			echo $this->Session->flash(); 
			echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'id' => 'loginForm', 'mobile' => true, 'data-transition' => 'pop', 'data-ajax' => "false"));
		?>
        	<div data-role="fieldcontain">
            	<label for="username">Tên đăng nhập : </label>
                <?php 
					echo $this->Form->input('username',
							array(
									'autocomplete'	=>	'off',
									'label'			=>	false,
									'div'			=>	false,
									'id'			=>	'username',
									'placeholder'	=>	'Tên đăng nhập'
							)
						);
				?>
            </div>
            <div data-role="fieldcontain">
            	<label for="password">Mật khẩu : </label>
                <?php 
					echo $this->Form->input('password',
							array(
								'label'		=>	false,
								'div'		=>	false,
								'type'		=>	'password',
								'id'		=>	'password',
								'placeholder'	=>	'Mật khẩu'
								)
					);
				?>
            </div>
            <div data-role="fieldcontain">
            	<label for="rememberme">Ghi nhớ đăng nhập</label>
                <?php 
					echo $this->Form->input('remember_me',
							array(
								'label'		=>	false,
								'div'		=>	false,
								'type'		=>	'checkbox',
								'id'		=>	'rememberme'
								)
					);
				?>
            </div>
            <div data-role="fieldcontain">
            	<input type="submit" value="Đăng nhập" data-theme="b" />
            </div>
        <?php
			echo $this->Form->end(null);
		?>
	</div>
	<div data-role="footer">
		<h4><?php echo Configure::read('Mobile.footer') ?></h4>
	</div>
</div>