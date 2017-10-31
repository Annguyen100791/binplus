<?php

class NhanvienController extends AppController {



	public $uses = array('Nhanvien', 'User');

	

	protected $paginate = array(

        'limit' => 20,

        );

	

	public $components = array('ImageResizer');

	

	public	function	beforeFilter()

	{

		parent::beforeFilter();

		

	}

	

	public function index()

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException('Bạn không có quyền quản lý nhân viên. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

		

		$this->Nhanvien->bindModel(array(

			'belongsTo' 	=> array(

					'Chucdanh'	=>	array('className'	=>	'Chucdanh', 

										  'fields'		=>	array('ten_chucdanh')),

					'Phong'		=>	array('className'	=>	'Phong',

										  'fields'		=>	array('Phong.id', 'Phong.ten_phong'),

										  )

					)

					), false); 

					

					

		$conds = array();

		

		$ds_phong = $this->listPhong('HeThong.nhanvien');

		

		if($ds_phong != -1)

		{

			$conds['phong_id'] = $ds_phong;

		}

		

		if(!empty($this->data))

		{

			if(!empty($this->data['Nhanvien']['ten']))

			{

				$this->passedArgs['ten'] = $this->data['Nhanvien']['ten'];

				$conds['ten LIKE']	=	'%' . $this->data['Nhanvien']['ten'] . '%';

			}

			

			if(!empty($this->data['Nhanvien']['chucdanh_id']))

			{

				$this->passedArgs['chucdanh_id'] = $this->data['Nhanvien']['chucdanh_id'];

				$conds['chucdanh_id']	=	$this->data['Nhanvien']['chucdanh_id'];

			}

			

			if(!empty($this->data['Nhanvien']['phong_id']))

			{

				$this->passedArgs['phong_id'] = $this->data['Nhanvien']['phong_id'];

				$conds['phong_id']	=	$this->data['Nhanvien']['phong_id'];

			}

			

			

		}elseif(isset($this->passedArgs))

		{

			if(!empty($this->passedArgs['ten']))

				$conds['ten LIKE'] = '%' . $this->passedArgs['ten'] . '%';

			if(!empty($this->passedArgs['chucdanh_id']))

				$conds['chucdanh_id'] = $this->passedArgs['chucdanh_id'];

			if(!empty($this->passedArgs['phong_id']))

				$conds['phong_id'] = $this->passedArgs['phong_id'];

		}

		

		$this->paginate['order'] = array('ten' => 'ASC', 'ten_lot' => 'ASC', 'ho' => 'ASC');

		$ds =  $this->paginate('Nhanvien', $conds);

		

		if(empty($ds))

		{

			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');

		}

		$this->set('ds', $ds);

		if(!$this->RequestHandler->isAjax())

		{

			$this->set('title_for_layout', 'Quản lý Nhân viên');

			$this->render('index');

		}else

		{

			$this->viewPath = 'Elements' . DS . 'Common';

			$this->render('nhansu');

		}

	}

	

	

	public	function	nhansu()

	{

		//pr($_SESSION);

		$this->set('title_for_layout', 'Danh sách nhân viên');

		/*

		$this->loadModel('Phong');

		$this->Nhanvien->bindModel(array(

			'belongsTo' 	=> array(

					'Chucdanh'	=>	array('className'	=>	'Chucdanh', 

										  'fields'		=>	array('ten_chucdanh'))

					)

					), false); 

		$ds =  $this->Phong->getList();

		

		if(empty($ds))

		{

			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');

		}else{

			$this->Nhanvien->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen'), 'belongsTo' => array('User')), false);

			$this->Nhanvien->bindModel(array('belongsTo' 	=> array('Chucdanh')), false); 

			for($i = 0; $i < $size=count($ds); $i++)

			{

				$nhanvien = $this->Nhanvien->find('all', 

					array(

						'conditions' 	=> 	array('phong_id' => $ds[$i]['Phong']['id'], 'Nhanvien.tinh_trang' => 1),

						'order' 		=> 	array('nguoi_quanly' => 'DESC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC')

						)

					);

				$ds[$i]['Nhanvien'] = $nhanvien;

				

			}

		}

//pr($ds); die();

		$this->set('ds', $ds);

		*/

	}

	

	

	

	public	function	add()

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException('Bạn không có quyền quản lý nhân viên. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

			

		if(!empty($this->request->data)) 

		{

			if(!empty($this->request->data['Nhanvien']['ho_ten']))

			{

				$this->request->data['User']['fullname'] = $this->request->data['Nhanvien']['ho_ten'];

				$this->request->data['Nhanvien']['email'] = $this->request->data['User']['email'];

				$hoten = explode(" ", trim($this->request->data['Nhanvien']['ho_ten']));

				$count = count($hoten);

				if($count <= 1)

				{

					$this->request->data['Nhanvien']['ten'] = $hoten[0];

					$this->request->data['Nhanvien']['ho'] = '';

					$this->request->data['Nhanvien']['ten_lot'] = '';

				}else

				{

					$this->request->data['Nhanvien']['ho'] = $hoten[0];

					$this->request->data['Nhanvien']['ten'] = $hoten[$count-1];

					$this->request->data['Nhanvien']['ten_lot'] = '';

					if($count > 2)

					{

						array_pop($hoten);

						array_shift($hoten);

						$this->request->data['Nhanvien']['ten_lot'] = implode(" ", $hoten);

					}

				}

				$this->request->data['User']['enabled'] = $this->request->data['Nhanvien']['tinh_trang'];

				if($this->Nhanvien->saveAssociated($this->request->data))

				{

					

					$this->Session->setFlash('Thêm mới thành công.', 'flash_success');

					$this->redirect('/nhanvien/add');

				}else

				{

					$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');

					$this->redirect('/nhanvien/add');

				}

			}

        }else

		{

			$this->loadModel('Phong');

			$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');

			

			$gioi_tinh = $this->Nhanvien->gioi_tinh;

			$tinh_trang = $this->Nhanvien->tinh_trang;

			

			$this->loadModel('Chucdanh');

			$chucdanh = $this->Chucdanh->find('list', array(

								'conditions' => array('Chucdanh.enabled' => 1),

								'fields'	=>	array('Chucdanh.id', 'Chucdanh.ten_chucdanh'),

								'order'		=>	'thu_tu ASC'));

			

			$nhomquyen = $this->Nhanvien->Nhomquyen->find('list', array('fields' => array('id', 'ten_nhomquyen')));

			$this->set(compact("chucdanh", "gioi_tinh", "tinh_trang", "phong", "nhomquyen"));

		}

	}

	

	

	public	function	edit($id = null)

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException('Bạn không có quyền quản lý nhân viên. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

			

		if(!empty($this->request->data)) 

		{

			if(!empty($this->request->data['Nhanvien']['ho_ten']))

			{

				$this->request->data['User']['fullname'] = $this->request->data['Nhanvien']['ho_ten'];

				$this->request->data['Nhanvien']['email'] = $this->request->data['User']['email'];

				

				if(!empty($this->request->data['User']['password1']) && $this->request->data['User']['password1'] == $this->request->data['User']['password2'])

					$this->request->data['User']['password'] = $this->request->data['User']['password1'];

				

				$hoten = explode(" ", trim($this->request->data['Nhanvien']['ho_ten']));

				$count = count($hoten);

				if($count <= 1)

				{

					$this->request->data['Nhanvien']['ho'] = '';

					$this->request->data['Nhanvien']['ten_lot'] = '';

					$this->request->data['Nhanvien']['ten'] = $hoten[0];

				}else

				{

					$this->request->data['Nhanvien']['ho'] = $hoten[0];

					$this->request->data['Nhanvien']['ten_lot'] = '';

					$this->request->data['Nhanvien']['ten'] = $hoten[$count-1];

					if($count > 2)

					{

						array_pop($hoten);

						array_shift($hoten);

						$this->request->data['Nhanvien']['ten_lot'] = implode(" ", $hoten);

					}

				}

				$this->request->data['User']['enabled'] = $this->request->data['Nhanvien']['tinh_trang'];

				

				$anh = null;

				

				if(!empty($this->request->data['Nhanvien']['anh_the']))

				{

					$anh = $this->Nhanvien->field('anh_the', array('Nhanvien.id' => $this->request->data['Nhanvien']['id']));

				}

				//pr($this->request->data); die();

				if($this->Nhanvien->saveAssociated($this->request->data))

				{

					if(!empty($anh))

					{

						$output = str_replace("/", DS, Configure::read('NhanVien.avatar_path'));

						$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $anh;

						unlink($output);

					}

					

					$this->Session->setFlash('Hiệu chỉnh nhân viên thành công.', 'flash_success');

				}else

				{

					$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');

					

				}

				$this->redirect('/nhanvien');

			}

        }else

		{

			$data = $this->Nhanvien->read(null, $id);

			

			if(empty($data))

				throw new InternalErrorException('Không tìm thấy nhân viên để hiệu chỉnh. Vui lòng chọn nhân viên khác.');

				

			$this->loadModel('Phong');

			$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');

			

			$gioi_tinh = $this->Nhanvien->gioi_tinh;

			$tinh_trang = $this->Nhanvien->tinh_trang;

			

			$this->loadModel('Chucdanh');

			$chucdanh = $this->Chucdanh->find('list', 

					array(

						'conditions' 	=> array('Chucdanh.enabled' => 1),

						'fields'		=>	array('Chucdanh.id', 'Chucdanh.ten_chucdanh'),

						'order'			=>	'thu_tu ASC'));

			

			$nhomquyen = $this->Nhanvien->Nhomquyen->find('list', array('fields' => array('id', 'ten_nhomquyen')));

			$this->set(compact("chucdanh", "gioi_tinh", "tinh_trang", "phong", "nhomquyen"));

			

			$this->data = $data;

		}

	}

	

	public	function	delete()

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException('Bạn không có quyền quản lý nhân viên. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

			

		$this->layout = null;

		if(!empty($this->data))

		{

			$success = 0;

			$ids = explode(",", $this->data['v_id']);

			foreach($ids as $k=>$v)

			{

				if($this->Nhanvien->delete($v))	$success++;

			}

			if($success > 0)

			{

				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');

				$this->redirect('/nhanvien');

			}

		}

		

		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');

		$this->redirect('/nhanvien');

	}

	

	

	public	function	active($id)

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException('Bạn không có quyền quản lý nhân viên. Vui lòng liên hệ quản trị để biết thêm chi tiết.');

			

		$data = $this->Nhanvien->read(array('tinh_trang', 'Nhanvien.user_id'), $id);

		$status = 0;

		$success = 0;

		

		if(!empty($data))

		{

			if($data['Nhanvien']['tinh_trang'] == 0)

			{

				$status = 1;

			}

			else

				$status = 0;

			$data['Nhanvien']['tinh_trang'] = $status;

			$data['User']['enabled'] = $status;

			

			$data['Nhanvien']['id'] = $id;

			$data['User']['id'] = $data['Nhanvien']['user_id'];

			if($this->Nhanvien->saveAssociated($data))

				$success = 1;

		}

		

		die(json_encode(array(

			'success'	=>	$success,

			'id'		=>	$id,

			'status'	=>	$status

		)));

	}

	

	

	public	function	nguoi_congtac()

	{

		$this->layout = null;

		$arr = array();

///		pr($this->request->query);

		if(!empty($this->request->query['q']))

		{

			$this->Nhanvien->recursive = -1;

			$data = $this->Nhanvien->find('list', array('conditions' => array("full_name  LIKE"	=>	"%" . $this->request->query['q'] . "%"), 'fields' => array('Nhanvien.id', 'Nhanvien.full_name'), 'limit' => 10));

			

			

			foreach($data as $k=>$v):

				array_push($arr, array("id" => $k, "name" => $v));

			endforeach;

			

		}

		echo json_encode($arr);

		die();

	}

	

	

	public	function	profile()

	{

		$id = $this->Auth->user('nhanvien_id');
		$this->Nhanvien->query("UPDATE nhansu_nhanvien SET last_view = '".date("Y-m-d H:i:s")."' WHERE id =".$id);
		if(empty($this->request->data))
		{
			$data = $this->Nhanvien->read(null, $id);
			if(empty($data))

				throw new InternalErrorException('Không tìm thấy nhân viên này.');

			

			$this->data = $data;

		}else

		{

			$data['Nhanvien'] = array(

				'id'			=>	$id,

				'email'		=>	$this->request->data['Nhanvien']['email'],

				'dia_chi'		=>	$this->request->data['Nhanvien']['dia_chi'],

				'dien_thoai'	=>	$this->request->data['Nhanvien']['dien_thoai'],

				'dien_thoai_noi_bo'	=>	$this->request->data['Nhanvien']['dien_thoai_noi_bo'],

				'dien_thoai_nha_rieng'	=>	$this->request->data['Nhanvien']['dien_thoai_nha_rieng'],

				'signature'		=>	$this->request->data['Nhanvien']['signature'],
				

				

			);

			if($this->Nhanvien->save($data)){
			$this->Nhanvien->query("UPDATE nhansu_nhanvien SET last_update = '".date("Y-m-d H:i:s")."' WHERE id = ".$id);	

				die(json_encode(array('success' => true, 'message' => 'Thông tin nhân viên đã được cập nhật thành công.')));
			}
			else{

				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi.')));
			}

		}

		

		

	}

	public	function	view($id = null)

	{

		if(empty($id))

		$id = $this->Auth->user('nhanvien_id');

		$data = $this->Nhanvien->read(null, $id);

		

		if(empty($data))

			throw new InternalErrorException('Không tìm thấy nhân viên này. Vui lòng chọn nhân viên khác.');

			

		$this->set('title_for_layout', 'Xem thông tin Nhân viên');

		

		$this->data = $data;

		

		$this->loadModel('Phong');

		$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');

		

		$gioi_tinh = $this->Nhanvien->gioi_tinh;

		$tinh_trang = $this->Nhanvien->tinh_trang;

		

		$this->loadModel('Chucdanh');

		$chucdanh = $this->Chucdanh->find('list', array('conditions' => array('Chucdanh.enabled' => 1),

														'fields'	=>	array('Chucdanh.id', 'Chucdanh.ten_chucdanh'),

														'order'		=>	'thu_tu ASC'));

		

		$nhomquyen = $this->Nhanvien->Nhomquyen->find('list', array('fields' => array('id', 'ten_nhomquyen')));

		$quyen = $this->Nhanvien->dsQuyen($id);

		

		$this->loadModel('Quyen');

		$nhom_chucnang = $this->Quyen->prefix;

		

		$this->set(compact("chucdanh", "gioi_tinh", "tinh_trang", "phong", "nhomquyen", "quyen", "nhom_chucnang"));

	}

	

	public	function	search()

	{

		$this->loadModel('Phong');

		$this->set('phong', $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---'));

		

		$this->loadModel('Chucdanh');

		$chucdanh = $this->Chucdanh->find('list', array('conditions' => array('Chucdanh.enabled' => 1),

														'fields'	=>	array('Chucdanh.id', 'Chucdanh.ten_chucdanh'),

														'order'		=>	'thu_tu ASC'));

		$this->set(compact("chucdanh"));

	}

	

	

	public	function	upload()

	{

		if(!$this->check_permission('HeThong.nhanvien'))

			throw new InternalErrorException();

			

		$filename = time() . '_' . $this->request->data['Nhanvien']['avatar']['name'];

		$output = str_replace("/", DS, Configure::read('NhanVien.avatar_path'));

		$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $filename;

		

		$f = $this->ImageResizer->resizeImage($this->request->data['Nhanvien']['avatar']['tmp_name'], array(

																						  'maxWidth'	=>	90,

																						  'maxHeight'	=>	120,

																						  'output'		=>	$output,

																						  'cropZoom'	=>	true,

																						  'deleteSource'=>	true

																						  ));

		if($f)

			die(json_encode(array('success' => true, 'filename' => $filename)));

		else

			die(json_encode(array('success' => false, 'message' => 'Lỗi')));

	}

	

	public	function	nhanviennhan($action)

	{

		$this->loadModel('Group');

		$groups = $this->Group->find('list', array(

			'conditions' => array('user_id' => $this->Auth->user('nhanvien_id')),

			'fields' => array('id', 'ten_nhom')

			));

		$this->set(compact('action', 'groups'));

	}

	public	function	dinhhuong_nhanvb($action)

	{

		$this->loadModel('Group');

		$groups = $this->Group->find('list', array(

			'conditions' => array('user_id' => $this->Auth->user('nhanvien_id')),

			'fields' => array('id', 'ten_nhom')

			));

		$this->set(compact('action', 'groups'));

	}

	

	public function online()

	{

		$ds_phong = $this->listPhong('NhanSu.online');

		//pr($ds_phong);die();

		$this->User->bindModel(array(

			'hasOne'	=>	array('Nhanvien')

		));

		$conds = array("is_online" => 1);

		if($ds_phong !== -1)						

			$conds['Nhanvien.phong_id'] = $ds_phong;

		//pr($conds);die();

		$this->loadModel('User');

		$online = $this->User->find('all', array(

				'conditions'	=>	$conds,

				'fields'		=>	array('Nhanvien.id', 'fullname'),

				'order'			=>	'last_action DESC'

				));

		//pr($online);die();

		$this->set('online',$online);

	}

	public	function	listnv($action)

	{

		$ds = $this->treeNhanvien($action);
		die(json_encode(array(

			'title'		=>	'<b>' . Configure::read('Site.company_name') .'</b>',

			'expand'	=>	true,

			'isFolder'	=>	true,

			'isroot'	=> 	true,

			'children'	=>	$ds

			

		)));

	}

	

	public	function	canhan($id = null)

	{

		/*if(!$this->check_permission('ThongBao.nhap'))

			throw new InternalErrorException();*/

		$f = false;

		$id = $this->Auth->user('nhanvien_id');

		//pr($this->request->data);die();

		

		if (!empty($this->request->data)) 

		{

			$this->request->data['Canhan']['van_ban'] = isset($this->request->data['Canhan']['van_ban']) ? 1 : 0;

			$this->request->data['Canhan']['tin_nhan'] = isset($this->request->data['Canhan']['tin_nhan']) ? 1 : 0;

			$this->request->data['Canhan']['cong_viec'] = isset($this->request->data['Canhan']['cong_viec']) ? 1 : 0;

			//pr($this->Auth->user('nhanvien_id'));die();

			

			$this->Nhanvien->query("UPDATE nhansu_nhanvien 

											SET meg_vanban = " . $this->request->data['Canhan']['van_ban'] . " ,

											    meg_tinnhan = " . $this->request->data['Canhan']['tin_nhan'] . " ,

												meg_congviec = " . $this->request->data['Canhan']['cong_viec'] . "

															   

									WHERE user_id = ". $this->Auth->user('nhanvien_id')

									);

			$f = true;

			if ($f) 

			{

				$this->Session->setFlash('Lưu dữ liệu thành công.', 'flash_success');

				$this->redirect('/nhanvien');									  

			} else {

				$this->Session->setFlash('Đã phát sinh lỗi khi lưu dữ liệu. Vui lòng thử lại.', 'flash_error');

				$this->redirect('/nhanvien');

					

			}

        }

		else

		{

			$data = $this->Nhanvien->read(null, $id);

			//pr($data);die();

			$this->data = $data;

			

		}

		

	}
	public	function	phanluong_nhanvb($action)
	{
		$this->loadModel('Group');
		$groups = $this->Group->find('list', array(
			'conditions' => array('user_id' => $this->Auth->user('nhanvien_id')),
			'fields' => array('id', 'ten_nhom')
			));
		$this->set(compact('action', 'groups'));
	}
	public	function	autocomplete()

	{

		$this->layout = null;

		$ret = array(

			'total'	=>	0,

			'results'	=>	array()

		);

		$q = $this->request->query['q'];

		

		if(!empty($q))

		{

			$ret['total'] = $this->Nhanvien->find('count', array('conditions' => array("full_name LIKE" => $q . '%'), 'recursive' => -1));

			

			

			$data = $this->Nhanvien->find('all', array('conditions' => array("full_name LIKE" => $q . '%'), 'order' => array('full_name' => 'ASC'), 'fields' => array('id', 'full_name', 'phong_id'), 'recursive' => -1, 'limit' => $this->request->query['page_limit'], 'page' => $this->request->query['page']));

			if(!empty($data))

			{

				if(!empty($data))

				foreach($data as $item)

					array_push($ret['results'], array('id' => 'nv-' . $item['Nhanvien']['id'], 'text' => $item['Nhanvien']["full_name"], 'phong_id' => $item['Nhanvien']["phong_id"]));

			}

		}

		die(json_encode($ret));

	}

}