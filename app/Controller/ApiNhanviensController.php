<?php 
/**
* 
*/
	class ApiNhanviensController extends AppController
	{
		public $uses = array('Nhanvien', 'User','Chucdanh','Phong');
		public function beforeFilter() {
	     	parent::beforeFilter();
	     // 	$user = $this->Auth->user();
		    // if ( ( $user == null ) &&
			   //      ( $this->request->params['controller'] == 'apinhanviens'
			   //      	        || $this->request->params['action'] == 'thongtin'
			   //     	)
		    //     )
		    // {
		    // 	$this->Auth->allow();
		    // }
	     }
		
		public function thongtin()
		{
			$iduser = $this->request->data['nhanvien_id'];
			$res = array();
			$data = array();
			$this->Nhanvien->unbindModel(
				 array('belongsTo' => array('User'))
				 );
			$this->Nhanvien->bindModel(array(
			'hasOne' 	=> array(
					'Chucdanh'	=>	array('foreignKey' => 'id', 
										  'fields'		=>	array('ten_chucdanh')),
					'Phong'		=>	array('foreignKey'	=>	'id',
										  'fields'		=>	array('ten_phong')),

					)
					));
			$conds = array('conditions' => array('Nhanvien.id' => $iduser));
			$data = $this->Nhanvien->find('all',$conds);
			$res['id'] = $data[0]['Nhanvien']['id'];
			$res['ma_nv'] = $data[0]['Nhanvien']['ma_nv'];
			$res['gioi_tinh'] = $data[0]['Nhanvien']['gioi_tinh'];
			$res['ngay_sinh'] = $data[0]['Nhanvien']['ngay_sinh'];
			$res['dien_thoai'] = $data[0]['Nhanvien']['dien_thoai'];
			$res['full_name'] = $data[0]['Nhanvien']['full_name'];
			$res['gioi_tinh'] = $data[0]['Nhanvien']['gioi_tinh'];
			$res['chucdanh'] = $data[0]['Chucdanh']['ten_chucdanh'];
			$res['phong'] = $data[0]['Phong']['ten_phong'];			
			$tmp = array();
			foreach ($data[0]['Nhomquyen'] as $value) {
				array_push($tmp,$value['ten_nhomquyen']);
			}
			$res['nhomquyen'] = $tmp;
			$this->set(compact('res'));
	    	$this->set('_serialize', 'res');
		}
	}

 ?>