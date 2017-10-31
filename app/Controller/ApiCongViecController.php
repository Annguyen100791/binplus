 <?php
 App::uses('CongviecController', 'Controller');

 class ApiCongviecController extends CongviecController {

 	public function beforeFilter() {
 		if ($this->request->params['action'] == 'get_files') {
			$this->Auth->allow();
		}
		else
		{
			parent::beforeFilter();
		}
	}

 	/*********************************************************
 	*View comment
 	**********************************************************/
 	public function apiComment() {

 		$congviec_id = isset($this->request->data['congviec_id']) ? $this->request->data['congviec_id'] : '';

 		$res = array();
 		$itRes = array();

		$this->loadModel('CongviecComment');
		$comments = $this->CongviecComment->find('all', 
			array('conditions' => array('congviec_id' => $congviec_id), 
				'order' => 'CongviecComment.id ASC'));

		foreach ($comments as $item) {
			$itRes['nguoibinhluan_id'] = $item['CongviecComment']['nguoibinhluan_id'];
			$itRes['noi_dung'] = $item['CongviecComment']['noi_dung'];
			$itRes['ngay_binhluan'] = $item['CongviecComment']['ngay_binhluan'];
			$itRes['full_name'] = $item['NguoiBinhluan']['full_name'];

			array_push($res, $itRes);
		}


		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}


 	/*********************************************************
	* Download file van ban
	**********************************************************/
 	function	get_files($id = null){

 		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),
							'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')
							),
		), false);
		$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.vanban_id' => $id )));
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

 	/*********************************************************
	*API cong viec duoc giao
	**********************************************************/
	public function apiDuocgiao() {

		$res = array();
		$conds = array();

		$opt = isset($this->request->data['opt']) ? $this->request->data['opt'] : '';
		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';
		$limit = isset($this->request->data['limit']) ? $this->request->data['limit'] : '50';
		
		$this->loadModel('Congviec');

		$this->loadModel('User');

		// if(!$this->check_permission('CongViec.thuchien')) {
		// 	$res['message'] = ('Bạn không có quyền thực hiện công việc được giao. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		// }

		$this->Congviec->bindModel(array(

			'belongsTo'	=> array(

				'CongviecChinh'	=>	array('className' 	=> 'Congviec', 'foreignKey' 	=> 'congviecchinh_id', 'fields' => 'mucdo_hoanthanh'),

				'Vanban'	=>	array('className' 	=> 'Vanban', 'foreignKey' 	=> 'vanban_id', 'fields' => 'trich_yeu'),

				)

			));

		$this->Congviec->bindModel(

 			array(

 				'belongsTo' => array(

 					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')),

 					'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')

 					)

 				)

 			, false);

		$this->Congviec->recursive = 1;

		$this->loadModel('Nhanvien');

		$this->Nhanvien->unbindModel(array(

			'hasAndBelongsToMany' => array('Nhomquyen'),'belongsTo'=>array('User')

			));

		switch($opt) {

			case 'all':
				$conds = array('conditions' => array(

					'Congviec.nguoinhan_id' => $nhanvien_id),

					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'instant':
				$conds = array('conditions' => array(
					'Congviec.nguoinhan_id' => $nhanvien_id,
					'Congviec.mucdo_hoanthanh <'	=>	10,
					'Congviec.tinhchat_id' =>11
					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'baocao':
				$conds = array('conditions' => array(
					'Congviec.nguoinhan_id' => $nhanvien_id,
					'Congviec.loaicongviec_id' => 1
					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'progressing':
				$conds = array('conditions' => array(

					'Congviec.nguoinhan_id' => $nhanvien_id,
					'OR'=>array(
						array(
							'Congviec.mucdo_hoanthanh <'	=>	10,
							'Congviec.ngay_ketthuc <'	=>	date('Y-m-d', time())
							),
						array(
							'Congviec.mucdo_hoanthanh <'	=>	10,
							'Congviec.ngay_ketthuc >='	=>	date('Y-m-d', time())
							))),

					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'unfinished':
				$conds = array('conditions' => array(
					'Congviec.nguoinhan_id' => $nhanvien_id,
					'Congviec.mucdo_hoanthanh <'	=>	10,
					'Congviec.ngay_ketthuc <'	=>	date('Y-m-d', time())),

					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'finished':
				$conds = array('conditions' => array(
					'Congviec.nguoinhan_id' => $nhanvien_id,
					'Congviec.mucdo_hoanthanh '	=>	10),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;
				
			case 'commentmoi':
				$conds = array('conditions' => array(

					'Congviec.nguoinhan_id' => $nhanvien_id,

					'Congviec.giaoviec_comment'	=>	1,

					'Congviec.parent_id'=>NULL

					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));
				$data =  $this->Congviec->find('all', $conds );
				break;	
		}

		// if(empty($res)){
		// 	$res = $data;
		// }
		$i=0;
		foreach ($data as $n => $v){
			$res[$i]['id'] = $v['Congviec']['id'];
 			$res[$i]['ten_congviec'] = $v['Congviec']['ten_congviec'];
 			$res[$i]['ten_tinhchat'] = $v['Tinhchatcongviec']['ten_tinhchat'];
 			$res[$i]['ngay_batdau'] = $v['Congviec']['ngay_batdau'];
 			$res[$i]['ngay_ketthuc'] = $v['Congviec']['ngay_ketthuc'];
 			$res[$i]['noi_dung'] = $v['Congviec']['noi_dung'];
 			$res[$i]['nguoi_giaoviec'] = $v['NguoiGiaoviec']['full_name'];
 			$res[$i]['ngay_giao'] = $v['Congviec']['ngay_giao'];
 			$res[$i]['mucdo_hoanthanh'] = $v['Congviec']['mucdo_hoanthanh'];
 			$res[$i]['vb_trich_yeu'] = $v['Vanban']['trich_yeu'];
 			$res[$i]['vb_so_hieu'] = $v['Vanban']['so_hieu'];
 			$res[$i]['vb_ngay_phathanh'] = $v['Vanban']['ngay_phathanh'];
 			$res[$i]['vb_noi_gui'] = $v['Vanban']['noi_gui'];
 			$res[$i]['nguoi_nhanviec'] = $v['NguoiNhanviec']['full_name'];
 			$res[$i]['vanban_id'] = $v['Congviec']['vanban_id'];
 			$i++;
		}
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}

 	/***************************************************
	* API cong viec da giao (All)
	***************************************************/
	public function apiDagiao(){

		$res = array();

		$opt = isset($this->request->data['opt']) ? $this->request->data['opt'] : '';
		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';
		$limit = isset($this->request->data['limit']) ? $this->request->data['limit'] : '';
		
		// Update check
		// if(!$this->check_permission('CongViec.khoitao')) {

		// 	$res['message'] = "Bạn không có quyền giao việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.";
		// }

		// Define to use model Congviec
		$this->loadModel('Congviec');
		$this->Congviec->bindModel(array(

			'belongsTo'	=> array(

				'CongviecChinh'	=>	array('className' 	=> 'Congviec', 'foreignKey' 	=> 'congviecchinh_id', 'fields' => 'mucdo_hoanthanh'),

				'Vanban'	=>	array('className' 	=> 'Vanban', 'foreignKey' 	=> 'vanban_id', 'fields' => 'trich_yeu'),

				)

			));
		$this->Congviec->bindModel(

 			array(

 				'belongsTo' => array(

 					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')),

 					'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')

 					)

 				)

 			, false);

		/*****************************************************/
		switch($opt) {

			case 'all':

				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => ''),

					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'instant':
				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => '',

					'Congviec.mucdo_hoanthanh <'	=>	10,

					'Congviec.tinhchat_id' => 11
					),

					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'baocao': //hỏi lại cái này
				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => '',

					'Congviec.loaicongviec_id' => 1

					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$this->Congviec->bindModel(array(

					'hasMany' => array(

						'Filecongviec' => array('foreignKey' => 'congviec_id')

						)

					));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'progressing':
				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => NULL,

					'Congviec.mucdo_hoanthanh <'	=>	10,

					'Congviec.ngay_ketthuc >='	=>	date('Y-m-d', time())
					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));
				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'unfinished':
				//code chị Hà

				/*$conds = array(
					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => '',

					'Congviec.mucdo_hoanthanh <'	=>	10,

					'Congviec.ngay_ketthuc <'	=>	date('Y-m-d', time())

					);*/  

				$conds = array('conditions' => array(
					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.mucdo_hoanthanh <'	=>	10,

					'Congviec.ngay_ketthuc <'	=>	date('Y-m-d', time())
					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'finished':
				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.parent_id' => '',

					'Congviec.mucdo_hoanthanh'	=>	10
					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));

				$data =  $this->Congviec->find('all', $conds );
				break;

			case 'commentmoi':
				$conds = array('conditions' => array(

					'Congviec.nguoi_giaoviec_id' => $nhanvien_id,

					'Congviec.giaoviec_comment'	=>	1,

					'Congviec.parent_id'=>NULL

					),
					'limit' => $limit,
					'order' => array('Congviec.ngay_giao DESC'));
				$data =  $this->Congviec->find('all', $conds );
				break;	
			}

			// if(empty($res)){
			// 	$res = $data;
			// }
			$i=0;
			foreach ($data as $n => $v){
				$res[$i]['id'] = $v['Congviec']['id'];
	 			$res[$i]['ten_congviec'] = $v['Congviec']['ten_congviec'];
	 			$res[$i]['ten_tinhchat'] = $v['Tinhchatcongviec']['ten_tinhchat'];
	 			$res[$i]['ngay_batdau'] = $v['Congviec']['ngay_batdau'];
	 			$res[$i]['ngay_ketthuc'] = $v['Congviec']['ngay_ketthuc'];
	 			$res[$i]['noi_dung'] = $v['Congviec']['noi_dung'];
	 			$res[$i]['nguoi_giaoviec'] = $v['NguoiGiaoviec']['full_name'];
	 			$res[$i]['ngay_giao'] = $v['Congviec']['ngay_giao'];
	 			$res[$i]['mucdo_hoanthanh'] = $v['Congviec']['mucdo_hoanthanh'];
	 			$res[$i]['vb_trich_yeu'] = $v['Vanban']['trich_yeu'];
	 			$res[$i]['vb_so_hieu'] = $v['Vanban']['so_hieu'];
	 			$res[$i]['vb_ngay_phathanh'] = $v['Vanban']['ngay_phathanh'];
	 			$res[$i]['vb_noi_gui'] = $v['Vanban']['noi_gui'];
	 			$res[$i]['nguoi_nhanviec'] = $v['NguoiNhanviec']['full_name'];
	 			$res[$i]['vanban_id'] = $v['Congviec']['vanban_id'];
	 			$i++;
			}
			$this->set(compact('res'));
			$this->set('_serialize', 'res');
		}


	/*********************************************
	* Xem chi tiet cong viec
	**********************************************/
 	public	function	apiView()
 	{
 		$res = array();

 		$congviec_id = isset($this->request->data['congviec_id']) ? $this->request->data['congviec_id'] : '';
 		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';

 		$this->loadModel('Congviec');

 		$this->Congviec->bindModel(

 			array(

 				'belongsTo' => array(

 					'Vanban'	=>	array('fields' => array('trich_yeu', 'so_hieu', 'ngay_phathanh', 'noi_gui')),

 					'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')

 					)

 				)

 			, false);

 		$this->Congviec->bindModel(array(

 			'hasMany' => array(

 				'Filecongviec' => array('foreignKey' => 'congviec_id')

 				)

 			));

 		$data = $this->Congviec->find('first', array(

 			'conditions'	=>	array(

 				"Congviec.id"	=>	$congviec_id,

 				"OR"	=>	array(

 					'nguoi_giaoviec_id'	=>	$nhanvien_id,

 					'nguoinhan_id'		=>	$nhanvien_id

 					)
 				)
 			));
 		if(empty($data))
 		{
 			$res = null;
 			$this->set(compact('res'));
 			$this->set('_serialize', 'res');

 		}else{

 			if($data['Congviec']['nguoinhan_id']==$nhanvien_id){

 				$this->Congviec->updateAll(

 					array('is_dadoc_comment' => 0),

 					array('Congviec.congviecchinh_id' => $data['Congviec']['congviecchinh_id'],'Congviec.nguoinhan_id' => $nhanvien_id)

 					);

 			}elseif($data['Congviec']['nguoi_giaoviec_id']==$nhanvien_id){

 				$this->Congviec->updateAll(

 					array('giaoviec_comment' => 0),

 					array('Congviec.congviecchinh_id' => $data['Congviec']['congviecchinh_id'],'Congviec.nguoi_giaoviec_id' => $nhanvien_id)

 					);
 			}
 		}

 		// Comment
 		// $this->loadModel('CongviecComment');

 		// $comments = $this->CongviecComment->find('all'
 		// 	, array('conditions' => array('congviec_id' => $congviec_id)
 		// 	, 'order' => 'CongviecComment.id ASC'));


 		if(empty($res)){
 			$res['id'] = $data['Congviec']['id'];
 			$res['ten_congviec'] = $data['Congviec']['ten_congviec'];
 			$res['ten_tinhchat'] = $data['Tinhchatcongviec']['ten_tinhchat'];
 			$res['ngay_batdau'] = $data['Congviec']['ngay_batdau'];
 			$res['ngay_ketthuc'] = $data['Congviec']['ngay_ketthuc'];
 			$res['noi_dung'] = $data['Congviec']['noi_dung'];
 			$res['nguoi_giaoviec'] = $data['NguoiGiaoviec']['full_name'];
 			$res['ngay_giao'] = $data['Congviec']['ngay_giao'];
 			$res['mucdo_hoanthanh'] = $data['Congviec']['mucdo_hoanthanh'];
 			$res['vb_trich_yeu'] = $data['Vanban']['trich_yeu'];
 			$res['vb_so_hieu'] = $data['Vanban']['so_hieu'];
 			$res['vb_ngay_phathanh'] = $data['Vanban']['ngay_phathanh'];
 			$res['vb_noi_gui'] = $data['Vanban']['noi_gui'];
 			$res['nguoi_nhanviec'] = $data['NguoiNhanviec']['full_name'];
 			$res['vanban_id'] = $data['Congviec']['vanban_id'];
 			// $res['id'] = $data;	

 		}
 		$this->set(compact('res'));
 		$this->set('_serialize', 'res');
 	}
 	public	function	add_comment()
	{
		$res = array();
		$this->loadModel('CongviecComment');
		if(!empty($this->request->data))
		{
			$data = array(
				'congviec_id'	=>	$this->request->data['congviec_id'],
				'nguoibinhluan_id'	=>	$this->request->data['nhanvien_id'],
				'noi_dung'		=>	$this->request->data['noi_dung'],
				'ngay_binhluan'	=>	date('Y-m-d H:i:s', time())
			);
			if($this->CongviecComment->save($data)){
				$this->loadModel('Congviec');
				//Update tình trang da_doc_comment ve 1: Chua doc, có comment moi; 0: da doc het comment; giaoviec_comment=1: người giao việc chưa đọc comment
				$this->Congviec->query("UPDATE congviec_thongtin SET is_dadoc_comment='1',giaoviec_comment='1' 
					WHERE id=". $this->request->data['congviec_id'] );
				$res =1;
			}
			else
				$res =0;
		}
		$this->set(compact('res'));
 		$this->set('_serialize', 'res');
	}
}