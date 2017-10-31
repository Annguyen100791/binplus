<?php
/**
 * Layout Helper
 *
 * PHP version 5
 *
 * @category Helper
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class LayoutHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Js',
    );
	
	
	public function flash($key = 'flash'){
		$out = '';
		if (CakeSession::check('Message.' . $key)) {
			$flash = CakeSession::read('Message.' . $key);
			$message = $flash['message'];
			unset($flash['message']);

			if ($flash['element'] == 'default') {
				$class = 'message';
				if (!empty($flash['params']['class'])) {
					$class = $flash['params']['class'];
				}
				$tmp = explode(' ', $class);
				if (in_array('error', $tmp)){
					if (isset($this->request->params['admin']))
						$message = '<span>' . __('Thông báo lỗi', true) . '</span>' . $message;
				}
				else{
					if (isset($this->request->params['admin']))
						$message = '<span>' . __('Thông báo', true) . '</span>' . $message;
				}
				$out = '<div id="' . $key . 'Message" class="' . $class . '">' . $message . '</div>';
			} elseif ($flash['element'] == '' || $flash['element'] == null) {
				$out = $message;
			} else {
				$options = array();
				if (isset($flash['params']['plugin'])) {
					$options['plugin'] = $flash['params']['plugin'];
				}
				$tmpVars = $flash['params'];
				$tmpVars['message'] = $message;
				$out = $this->_View->element($flash['element'], $tmpVars, $options);
			}
			CakeSession::delete('Message.' . $key);
		}
		echo $out;
	}
	
	public function js_init() {
        $crm = array();
        $crm = array(
			'basePath'		=>	'/',
            'controller' 	=> 	$this->params['controller'],
            'action' 		=> 	$this->params['action'],
            'named' 		=> 	$this->params['named'],
			'plugin'		=>	$this->params['plugin'],
			'prefix'		=>	$this->params['prefix']
        );
        if (is_array(Configure::read('Js'))) {
            $cms = Set::merge($cms, Configure::read('Js'));
        }
		  
		  $scripts = $this->Html->scriptBlock('var params = ' . $this->Js->object($crm) . ';');
		  $scripts .= $this->Html->script('libs/bin.js');
		  $scripts .= $this->jsFile($crm);
		  return $scripts;
    }
	 
	 private	function	jsFile($params)
	 {
		$jsFile = '';
		$prefix = strtolower($params['prefix']);
		$controller = strtolower(Inflector::underscore($params['controller']));
		if(isset($params['prefix']))
			$jsFile = $prefix . '_' . $controller . '.js';
		else
			$jsFile = $controller . '.js';
			
		if(!empty($params['plugin']))
		{
			if(file_exists(APP . 'Plugin' . DS . $params['plugin'] . DS . 'webroot' . DS . 'js' . DS . $jsFile))
				return $this->Html->script(Router::url('/') . $params['plugin'] . '/js/' . $jsFile);
			else
				return '';
		}else
		{
			if(file_exists(JS . 'libs/' . $jsFile))
				return $this->Html->script('libs/' . $jsFile);
			else
				return '';
		}

		
	}
	
	public	function	check_permission($permissions)
	{
		$ds = $this->Session->read('Auth.User.quyen');
//		pr($ds);die();
		if(array_key_exists('HeThong.toanquyen', $ds))
			return true;
			
		if(is_array($permissions))
		{
			$ret = array_intersect_key(array_flip($permissions), $ds);
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
	
	public	function	check_group_permission($group) // LIKE HeThong.*
	{
		$ds = $this->Session->read('Auth.User.quyen');
		if(array_key_exists('HeThong.toanquyen', $ds))
			return true;
			
//		pr($ds); die();
		foreach($ds as $k=>$v)
		{
			if(substr($k, 0, strpos($k, '.')) == $group)
				return true;	
		}
		return false;
	}
	
	public	function	createLink($permission, $url, $title, $options)
	{
		if(!$this->check_permission($permission))
			return $this->Html->link($title, '/pages/denied', array('rel' => 'facebox_access_denied'));
		else
			return $this->Html->link($title, $url, $options);
	}
	
	public	function	createMenuLink($permission, $url, $title, $options)
	{
		if(!$this->check_permission($permission))
			return $this->Html->link($title, '/pages/denied', array('rel' => 'facebox_access_denied'));
		else
			return $this->Html->link('<span>' . $title . '<span>', $url, $options);
	}
	
	
	public	function	check($permission, $owner_id)
	{
		$ds = $this->Session->read('Auth.User.quyen');
		if(array_key_exists('HeThong.toanquyen', $ds))
			return true;
			
		if(!array_key_exists($permission, $ds))
			return false;
			
		if($ds[$permission] == 0)	// toàn đơn vị
			return true;
			
		if($ds[$permission] == 1)	// phòng đang công tác
		{
			App::import('Nhanvien', 'Model');
			$nhanvien = new Nhanvien();
			$phong_id = $nhanvien->field('phong_id', array('id' => $owner_id));
			return ($phong_id == $this->Session->read('Auth.User.phong_id'));
		}
		if($ds[$permission] == 2)	// cá nhân
		{
			return $owner_id == $this->Session->read('Auth.User.nhanvien_id');
		}
	}
	
	
	public function status($value) {
		if ($value == 1) {
			return '<i class="icon-ok"></i>'; 
		} else {
			return '<i class="icon-remove"></i>';
		}
	}
	
	public	function	status_toggle($value, $url)
	{
		$options = array('escape' => false, 'data-mode' => 'ajax', 'data-action' => 'toggle', 'class' => 'tip');
		if($value)
			$options['title'] = 'Click để disable mục này';
		else
			$options['title'] = 'Click để enable mục này';
		return $this->Html->link($this->status($value), $url, $options);
	}

}
?>
