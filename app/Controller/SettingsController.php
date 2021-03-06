<?php
/**
 * Settings Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  CMS
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.cms.org
 */
class SettingsController extends AppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
    public $name = 'Settings';
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    public $uses = array('Setting');
/**
 * Helpers used by the Controller
 *
 * @var array
 * @access public
 */
    public $helpers = array('Html', 'Form');
	
	public	function	beforeFilter()
	{
		parent::beforeFilter();
		if(!$this->check_permission('HeThong.setting'))
			throw new InternalErrorException();
	}

    public function dashboard() {
        $this->set('title_for_layout', __('Dashboard'));
    }

    public function index() {
        $this->set('title_for_layout', __('Settings'));

        $this->Setting->recursive = 0;
        $this->paginate['Setting']['order'] = "Setting.weight ASC";
        if (isset($this->request->params['named']['p'])) {
            $this->paginate['Setting']['conditions'] = "Setting.key LIKE '". $this->request->params['named']['p'] ."%'";
        }
        $this->set('settings', $this->paginate());
    }

    public function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Setting.'), 'default', array('class' => 'error'));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('setting', $this->Setting->read(null, $id));
    }

    public function admin_add() {
        $this->set('title_for_layout', __('Add Setting'));

        if (!empty($this->request->data)) {
            $this->Setting->create();
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(__('The Setting has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Setting could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }
    }

    public function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Setting'));

        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__('Invalid Setting'), 'default', array('class' => 'error'));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->request->data)) {
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(__('The Setting has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Setting could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Setting->read(null, $id);
        }
    }

    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Setting'), 'default', array('class' => 'error'));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Setting->delete($id)) {
            $this->Session->setFlash(__('Setting deleted'), 'default', array('class' => 'success'));
            $this->redirect(array('action'=>'index'));
        }
    }

    public function prefix($prefix = null) 
	{
		$prefixes = $this->Setting->prefixes;
        $this->set('title_for_layout', sprintf(__('Settings: %s'), $prefixes[$prefix]));

        
		if(!empty($this->request->data) && $this->Setting->saveAll($this->request->data['Setting'])) 
		{
			$this->Session->setFlash('Tham số cấu hình được cập nhật thành công.', 'flash_success');
        }

        $settings = $this->Setting->find('all', array(
            'order' => 'Setting.weight ASC',
            'conditions' => array(
                'Setting.key LIKE' => $prefix . '.%',
                'Setting.editable' => 1,
            ),
        ));
        if( count($settings) == 0 ) 
		{
			$this->Session->setFlash('Invalid Setting key.', 'flash_error');
        }

        $this->set(compact('prefix', 'prefixes', 'settings'));
		
    }

    public function admin_moveup($id, $step = 1) {
        if( $this->Setting->moveup($id, $step) ) {
            $this->Session->setFlash(__('Moved up successfully'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('Could not move up'), 'default', array('class' => 'error'));
        }

        $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
    }

    public function admin_movedown($id, $step = 1) {
        if( $this->Setting->movedown($id, $step) ) {
            $this->Session->setFlash(__('Moved down successfully'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('Could not move down'), 'default', array('class' => 'error'));
        }

        $this->redirect(array('admin' => true, 'controller' => 'settings', 'action' => 'index'));
    }

}
?>