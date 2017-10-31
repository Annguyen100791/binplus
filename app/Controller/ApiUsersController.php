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
 * @link	 http://www.croogo.org
 */

App::uses('UsersController', 'Controller');

class ApiUsersController extends UsersController {

	public function beforeFilter() {
	     	$user = $this->Auth->user();
		    if ( ( $user == null ) &&
			        !strpos('api', $this->request->params['controller'])
		        )
		    {
		    	$this->Auth->allow();
		    }
	   }

	public function login(){

		// Define to return API
		$res = array();

		$this->loadModel('User');

			$this->request->data['User']['username'] = $this->request->data['username'];
	        $this->request->data['User']['password'] = $this->request->data['password'];

			if ($this->Auth->login())
			{
				$user = $this->Auth->user();

				$this->User->id = $this->Auth->user('id');
				$this->User->afterLogin();
				// $this->Session->delete('Auth');  
				$res = $user;
				$res['isLogin'] = "1";

			} else {
				$res['isLogin'] = "0";
			}

		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

	 public function logout() {
	        $user = $this->Auth->user();
	        $res = array();
	        if ($user !== null) {
	            $this->Auth->logout();
	            $res['isLogout'] = "1";
	        } else {
	            $res['isLogout'] = "0";
	        }
	        $this->set(compact('res'));
	        $this->set('_serialize', 'res');
	}

	public function apiGetQuyen(){

		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';
		
		$quyen = array();

		$this->loadModel('Nhanvien');
		$this->Nhanvien->recursive = 2;
		$info = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nhanvien_id)));

		// var_dump($info['Nhomquyen']);die();
		if(!empty($info['Nhomquyen'])){
			foreach($info['Nhomquyen'] as $item)
			{
				if(!empty($item['Quyen']))
					foreach($item['Quyen'] as $child)
						if(!isset($quyen[$child['tu_khoa']]) || $quyen[$child['tu_khoa']] > $child['SysQuyenNhomquyen']['pham_vi'])
						$quyen[$child['tu_khoa']] = $child['SysQuyenNhomquyen']['pham_vi'];
					
			}
		}
		$this->set(compact('quyen'));
	    $this->set('_serialize', 'quyen');
	}
}
?>

