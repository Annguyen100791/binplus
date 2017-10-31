<?php 
	/**
	* 
	*/
	class ApiusersController extends AppController
	{
		public function login() {
	        $res = array();
            $this->request->data['User']['username'] = $this->request->data['username'];
            $this->request->data['User']['password'] = $this->request->data['password'];
            if ($this->Auth->login()) {
                $user = $this->Auth->user();
             //    $res['Id'] = $user['id'];
             //    $res['User'] = $user['username'];
             //    $res['Email'] = $user['email'];
            	// $res['RoleID'] = $user['role_id'];
            	// $res['fullname'] = $user['fullname'];
             //    $res['intmessage'] = 1;
                $res = $user;
    			$res['isLogin'] = "1";
                $this->Session->delete('Auth');            
            } else {
                $res['isLogin'] = 0;
            }
	        $this->set(compact('res'));
	        $this->set('_serialize', 'res');
	    }

	    public function logout() {
	        $user = $this->Auth->user();
	        $res = array();
	        if ($user !== null) {
	            $this->Auth->logout();
	            $res['message'] = "Đăng xuất thành công";
	        } else {
	            $res['message'] = "Không thể đăng xuất";
	        }
	        $this->set(compact('res'));
	        $this->set('_serialize', 'res');
	    }
	     public function beforeFilter() {
	     	$user = $this->Auth->user();
		    if ( ( $user == null ) &&
			        ( $this->request->params['controller'] == 'apiusers'
			        	        || $this->request->params['action'] == 'login'
			        	        || $this->request->params['action'] == 'logout'
			       	)
		        )
		    {
		    	$this->Auth->allow();
		    }
	     }
	}
 ?>