<?php 
/**
* 
*/
class ApivanbansController extends AppController
{
	public	$uses = array('Vanban');
	public function beforeFilter()
	{
		parent::beforeFilter();
		// $this->Auth->allow('getfile_ggd');
		// $this->Auth->allow('unread');
		// $this->Auth->allow('all');
		$user = $this->Auth->user();
	    if ( ( $user == null ) &&
		        ( $this->request->params['controller'] == 'apitinnhans'
		        	        || $this->request->params['action'] == 'unread'
		        	        || $this->request->params['action'] == 'all'
		        	        || $this->request->params['action'] == 'den'
		        	        || $this->request->params['action'] == 'di'
		        	        || $this->request->params['action'] == 'noibo'
		        	        || $this->request->params['action'] == 'update'
		        	        // || $this->request->params['action'] == 'theodoi'
		        	        // || $this->request->params['action'] == 'vbden_gap'
		        	        // || $this->request->params['action'] == 'vb_giamdocchidao'
		        	        || $this->request->params['action'] == 'get_files'
		       	)
	        )
	    {
	    	$this->Auth->allow();
	    }
	}
	public	function	unread()
	{
		$iduser = $this->request->data['iduser'];
		$this->loadModel('Nhanvanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id')				
				),
			'hasMany' => array(
				'Filevanban' => array('foreignKey' => 'vanban_id',
										'fields' => array('id', 'ten_cu'))
				)
			)
		);
		if($iduser == 681)
			$conds = array('conditions' => array(
						'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
					   'Nhanvanban.ngay_xem' 		=> NULL,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1,
					   'OR' => array(
					   		'AND' => array('Vanban.nguoi_duyet_id <>' 	=> 681, 'Vanban.chuyen_nguoiduyet <>' 	=> 1),
							'Vanban.nguoi_duyet_id' => NULL
						)
					   ),
						'limit' => 50,
						'order' => array('Vanban.ngay_nhap' => 'DESC')
					  );
		else
			$conds = array('conditions' => array(
						'Nhanvanban.nguoi_nhan_id'	=>	$iduser,

					   'Nhanvanban.ngay_xem' 		=> NULL,

					   'Vanban.tinhtrang_duyet' => 1,

					   'Vanban.loaivanban_id <>' 		=> NULL
					   ),
						'limit' => 50,
						'order' => array('Vanban.ngay_nhap' => 'DESC')
					  );
		$res = array();
		$res = $this->Vanban->find('all',$conds);
		$this->set(compact('res'));
	    $this->set('_serialize', 'res');
	}
	public	function	all()
	{
		$iduser = $this->request->data['iduser'];
		$this->loadModel('Nhanvanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id')				
				),
			'hasMany' => array(
				'Filevanban' => array('foreignKey' => 'vanban_id',
										'fields' => array('id', 'ten_cu'))
				)
			)
		);
		if($iduser == 681)
			$conds = array('conditions' => array('Nhanvanban.nguoi_nhan_id'	=>	$iduser,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1,
					   'OR' => array(
					   		'AND' => array('Vanban.nguoi_duyet_id <>' 	=> 681, 'Vanban.chuyen_nguoiduyet <>' 	=> 1),
							'Vanban.nguoi_duyet_id' => NULL
						)
					  ),
					'limit' => 50,
					'order' => array('Vanban.ngay_nhap' => 'DESC')
					);
		else
			$conds = array('conditions' => array(
						'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL
							),
						'limit' => 50,
						'order' => array('Vanban.ngay_nhap' => 'DESC')
						);
		$res = array();
		$res = $this->Vanban->find('all',$conds);
		$this->set(compact('res'));
	    $this->set('_serialize', 'res');

	}
	public	function	den() 
	{ 
		$iduser = $this->request->data['iduser'];
		$this->loadModel('Nhanvanban'); 
		$this->Nhanvanban->bindModel(array( 
								'belongsTo'	=>	array('Vanban') 
										   ), false); 
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id')				
				),
			'hasMany' => array(
				'Filevanban' => array('foreignKey' => 'vanban_id',
										'fields' => array('id', 'ten_cu'))
				)
			)
		);
		if($iduser == 681)
			$conds = array('conditions' => array(
						'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.chieu_di'			=>	1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1,
					   'OR' => array(
					   		'AND' => array('Vanban.nguoi_duyet_id <>' 	=> 681, 'Vanban.chuyen_nguoiduyet <>' 	=> 1),
							'Vanban.nguoi_duyet_id' => NULL
						)
					  ),
					'limit' => 50,
					'order' => array('Vanban.ngay_nhap' => 'DESC')
					);
		else
			$conds = array('conditions' => array( 
						'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
						'Vanban.chieu_di'			=>	1,
						'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL),
					'limit' => 50,
					'order' => array('Vanban.ngay_nhap' => 'DESC')
					);
		$res = array();
		$res = $this->Vanban->find('all',$conds);
		$this->set(compact('res'));
	    $this->set('_serialize', 'res');
	}
	public	function	di() 
	{ 
		$iduser = $this->request->data['iduser'];
		$this->loadModel('Nhanvanban'); 
		$this->Nhanvanban->bindModel(array( 
								'belongsTo'	=>	array('Vanban') 
										   ), false); 
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id')				
				),
			'hasMany' => array(
				'Filevanban' => array('foreignKey' => 'vanban_id',
										'fields' => array('id', 'ten_cu'))
				)
			)
		);
		if($iduser == 681)
			$conds = array('conditions' => array( 
							'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
 							'Vanban.chieu_di'			=>	0,
							'Vanban.tinhtrang_duyet' => 1,
					   		'Vanban.loaivanban_id <>' 		=> NULL
								),
							'limit' => 50,
							'order' => array('Vanban.ngay_nhap' => 'DESC')
							);
		else
			$conds = array('conditions' => array( 
							'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
 							'Vanban.chieu_di'			=>	0,
							'Vanban.tinhtrang_duyet' => 1,
					   		'Vanban.loaivanban_id <>' 		=> NULL,
							'Vanban.chuyen_bypass <>' 		=> 1
								),
							'limit' => 50,
							'order' => array('Vanban.ngay_nhap' => 'DESC')
							);
		$res = array();
		$res = $this->Vanban->find('all',$conds);
		$this->set(compact('res'));
	    $this->set('_serialize', 'res');		
	}
	public	function	noibo() 
	{ 
		$iduser = $this->request->data['iduser'];
		$this->loadModel('Nhanvanban'); 
		$this->Nhanvanban->bindModel(array( 
								'belongsTo'	=>	array('Vanban') 
										   ), false); 
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id')				
				),
			'hasMany' => array(
				'Filevanban' => array('foreignKey' => 'vanban_id',
										'fields' => array('id', 'ten_cu'))
				)
			)
		);
		if($iduser == 681)
			$conds = array('conditions' => array( 
							'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
							'Vanban.chieu_di'			=>	2,
							'Vanban.tinhtrang_duyet' => 1,
						    'Vanban.loaivanban_id <>' 		=> NULL,
							'Vanban.chuyen_bypass <>' 		=> 1
								),
							'limit' => 50,
							'order' => array('Vanban.ngay_nhap' => 'DESC')
							);	// văn bản nội bộ
		else
			$conds = array('conditions' => array( 
							'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
							'Vanban.chieu_di'			=>	2,
							'Vanban.tinhtrang_duyet' => 1,
						    'Vanban.loaivanban_id <>' 		=> NULL
						    	),
							'limit' => 50,
							'order' => array('Vanban.ngay_nhap' => 'DESC')
						    );	// văn bản nội bộ 
		$res = array();
		$res = $this->Vanban->find('all',$conds);
		$this->set(compact('res'));
	    $this->set('_serialize', 'res');
	}
	public function update(){
			$idnhanvanban = $this->request->data['idnhanvanban'];
			$this->loadModel('Nhanvanban'); 
			$this->Nhanvanban->query("UPDATE vanban_nhan SET ngay_xem='" . date('Y-m-d H:i:s', time()) . "' WHERE id=" . $idnhanvanban);

			$ngaynhan = $this->Nhanvanban->query("SELECT ngay_xem FROM vanban_nhan WHERE id=" . $idnhanvanban);
			$res = array();			
			foreach ($ngaynhan as $value){
				$ngaynhan= $value['vanban_nhan']['ngay_xem'];
			}
			if($ngaynhan!=NULL){
				$res = 1;
			}
			else{
				$res = 0;
			}
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');
		}
	// public	function	theodoi() 
	// { 
	// 	$iduser = $this->request->data['iduser'];
	// 	$this->loadModel('Theodoivanban'); 
	// 	$this->Theodoivanban->bindModel(array( 
	// 							'belongsTo'	=>	array('Vanban') 
	// 									   ), false); 
	// 	$conds = array('conditions' => array( 
	// 					'Theodoivanban.nguoi_theodoi_id'	=>	$iduser
	// 					)
	// 				); 
	// 	$res = array();
	// 	$res = $this->Theodoivanban->find('all',$conds);
	// 	$this->set(compact('res'));
	//     $this->set('_serialize', 'res');
	// }
	// public	function	vbden_gap()
	// { 
	// 	$iduser = $this->request->data['iduser'];
	// 	$this->loadModel('Vanban');
	// 	$this->Vanban->bindModel(array(
	// 			'belongsTo'	=>	array('Phong' => array('foreignKey' => 'phongchutri_id')),
	// 			'hasOne' => array(
	// 							//'Nhanvanban' => array('foreignKey' => 'vanban_id'),
	// 							'Filevanban' => array('foreignKey' => 'vanban_id',
	// 								'fields' => 'id')
	// 							),
	// 		), false);
	// 	$this->Vanban->bindModel(array(
	// 		'hasMany'	=>	array('Ketquavanban' => array('className'	=>	'Ketquavanban','foreignKey' => 'vanban_id',
	// 								'conditions' => array('nguoi_capnhat_id' => $iduser))
	// 						)
	// 	), false);
	// 	$conds = array('conditions' => array( 
	// 					'Vanban.vb_gap'	=>	1
	// 					)
	// 				); 
	// 	$res = array();
	// 	$res = $this->Vanban->find('all',$conds);
	// 	$this->set(compact('res'));
	//     $this->set('_serialize', 'res');
	// }
	// public	function	vb_giamdocchidao()

	// {
	// 	$iduser = $this->request->data['iduser'];
	// 	$this->loadModel('Nhanvanban');
	// 	$this->Nhanvanban->bindModel(array(
	// 							'belongsTo'	=>	array('Vanban')
	// 									   ), false);
	// 	$this->Vanban->bindModel(array(
	// 		'hasOne' => array(
	// 			'Theodoivanban' => array('foreignKey' => 'vanban_id',
	// 		 		'conditions' => array('nguoi_theodoi_id' => $iduser)
	// 			)
	// 		)
	// 	)); 
	// 	$conds = array('conditions' => array( 
	// 					'Nhanvanban.nguoi_nhan_id'	=>	$iduser,
	// 					'Vanban.tinhtrang_duyet' => 1,
	// 					//'Vanban.gd_chidao'			=>	NULL
	// 					'not'=>array(
	// 					'Vanban.gd_chidao' => NULL),
	// 					)
	// 				);
	// 	$res = array();
	// 	$res = $this->Nhanvanban->find('all',$conds);
	// 	$this->set(compact('res'));
	//     $this->set('_serialize', 'res');		
	// }
	function	get_files($id = null)

	{
		$this->Vanban->bindModel(array(
			'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),
							'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')
							),
		), false);
		$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id )));
		$this->loadModel('Filevanban');
		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
		$path = substr($path, 1, strlen($path)-1);
		$path = WWW_ROOT . $path;
		$file_moi = $vanban['Filevanban']['ten_moi'];
		$file_cu = $vanban['Filevanban']['ten_cu'];
		$file_contents = file_get_contents($path.$file_moi, true);
		$this->layout = null;
		Configure::write('debug',0);
		header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $file_cu .'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path.$file_moi));
		ob_clean();
		flush();
		readfile($path.$file_moi);
		exit;
	}
}
?>