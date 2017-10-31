<?php 
	/**
	* 
	*/
	class Apiuser extends AppModel
	{
		public $name = 'User';	
		public $useTable = 'sys_users';
		public function beforeSave($options = array()) {
	        if (!empty($this->data['User']['password'])) {
	            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
	        }
	        return true;
	    }		
	}
 ?>