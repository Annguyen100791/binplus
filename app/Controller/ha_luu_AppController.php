<?php



App::uses('Controller', 'Controller');



/**

 * Application controller

 *

 * This file is the base controller of all other controllers

 *

 * PHP version 5

 *

 * @category Controllers

 * @package  BIN

 * @version  1.0

 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>

 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License

 * @link     http://www.ptc.com.vn

 */

class AppController extends Controller {

/**

 * Components

 *

 * @var array

 * @access public

 */

    public $components = array(

        //'Acl',

        'Auth'	=> array(

				'loginRedirect' => array(

						'controller' => 'pages',

						'action' => 'home',

						'plugin' => null

					),

				'autoRedirect'	=>	false,

				'authorize'		=>	array('Controller')

			),

        //'AclFilter',

        'Session',

        'RequestHandler',

		'Cookie'	=>	array('name'	=>	'BINPLUS', 'time' => '+4 weeks')

    );

/**

 * Helpers

 *

 * @var array

 * @access public

 */

    public $helpers = array(

        'Html',

        'Form',

        'Session',

        'Text',

        'Js',

        'Time',

		'Paginator',

		'Layout'

		

    );

	

	const PHAMVI_TOANVIENTHONG = 0;

	const PHAMVI_TOANPHONGBAN_VIENTHONG = 1;

	const PHAMVI_TRUNGTAM = 2;

	const PHAMVI_TRUNGTAM_PHONGBAN = 3;

	const PHAMVI_CANHAN	= 4;

	const PHAMVI_NHOM	= 5;	

	const PHAMVI_VIENTHONG_PHONGBAN	= 6;	

	

	

	const	VIENTHONG = 1;

	const	VIENTHONG_PHONGBAN = 2;

	const	TRUNGTAM = 3;

	const	TRUNGTAM_PHONGBAN = 4;

	

	

	public $loai_donvi = array(

		//self::VIENTHONG 			=> 'Viễn thông Đà Nẵng',

		self::VIENTHONG_PHONGBAN	=> 'Phòng ban chức năng thuộc viễn thông',

		self::TRUNGTAM			=>	'Trung tâm thuộc viễn thông',

		self::TRUNGTAM_PHONGBAN	=>	'Phòng / đội / tổ thuộc Trung tâm'

	);

	

	public	$pham_vi = array(

		self::PHAMVI_TOANVIENTHONG	=>	'Toàn Viễn thông Đà Nẵng',

		self::PHAMVI_TOANPHONGBAN_VIENTHONG	=>	'Tất cả phòng ban chức năng',

		self::PHAMVI_VIENTHONG_PHONGBAN	=>	'Chỉ phòng ban đang công tác',

		self::PHAMVI_TRUNGTAM		=>	'Trung tâm thuộc viễn thông',

		self::PHAMVI_TRUNGTAM_PHONGBAN	=>	'Phòng / đội / tổ thuộc Trung tâm',

		self::PHAMVI_CANHAN		=>	'Cá nhân'

	);

	

	/*

	public	$phamvi = array(

		self::VIENTHONG	=>	'Toàn Viễn thông Đà Nẵng',

		self::VIENTHONG_PHONGBAN	=>	'Phòng ban chức năng thuộc viễn thông',

		self::DONVI	=>	'Trung tâm thuộc viễn thông',

		self::CANHAN	=>	'Thuộc cá nhân'

	);

	*/

/**

 * Models

 *

 * @var array

 * @access public

 */

    public $uses = array(

    );

/**

 * Cache pagination results

 *

 * @var boolean

 * @access public

 */

    public $usePaginationCache = true;

/**

 * View

 *

 * @var string

 * @access public

 */

    public $viewClass = 'Theme';

/**

 * Theme

 *

 * @var string

 * @access public

 */

    public $theme;

	

/**

 * Constructor

 *

 * @access public

 */

    public function __construct($request = null, $response = null) {

        //Croogo::applyHookProperties('Hook.controller_properties');

        parent::__construct($request, $response);

        if ($this->name == 'CakeError') {

            $this->_set(Router::getPaths());

            $this->request->params = Router::getParams();

            $this->constructClasses();

            $this->startupProcess();

        }

    }

/**

 * beforeFilter

 *

 * @return void

 */

    public function beforeFilter() {

        parent::beforeFilter();

		if (isset($this->request->params['mobile'])) 

		{

			$this->layout = 'mobile';

        }

		

		$this->RequestHandler->setContent('json', 'text/x-json');

		if($this->name == 'CakeError')

			$this->layout = 'error';

		

        if ($this->RequestHandler->isAjax()) 

		{

            $this->layout = 'ajax';

        }

		// update online status

		

		$this->loadModel('User');

		//if ($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.id') != ''){

		if($this->Auth->user())

		{

			if(is_null($this->Auth->user('username')))

			{

				$this->Cookie->delete('User');

				$this->redirect($this->Auth->logout());

			}

			/*

			if(is_null($this->Auth->user('username')))

			{

				$this->Cookie->delete('User');

				$this->redirect('/');

			}

			*/

			if($this->Auth->user('username') != '')

			// update last action and is_online for users

				$this->User->query("UPDATE sys_users SET last_action='" . date('Y-m-d H:i:s') . "', is_online=1 WHERE id = " . $this->Auth->user('id'));

		}

		$current_date = date('Y-m-d H:i:s', strtotime("-20 minutes"));

		//$this->User->query('UPDATE sys_users SET is_online = 0 WHERE TIMESTAMPDIFF(MINUTE, last_action, now()) > 20');

		$this->User->query("UPDATE sys_users SET is_online = 0 WHERE last_action <'" . $current_date. "'");

    }

/**

 * blackHoleCallback for SecurityComponent

 *

 * @return void

 */

    public function __securityError($type) {

        switch ($type) {

        case 'auth':

            break;

        case 'csrf':

            break;

        case 'get':

            break;

        case 'post':

            break;

        case 'put':

            break;

        case 'delete':

            break;

        default:

            break;

        }

        $this->set(compact('type'));

        $this->render('../Errors/security');

    }

	

	public	function	check_permission($permissions)

	{

		$ds = $this->Auth->user('quyen');

		

		if(array_key_exists('HeThong.toanquyen', $ds))

			return true;

			

		if(is_array($permissions))

		{

			$ret = array_intersect_key(array_flip($permissions), $ds);

			//pr($ds); die();

			if(empty($ret))	

				return false;

			else

				return true;

		}

		else

			if(array_key_exists($permissions, $ds))

			return true;

		else

			return false;

	}

	

	public	function	getLevelOfPermission($permission)

	{

		$ds = $this->Auth->user('quyen');

		//pr($ds); die();

		if(empty($ds))	return false;

		if(isset($ds[$permission]))

			return $ds[$permission];

		else

			return false;

	}

	

	public function isAuthorized($user) {

		return true;

	}

	

	

	public	function	cboNhanvien($permission)

	{

		$quyen = $this->Auth->user('quyen');

		$this->loadModel('Nhanvien');

		

		if(isset($quyen['HeThong.toanquyen']))

			return $this->Nhanvien->listNhanvien2Combobox(0);

			

		if(!isset($quyen[$permission]))	return null;

		$level = $quyen[$permission];

		//echo CakeSession::read('Auth.User.donvi_id'); die();

		if($level == self::PHAMVI_TOANVIENTHONG)

			return $this->Nhanvien->listNhanvien2Combobox(self::PHAMVI_TOANVIENTHONG);

		elseif($level == self::PHAMVI_TOANPHONGBAN_VIENTHONG)

		{

			$this->loadModel('Phong');

			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => self::VIENTHONG_PHONGBAN, 'enabled' => 1, 'thuoc_phong' => NULL), 'fields' => array('id', 'id')));

			$ds_phong = implode(",", $ds_phong);

			return $this->Nhanvien->listNhanvien2Combobox($ds_phong);

		}elseif($level == self::PHAMVI_VIENTHONG_PHONGBAN)

		{

			return $this->Nhanvien->listNhanvien2Combobox((int)CakeSession::read('Auth.User.phong_id'));

		}elseif($level == self::PHAMVI_TRUNGTAM)

		{

			return $this->Nhanvien->listNhanvien2Combobox((int)CakeSession::read('Auth.User.donvi_id'));

		}

		elseif($level == self::PHAMVI_TRUNGTAM_PHONGBAN)

			return $this->Nhanvien->listNhanvien2Combobox((int)CakeSession::read('Auth.User.phong_id'));

		elseif($level == self::PHAMVI_NHOM)

			return array();

		else

			return array();

	}

	

	

	

	public	function	treeNhanvien($permission)

	{

		$quyen = $this->Auth->user('quyen');

		$this->loadModel('Nhanvien');

		

		if(isset($quyen['HeThong.toanquyen']))

			return $this->Nhanvien->getTree(self::PHAMVI_TOANVIENTHONG);

			

		if(!isset($quyen[$permission]))	return null;

		$level = $quyen[$permission];

		

		if($level == self::PHAMVI_TOANVIENTHONG)

			return $this->Nhanvien->getTree(self::PHAMVI_TOANVIENTHONG);

		if($level == self::PHAMVI_TOANPHONGBAN_VIENTHONG)

		{

			$this->loadModel('Phong');

			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => self::VIENTHONG_PHONGBAN, 'enabled' => 1, 'thuoc_phong' => NULL), 'fields' => array('id', 'id')));

			$ds_phong = implode(",", $ds_phong);

			return $this->Nhanvien->getTree($ds_phong);

		}elseif($level == self::PHAMVI_VIENTHONG_PHONGBAN)

		{

			return $this->Nhanvien->getTree((int)CakeSession::read('Auth.User.phong_id'));

		}elseif($level == self::PHAMVI_TRUNGTAM)

			return $this->Nhanvien->getTree((int)CakeSession::read('Auth.User.donvi_id'));

		elseif($level == self::PHAMVI_TRUNGTAM_PHONGBAN)

			return $this->Nhanvien->getTree((int)CakeSession::read('Auth.User.phong_id'));

		elseif($level == self::PHAMVI_NHOM)

			return array();

		else	// cá nhân

			return array();

	}

	

	

	public	function	listPhong($permission)	// get List

	{

		$quyen = $this->Auth->user('quyen');

		$this->loadModel('Phong');

		

		if(isset($quyen['HeThong.toanquyen']) || (isset($quyen[$permission]) && $quyen[$permission] == self::PHAMVI_TOANVIENTHONG))

		{

			return -1;	// get all

		}

		

		if(!isset($quyen[$permission]))	return null;

		$level = $quyen[$permission];

		if($level == self::PHAMVI_TOANPHONGBAN_VIENTHONG)

		{

			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => self::VIENTHONG_PHONGBAN, 'enabled' => 1, 'thuoc_phong' => NULL), 'fields' => array('id', 'id')));

			

			return $ds_phong;

			

		}elseif($level == self::PHAMVI_TRUNGTAM)

		{

			$phong_id = CakeSession::read('Auth.User.donvi_id');

			

			if(empty($phong_id))	return null;

			

			$phong = $this->Phong->read(array('id', 'lft', 'rght'), $phong_id);

			

			return $this->Phong->find('list', array('conditions' => array('enabled' => 1, 'lft >=' => $phong['Phong']['lft'], 'rght <=' => $phong['Phong']['rght']), 'fields' => array('id')));

		}elseif($level == self::PHAMVI_TRUNGTAM_PHONGBAN || $level == self::PHAMVI_VIENTHONG_PHONGBAN)

		{

			$phong_id = CakeSession::read('Auth.User.phong_id');

			

			if(empty($phong_id))	return null;

			

			$phong = $this->Phong->read(array('id', 'lft', 'rght'), $phong_id);

			

			return $this->Phong->find('list', array('conditions' => array('enabled' => 1, 'lft >=' => $phong['Phong']['lft'], 'rght <=' => $phong['Phong']['rght']), 'fields' => array('id')));

			

		}

		elseif($level == self::PHAMVI_NHOM)

		{

			return array();

		}

		else	// cá nhân

			return array();

	}

	

}

?>