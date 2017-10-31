<?php

App::uses('AuthComponent', 'Controller/Component');
App::uses('SessionComponent', 'Controller/Component');
//App::uses('Nhanvien', 'Model');

/**
 * User
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class User extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public 	$name = 'User';
	
	public 	$tablePrefix = 'sys_';
	
	
	
/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
 	/*
    public $actsAs = array(
        'Acl' => array('type' => 'requester'),
    );
	*/
/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
    public 	$hasOne = array('Nhanvien');
	
/**
 * Validation
 *
 * @var array
 * @access public
 */
    public $validate = array(
        'username' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'The username has already been taken.',
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'This field cannot be left blank.',
            ),
        ),
        'password' => array(
            'rule' => array('minLength', 6),
            'message' => 'Passwords must be at least 6 characters long.',
        )
    );

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (!isset($data['User']['role_id']) || !$data['User']['role_id']) {
            return null;
        } else {
            return array('Role' => array('id' => $data['User']['role_id']));
        }
    }

    public function beforeSave($options = array()) {
        if (!empty($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }
	/*
    public function afterSave($created) {
        if (!$created) {
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
            $this->Aro->save($aro);
        }
    }

    protected function _identical($check) {
        if (isset($this->data['User']['password'])) {
            if ($this->data['User']['password'] == $check['current_password']) {
                return true;
            } else {
                return __('Current password did not match. Please, try again.');
            }
        }
        return true;
    }
	*/
	
	public	function	afterLogin()
	{
		//pr(CakeSession::read());die();
		$user = $this->find('first', array('conditions' => array('username' => CakeSession::read('Auth.User.username')), 'recursive' => -1));
		$this->save(
			array(
				'id'		=>	$user['User']['id'],
				'last_login_date'	=>	date("Y-m-d H:i:s"),
				'is_online'	=>	1
			)
		);
		$this->Nhanvien->recursive = 2;
		$info = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $user['User']['id'])));
		$quyen = array();
		if(!empty($info['Nhomquyen']))
			foreach($info['Nhomquyen'] as $item)
			{
				if(!empty($item['Quyen']))
					foreach($item['Quyen'] as $child)
						if(!isset($quyen[$child['tu_khoa']]) || $quyen[$child['tu_khoa']] > $child['SysQuyenNhomquyen']['pham_vi'])
						$quyen[$child['tu_khoa']] = $child['SysQuyenNhomquyen']['pham_vi'];
					
			}
		//SessionComponent::delete('Auth.User.Nhanvien');
		$user['User']['nhanvien_id'] = $info['Nhanvien']['id'];
		$user['User']['phong_id'] = $info['Nhanvien']['phong_id'];
		$user['User']['chucdanh_id'] = $info['Nhanvien']['chucdanh_id'];
		$user['User']['quyen'] = $quyen;
		$user['User']['donvi_id'] = $this->getDonvi($info['Nhanvien']['phong_id']);
		/***   Hà thêm để list ds nhân viên thuộc phòng thuộc trung tâm  ***/
		$user['User']['phong_cha_id'] = $this->getPhongcha($info['Nhanvien']['phong_id']);
		
		CakeSession::write('Auth', $user);
		
		$this->query("INSERT INTO signin_histories(nhanvien_id, signin_date) VALUES(" . $user['User']['nhanvien_id'] . ", '" . date('Y-m-d H:i:s', time()) . "')");
		/*SessionComponent::write('Auth.User.nhanvien_id', $info['Nhanvien']['id']);
		SessionComponent::write('Auth.User.phong_id', $info['Nhanvien']['phong_id']);
		SessionComponent::write('Auth.User.quyen', $quyen);
		*/
		
		// clear menu cache view
		clearCache('cake_element_' . $info['Nhanvien']['id'], 'views', ''); 
		return true;
	}
	
	public	function	delete($id = null, $cascade = true)
	{
		$user = $this->read('username', $id);
		if(parent::delete($id, $cascade))
		{
			$root = WWW_ROOT . 'uploads';
			$full_path = str_replace(DS, '/', $root . DS . $user['User']['username']);
			
			@rmdir($full_path);
			return true;
		}
		return false;
	}
	
	public function	getDonvi($phong_id)
	{
		App::import( 'Model', 'Phong' );
		App::import( 'Controller', 'App' );
		$app = new AppController();
		$phong = new Phong();
		$ct = $phong->read(array('id', 'lft', 'rght', 'loai_donvi'), $phong_id);
		$results = $phong->getPath($phong_id);
		
		if($ct['Phong']['loai_donvi'] == 1 || $ct['Phong']['loai_donvi'] == 2)
			return null;
		else
			foreach($results as $item)
			{
				if($item['Phong']['loai_donvi'] == 3)
					return $item['Phong']['id'];
			}
		return null;
	}
	/***   Hà thêm để list ds nhân viên thuộc phòng thuộc trung tâm  ***/
	public function	getPhongcha($phong_id)

	{

		App::import( 'Model', 'Phong' );

		App::import( 'Controller', 'App' );

		$app = new AppController();

		$phong = new Phong();

		$ct = $phong->read(array('thuoc_phong'), $phong_id);
		return $ct['Phong']['thuoc_phong'];

	}	
}
?>