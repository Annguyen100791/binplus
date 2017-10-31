<?php
/**
 * Users Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */

App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
    public $name = 'Users';
/**
 * Components
 *
 * @var array
 * @access public
 */
    public $components = array(
        'Email',

        'Cookie',

		//'Auth'
    );
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
		if(!$this->Auth->user())
			$this->Auth->allow(array('forgot', 'binalert_login', 'resetpass','apiauthenticate'));
    }	public	function	binalert_login($username = null, $password = null, $action = 'home')
	{
		$data = $this->User->find('first', array('conditions' => array('username' => $username, 'password' => $password, 'enabled' => 1)));
		if(!empty($data))
		{
			$this->Auth->login($data);

			if (!$this->Auth->user()){
				$this->Session->setFlash('Có lỗi xảy ra khi đăng nhập từ Bin Alert.');
			}else
			{
				$this->Session->write('Auth.User.username', $username);
				$this->User->afterLogin();
				$url = '';
				if($action == 'tinnhan')
					$url = '/tinnhan';
				elseif($action == 'vanban')
					$url = '/vanban';
				elseif($action == 'congviec')
					$url = '/congviec';
				else
					$url = '/';
				$this->redirect($url);
				//$this->redirect($this->Auth->redirect());
			}
		}else
		{
			$this->Session->setFlash('Có lỗi xảy ra khi đăng nhập từ Bin Alert.');

		}
		$this->redirect($this->Auth->redirect());
	}
	public function login() {

		$this->layout = 'login';
		$this->set('title_for_layout', 'Đăng nhập');

		if ($this->request->is('post')) {

			

			////

			if ($this->Auth->login())

			{

				if(!empty($this->request->data['User']['remember_me']))

				{

					$this->Cookie->write('User',

						array(

							'username'	=>	$this->request->data['User']['username'],

							'password'	=>	$this->request->data['User']['password']));

				}else

				{

					$this->Cookie->delete('User');

				}

				$this->User->id  = $this->Auth->user('id');

				$this->User->afterLogin();

				//$this->redirect($this->Auth->redirect());

				//echo var_dump($this->Cookie);die();

				if(!is_null($this->Cookie->read('return_url')))

				{

					  $this->redirect($this->Cookie->read('return_url'));

				}

				else {

							  $this->redirect('/');

				}

			} else {

				$this->Session->setFlash('Tên đăng nhập hoặc Mật khẩu không đúng. Vui lòng thử lại.', 'flash_error');

			}
		}else{
			if( !is_null( $this->Cookie->read('User') ) )

			{

				$cookie = $this->Cookie->read('User');

				if ($this->Auth->login($cookie)) {
					$this->Session->delete('Message.auth');

					$this->User->afterLogin();

					$this->redirect($this->Auth->redirect());

				} else {

					$this->Cookie->delete('User');
					$this->Session->setFlash('Invalid cookie');

					$this->redirect($this->Auth->redirect());

				}
			}

		}

    }
    /*public function login() {
		$this->layout = 'login';

		$this->set('title_for_layout', 'Đăng nhập');
		if ($this->request->is('post')) {
			if ($this->Auth->login())
			{
				if(!empty($this->request->data['User']['remember_me']))
				{
					$this->Cookie->write('User',
						array(
							'username'	=>	$this->request->data['User']['username'],
							'password'	=>	$this->request->data['User']['password']));
				}else
				{
					$this->Cookie->delete('User');
				}
				$this->User->id  = $this->Auth->user('id');
				$this->User->afterLogin();
				//$this->redirect($this->Auth->redirect());
				//$this->redirect('/');

        //echo var_dump($this->Cookie->read('return_url')));die();

        // if(!is_null($this->Cookie->read('return_url')) && $this->Cookie->read('return_url') != '0')

        // {

        //       $this->redirect($this->Cookie->read('return_url'));

        // }

        // else {

				//       $this->redirect('/');

        // }
			} else {
				$this->Session->setFlash('Tên đăng nhập hoặc Mật khẩu không đúng. Vui lòng thử lại.', 'flash_error');
			}

		}else{

			if( !is_null( $this->Cookie->read('User') ) )
			{
				$cookie = $this->Cookie->read('User');
				if ($this->Auth->login($cookie)) {

					$this->Session->delete('Message.auth');
					$this->User->afterLogin();
					$this->redirect($this->Auth->redirect());
				} else {
					$this->Cookie->delete('User');

					$this->Session->setFlash('Invalid cookie');
					$this->redirect($this->Auth->redirect());
				}

			}
		}
    }*/

    public function logout() {
		if(!is_null($this->Auth->user('id')))
			$this->User->query("UPDATE sys_users SET is_online=0 WHERE id=" . $this->Auth->user('id'));
		if( !is_null( $this->Cookie->read('User') ) )
			$this->Cookie->delete('User');
    if( !is_null( $this->Cookie->read('return_url') ) )

      $this->Cookie->delete('return_url');

		//Reset canh bao khi logout - quytn

		$this->Cookie->delete('popupexpire');

		$this->Cookie->delete('popupobexpire');

		//end - quytn

		$this->Session->setFlash('Bạn đã thoát ra khỏi hệ thống.', 'flash_success');
        $this->redirect($this->Auth->logout());
    }	public	function	changepass()
	{
		if(!$this->check_permission('NhanSu.doimatkhau'))
			throw new InternalErrorException();

		if(!empty($this->request->data))
		{
			if($this->User->find('count', array('User.id' => $this->Auth->user('id'), 'password' => AuthComponent::password($this->request->data['User']['oldpassword']))) <= 0)
				die(json_encode(array('success' => false, 'message' => 'Mật khẩu cũ không đúng')));
			else
			{
				$this->request->data['User']['id'] = $this->Auth->user('id');
				$this->request->data['User']['password'] =$this->request->data['User']['password1'];
				$this->User->recursive = -1;
				if($this->User->save($this->request->data))
					die(json_encode(array('success' => true, 'message' => 'Mật khẩu thay đổi thành công.')));
			}
		}
	}

	public	function	check($type = 'username')
	{
		$this->layout = null;
		$count = 0;

		if($type == 'username')
			$count = $this->User->find('count', array('conditions' => array("username" => $this->data['User']['username'])));
		else
			$count = $this->User->find('count', array('conditions' => array("User.email" => $this->data['User']['email'])));

		if($count > 0)
				echo "false";
			else
				echo "true";
		die();
	}

	public	function	forgot()
	{
		$this->layout = 'login';
		if(!empty($this->request->data))
		{
			$data = $this->User->find('first', array('conditions' => array('username' => $this->request->data['User']['username'])));

			if(empty($data))
				$this->Session->setFlash('Tên Đăng nhập này không tồn tại. Vui lòng thử lại.', 'flash_error');
			elseif($data['User']['email'] != $this->request->data['User']['email'])
				$this->Session->setFlash('Tên Đăng nhập và Email đăng ký không hợp lệ. Vui lòng thử lại.', 'flash_error');
			elseif($data['User']['enabled'] != 1)
				$this->Session->setFlash('Tên Đăng nhập này đã bị khóa. Vui lòng liên hệ với Quản trị hệ thống.', 'flash_error');
			else
			{
				if($this->_sendEmailForgotPassword($data))
				{
					$this->Session->setFlash('Chúng tôi đã gửi đến email của bạn các hướng dẫn để nhận lại mật khẩu. Vui lòng kiểm tra email của bạn.', 'flash_success');

				}
				else
					$this->Session->setFlash('Đã phát sinh lỗi. Vui lòng liên hệ với Quản trị hệ thống.', 'flash_error');
				$this->redirect('/users/login');
			}
		}
	}	public	function	resetpass($username, $security_code)
	{
		Configure::write('debug', 1);
		$user = $this->User->find('first', array('conditions' => array('username' => $username, 'enabled' => 1), 'fields' => array('id', 'email'), 'recursive' => -1));
		if(empty($user))
		{
			$this->Session->setFlash('Không tìm thấy thành viên này.', 'flash_error');
			$this->redirect('/users/forgot');
		}
		elseif(md5($username . '|' . $user['User']['email']) != $security_code)
		{
			$this->Session->setFlash('Mã bảo mật không đúng. Vui lòng thử lại.', 'flash_error');
			$this->redirect('/users/forgot');
		}
		else
		{
			$this->User->id = $user['User']['id'];
			if($this->User->saveField('password', 'abc123'))
			{
				$this->Session->setFlash('Mật khẩu của bạn hiện tại là abc123. Vui lòng đăng nhập hệ thống và đổi mật khẩu lại.', 'flash_success');
				$this->redirect('/users/login');
			}else
			{
				$this->Session->setFlash('Đã bị lỗi. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/users/forgot');
			}
		}

	}

	function	_sendEmailForgotPassword($info)
	{
		$email = new CakeEmail();
		$email->viewVars(array('info' => $info));
		return $email->template('forgot', 'default')
			->emailFormat('html')
			->subject('Nhận lại mật khẩu sử dụng BIN+')
			->to($info['User']['email'])
			->from('webmaster@binplus.ptc.com.vn')
			->send();
	}	public	function	mobile_login()
	{
		if ($this->request->is('post')) {
			if ($this->Auth->login())
			{
				if(!empty($this->request->data['User']['remember_me']))
				{
					$this->Cookie->write('User',
						array(
							'username'	=>	$this->request->data['User']['username'],
							'password'	=>	$this->request->data['User']['password']));
				}else
				{
					$this->Cookie->delete('User');
				}
				$this->User->id  = $this->Auth->user('id');
				$this->User->afterLogin();
				$this->redirect('/mobile');

			} else {
				$this->Session->setFlash('Tên đăng nhập hoặc Mật khẩu không đúng. Vui lòng thử lại.', 'mobile_flash_error');
			}

		}else{

			if( !is_null( $this->Cookie->read('User') ) )
			{
				$cookie = $this->Cookie->read('User');
				if ($this->Auth->login($cookie)) {

					$this->Session->delete('Message.auth');
					$this->User->afterLogin();
					$this->redirect($this->Auth->redirect());
				} else {
					$this->Cookie->delete('User');

					$this->Session->setFlash('Invalid cookie');
					$this->redirect($this->Auth->redirect());
				}

			}
		}
    }

	public function mobile_logout() {
		if(!is_null($this->Auth->user('id')))
			$this->User->query("UPDATE sys_users SET is_online=0 WHERE id=" . $this->Auth->user('id'));
		if( !is_null( $this->Cookie->read('User') ) )
			$this->Cookie->delete('User');

		$this->Session->setFlash('Bạn đã thoát ra khỏi hệ thống.');
        $this->redirect($this->Auth->logout());
    }
    public function apiauthenticate() {

    if(!$this->request->is('post'))

      exit();

    $username =  $this->request->data['username'];

    $hashedpassword = AuthComponent::password($this->request->data['password']);

    $userobj = $this->User->find('first',array('conditions' => array('username' => $username, 'password' => $hashedpassword), 'recursive' => -1));

    if(!is_null($userobj['User']))

    {

      header("Content-Type: text/plaintext");

      header("Access-Control-Allow-Origin: *");

      echo $userobj['User']['username'];

    }

    exit();
    //echo var_dump($this->request->data);die();

  }
}
?>

