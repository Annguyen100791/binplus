<?php
class CalendarsController extends AppController {
	var $uses = array('Lichlamviec', 'Lichcongtac');

	const TEMPLATE = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Header><AuthHeader xmlns="http://tempuri.org/"><Username>ws_danang</Username><Password>dng123</Password></AuthHeader></soap:Header><soap:Body><sendsms xmlns="http://tempuri.org/"><sdt>{{sdt}}</sdt><infor>{{message}}</infor></sendsms></soap:Body></soap:Envelope>';

	public $components = array(
		'Bin'
    );

	public function index($phamvi = -1)
	{
		if(!$this->check_permission('LichCongTac.danhsach'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách lịch hoạt động. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->set('title_for_layout', 'Xem chi tiết lịch hoạt động');
		$ds_phamvi = $this->Lichlamviec->pham_vi;
		$this->set(compact(array('phamvi', 'ds_phamvi')));
	}

	public	function	birthday()
	{
		$this->set('title_for_layout', 'Danh sách ngày sinh nhật');
	}

	public	function	birthday_list()
	{

		if(!$this->check_permission('LichCongTac.birthday'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách ngày sinh nhật. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$dStartDate = date('m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));
		$dEndDate = date('m-d', mktime(0, 0, 0, date("m")+1, 1, date("Y")));
		$Year = date('Y');
		if(!empty($this->request->data['start']))
			{
				//pr(date("Y-m-d", $this->request->data['start']));die();
				$dStartDate = date("m-d", $this->request->data['start']);
				//pr(date("m",$this->request->data['start']));
				//pr($dStartDate);die;
				if (date("m",$this->request->data['start']) == '12')
				{
					$dStartDate = "01-01";
				}
				else
				{
					$Year = date("Y",$this->request->data['start']);
				}
			}
		//pr($dStartDate);
		if(!empty($this->request->data['end']))
		{
			$dEndDate = date("m-d", $this->request->data['end']);
			//pr(date("m",$this->request->data['end'])); die();
			if (date("m",$this->request->data['end']) == '01')
			{
				$dEndDate = "12-31";
			}
			/*else
			{
				$Year = date("Y",$this->request->data['end']);
			}*/
		}
		//pr($dStartDate);
		//pr($dEndDate); die();
		$this->layout = null;
		$arr = array();
		$view = new View($this);
        $time = $view->loadHelper('Time');
		$this->loadModel('Nhanvien');
		$this->Nhanvien->recursive = -1;
		$events = $this->Nhanvien->find('all',
					array(
						 'conditions' 	=> array(
						 						"DATE_FORMAT(Nhanvien.ngay_sinh, '%m-%d') >=" 	=> $dStartDate,
						 						"DATE_FORMAT(Nhanvien.ngay_sinh, '%m-%d') <="	=> $dEndDate,
												"Nhanvien.tinh_trang"							=>	1),
						 'fields'		=>	array('id', 'full_name', 'ngay_sinh')
						 )
					);

		//pr($events);die();
		/*pr(array(
						 						"DATE_FORMAT(Nhanvien.ngay_sinh, '%m-%d') >=" 	=> $dStartDate,
						 						"DATE_FORMAT(Nhanvien.ngay_sinh, '%m-%d') <="	=> $dEndDate,
												"Nhanvien.tinh_trang"							=>	1));die();*/

		if(!empty($events))
		{
			foreach($events as $item)
			{
				$exp = explode("-", $item['Nhanvien']['ngay_sinh']);
				$tmp = array('id'	=>	$item['Nhanvien']['id'],
							 'start'=>	sprintf("%s-%s-%s", $Year, $exp[1], $exp[2]),
							 'title'	=>	'Sinh nhật: ' . $item['Nhanvien']['full_name'],
							 'editable'	=>	false,
							 'description'	=>	'<div><img src="/img/icons/cake.png" align="left" style="padding-right: 3px">Mừng sinh nhật <b>' . $item['Nhanvien']['full_name'] . '</b></div><div>' . $time->format('d-m-Y', $item['Nhanvien']['ngay_sinh']) . '&nbsp;&nbsp;' . sprintf("%s-%s-%s", $exp[2], $exp[1], date("Y")) . '</div>');
				array_push($arr, $tmp);
			}
		}
		die(json_encode($arr));
	}

	public	function	add_lichcongtac()
	{
		if(!$this->check_permission('LichCongTac.tao_lichcongtac'))
			throw new InternalErrorException('Bạn không có quyền tạo lịch công tác. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			$this->request->data['Lichcongtac']['nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Lichcongtac']['ngay_nhap'] = date("Y-m-d H:i:s");
			$this->request->data['Lichcongtac']['ngay_batdau'] = $this->Bin->vn2sql($this->request->data['Lichcongtac']['ngay_batdau']);
			$this->request->data['Lichcongtac']['ngay_ketthuc'] = $this->Bin->vn2sql($this->request->data['Lichcongtac']['ngay_ketthuc']);
			$nguoinhan = explode(",", $this->request->data['Lichcongtac']['nv_selected']);
			$this->request->data['Nhanvien'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvien'], array('nguoidi_id' => $n));
				}
			}else
			{
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Vui lòng chọn người đi công tác.')));
			}
			if($this->Lichcongtac->saveAssociated($this->request->data))
			{
				die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				die(json_encode(array('success'	=>	false,
								  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
		}else
		{
			$this->loadModel('Tinhchatcongtac');
			$this->loadModel('Nhanvien');
			$tinhchat = $this->Tinhchatcongtac->find('list', array('conditions' => array('enabled' => 1), 'fields' => array('id', 'ten_tinhchat')));
			$quyen = $this->Auth->user('quyen');
			if( !$this->check_permission('HeThong.toanquyen')
					&& $quyen['LichCongTac.tao_lichcongtac'] != 0)
			{
				$this->loadModel('Phong');
				$ds = $this->Phong->getList($this->Auth->user('phong_id'));
				for($i = 0; $i < $size=count($ds); $i++)
				{
					$nhanvien = $this->Nhanvien->find('all', array('conditions' => array('phong_id' => $ds[$i]['Phong']['id']),
																   'order' 	=> array('nguoi_quanly' => 'DESC', 'ten' => 'ASC')));
					$ds[$i]['Nhanvien'] = $nhanvien;
				}
			}else
			{
				$ds = $this->Nhanvien->listNhanvien();
			}
			$this->set(compact('tinhchat', 'ds'));
		}
	}
	public	function	autocomplete_congtac($type)
	{
		$this->layout = null;
		$ret = array(
			'total'	=>	0,
			'results'	=>	array()
		);
		$q = $this->request->query['q'];
		if(!empty($q))
		{
			$ret['total'] = $this->Lichcongtac->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Lichcongtac->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Lichcongtac->find('all', array(
				'conditions' => array(
					"$type LIKE" => $q . '%'
					),
				'fields'	=>	array("DISTINCT($type)"),
				'order'	=>	array($type => 'ASC'),
				'limit' => $this->request->query['page_limit'],
				'page' => $this->request->query['page']
			));
			if(!empty($data))
				foreach($data as $item)
					array_push($ret['results'], array('id' => $item['Lichcongtac']["$type"], 'text' => $item['Lichcongtac']["$type"]));
		}
		die(json_encode($ret));
	}

	public	function	edit_lichcongtac($id = null)
	{
		if(!$this->check_permission('LichCongTac.tao_lichcongtac'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh lịch công tác. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			$this->request->data['Lichcongtac']['nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Lichcongtac']['ngay_nhap'] = date("Y-m-d H:i:s");
			$this->request->data['Lichcongtac']['ngay_batdau'] = $this->Bin->vn2sql($this->request->data['Lichcongtac']['ngay_batdau']);
			$this->request->data['Lichcongtac']['ngay_ketthuc'] = $this->Bin->vn2sql($this->request->data['Lichcongtac']['ngay_ketthuc']);
			$nguoinhan = explode(",", $this->request->data['Lichcongtac']['nv_selected']);
			if(empty($nguoinhan))
			{
				die(json_encode(array('success'	=>	false,
								  'message'	=>	'Vui lòng chọn người đi công tác.')));
			}
			if($this->Lichcongtac->saveAssociated($this->request->data))
			{
				$this->loadModel('Nguoidi');
				$f = true;
				// save nguoi nhan
				$old = $this->Nguoidi->find('list', array('conditions' => 'congtac_id=' . $this->data['Lichcongtac']['id'], 'fields' => array('nguoidi_id')));
				$del = array_diff($old, $nguoinhan);
				$ins = array_diff($nguoinhan, $old);
				//insert
				if($f)
					foreach($ins as $k => $v)
					{
						$t['id'] = NULL;
						$t['congtac_id'] = $this->request->data['Lichcongtac']['id'];
						$t['nguoidi_id'] = $v;
						if(!$this->Nguoidi->save($t))
						{
							$f = false;	break;
						}
					}
				//delete
				if($f)
					foreach($del as $k=>$v)
					{
						if(!$this->Nguoidi->delete($k))
						{
							$f = false;	break;
						}
					}
				//$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
										  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				//$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
		}else
		{
			$this->Lichcongtac->recursive = -1;
			$data = $this->Lichcongtac->read(null, $id);
			if(empty($data) ||
				(!$this->check_permission('HeThong.toanquyen') && $data['Lichcongtac']['nguoi_nhap_id'] != $this->Auth->user('nhanvien_id')))
				throw new InternalErrorException();
			$this->loadModel('Nguoidi');
			$nvcongtac = $this->Nguoidi->find('list', array('conditions' => array('congtac_id' => $id), 'fields' => 'nguoidi_id'));
			$nvcongtac = implode(',', $nvcongtac);
			$this->loadModel('Tinhchatcongtac');
			$tinhchat = $this->Tinhchatcongtac->find('list', array('conditions' => array('enabled' => 1), 'fields' => array('id', 'ten_tinhchat')));
			$this->set(compact('tinhchat', 'nvcongtac'));
			$this->data = $data;
		}
	}

	public	function	del_lichcongtac()
	{
		if(!$this->check_permission('LichCongTac.tao_lichcongtac'))
			die(json_encode(array('success' => false, 'message' => 'Bạn không được phép xóa lịch công tác.')));
		if(empty($this->request->data['id']))
			die(json_encode(array('success' => false, 'message' => 'Lỗi khi xóa')));
		else
		{
			$data = $this->Lichcongtac->field('nguoi_nhap_id', array('Lichcongtac.id' => $this->request->data['id']));
			if(empty($data)
				|| (!$this->check_permission('HeThong.toanquyen') && $data != $this->Auth->user('nhanvien_id')))
				die(json_encode(array('success' => false, 'message' => 'Bạn không được phép xóa lịch công tác.')));
			elseif($this->Lichcongtac->delete($this->request->data['id']))
				die(json_encode(array('success' => true, 'message' => 'Xóa thành công.')));
			else
				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi khi xóa lịch công tác.')));
		}
	}
	public	function	add_lichlamviec()
	{
		if(!$this->check_permission('LichCongTac.tao_lichlamviec'))
			throw new InternalErrorException('Bạn không có quyền tạo lịch làm việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			$this->request->data['Lichlamviec']['nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Lichlamviec']['ngay_nhap'] = date("Y-m-d H:i:s");
			$this->request->data['Lichlamviec']['enabled'] = 1;
			if(!$this->check_permission('Hethong.toanquyen'))
				$this->request->data['Lichlamviec']['phong_id'] = $this->Auth->user('phong_id');
			if($this->Lichlamviec->save($this->request->data))

			{
			//$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
										  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				//$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
		}else
		{
			$this->loadModel('Phong');
			$ds_phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			$this->loadModel('Group');
			$groups = $this->Group->find('list', array('conditions' => array('user_id' => $this->Auth->user('nhanvien_id')), 'fields' => array('Group.id', 'Group.ten_nhom'), 'order' => 'thu_tu ASC'));
			$pham_vi = $this->Lichlamviec->pham_vi;
			$ds = $this->Session->read('Auth.User.quyen');
			if(isset($ds['LichCongTac.tao_lichlamviec']) && $ds['LichCongTac.tao_lichlamviec'] == 1)
				unset($pham_vi[0]);
			elseif(isset($ds['LichCongTac.tao_lichlamviec']) && $ds['LichCongTac.tao_lichlamviec'] == 2)
			{
				unset($pham_vi[1]);
				unset($pham_vi[0]);
			}
			$this->set(compact('pham_vi', 'ds_phong', 'groups'));
		}
	}

	public	function	autocomplete_lamviec($type)
	{
		$this->layout = null;
		$ret = array(
			'total'	=>	0,
			'results'	=>	array()
		);
		$q = $this->request->query['q'];
		if(!empty($q))
		{
			$ret['total'] = $this->Lichlamviec->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Lichlamviec->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Lichlamviec->find('all', array(
				'conditions' => array(
					"$type LIKE" => $q . '%'
					),
				'fields'	=>	array("DISTINCT($type)"),
				'order'	=>	array($type => 'ASC'),
				'limit' => $this->request->query['page_limit'],
				'page' => $this->request->query['page']
			));
			if(!empty($data))
				foreach($data as $item)
					array_push($ret['results'], array('id' => $item['Lichlamviec']["$type"], 'text' => $item['Lichlamviec']["$type"]));
		}
		die(json_encode($ret));
	}

	public	function	edit_lichlamviec($id = null)
	{
		if(!$this->check_permission('LichCongTac.tao_lichlamviec'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh lịch làm việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		if(!empty($this->request->data))
		{
			if(!$this->check_permission('Hethong.toanquyen'))
				$this->data['Lichlamviec']['phong_id'] = $this->Auth->user('phong_id');
			if($this->Lichlamviec->save($this->request->data))
			{
				//$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				//$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
		}else
		{
			$data = $this->Lichlamviec->find('first', array('conditions' => array('id' => $id, 'Lichlamviec.enabled' => 1)));
			if(empty($data) || (!$this->check_permission('HeThong.toanquyen')
					&& $data['Lichlamviec']['nguoi_nhap_id'] != $this->Auth->user('nhanvien_id')))
				throw new InternalErrorException();
			$this->loadModel('Phong');
			$ds_phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			$this->loadModel('Group');
			$groups = $this->Group->find('list', array('conditions' => array('user_id' => $this->Auth->user('nhanvien_id')), 'fields' => array('Group.id', 'Group.ten_nhom'), 'order' => 'thu_tu ASC'));
			$pham_vi = $this->Lichlamviec->pham_vi;
			$ds = $this->Session->read('Auth.User.quyen');
			if(isset($ds['LichCongTac.tao_lichlamviec']) && $ds['LichCongTac.tao_lichlamviec'] == 1)
				unset($pham_vi[0]);
			elseif(isset($ds['LichCongTac.tao_lichlamviec']) && $ds['LichCongTac.tao_lichlamviec'] == 2)
			{
				unset($pham_vi[1]);
				unset($pham_vi[0]);
			}
			$this->set(compact('pham_vi', 'ds_phong', 'groups'));
			$this->data = $data;
		}
	}

	public	function	view_lichlamviec($id = null)
	{
		$this->layout = null;
		$this->Lichlamviec->bindModel(array(
			'belongsTo' => array('NguoiNhap'	=>	array('className' => 'Nhanvien', 'foreignKey'	=>	'nguoi_nhap_id',
											  'fields'		=>	'full_name'))
		), false);
		$data = $this->Lichlamviec->find('first', array(
				'conditions' => array(
							'Lichlamviec.id' 					=> 	$id,
							'Lichlamviec.enabled' 	=> 	1,
							//'Lichlamviec.pham_vi' 	=>  0
							)));
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy lịch làm việc.');
		$this->data = $data;
	}

	public	function	del_lichlamviec()
	{
		if(!$this->check_permission('LichCongTac.tao_lichlamviec'))
			die(json_encode(array('success' => false, 'message' => 'Bạn không được phép xóa lịch làm việc.')));
		if(empty($this->request->data['id']))
			die(json_encode(array('success' => false, 'message' => 'Lỗi khi xóa')));
		else
		{
			$data = $this->Lichlamviec->field('nguoi_nhap_id', array('Lichlamviec.id' => $this->request->data['id']));
			if(empty($data)
				|| (!$this->check_permission('HeThong.toanquyen') && $data != $this->Auth->user('nhanvien_id')))
				die(json_encode(array('success' => false, 'message' => 'Bạn không được phép xóa lịch làm việc.')));
			elseif($this->Lichlamviec->delete($this->request->data['id']))
				die(json_encode(array('success' => true, 'message' => 'Xóa thành công.')));
			else
				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi khi xóa lịch làm việc.')));
		}
	}

	public	function	congtac()
	{
		$this->set('title_for_layout', 'Xem lịch công tác');
	}

	public	function	congtac_ds()
	{
		if(!$this->check_permission('LichCongTac.xem_lichcongtac'))

			throw new InternalErrorException('Bạn không có quyền xem danh sách lịch công tác. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$dStartDate = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));
		$dEndDate = date('Y-m-d', mktime(0, 0, 0, date("m")+1, 1, date("Y")));
		if(!empty($this->request->data['start']))
			$dStartDate = date("Y-m-d", $this->request->data['start']);
		if(!empty($this->request->data['end']))
			$dEndDate = date("Y-m-d", $this->request->data['end']);
		$view = new View($this);
        $time = $view->loadHelper('Time');
		$this->layout = null;
		$arr = array();
		// permission
		$is_admin = false;
		if($this->check_permission('HeThong.toanquyen'))
		$is_admin = true;
		$conds = array("OR"	=>	array(
							"Lichcongtac.ngay_batdau BETWEEN ? AND ?" => array($dStartDate, $dEndDate),
							"Lichcongtac.ngay_ketthuc BETWEEN ? AND ?" => array($dStartDate, $dEndDate),
						));
		$this->Lichcongtac->unbindModel(array('hasAndBelongsToMany' => array('Nhanvien')));
		$this->Lichcongtac->bindModel(array('belongsTo' => array(
					'NguoiNhap'	=>	array('className'	=>	'Nhanvien',
										  'foreignKey'	=>	'nguoi_nhap_id',
										  'fields'		=>	array('full_name'))
					)), false);
		$lichcongtac = $this->Lichcongtac->find('all', array('conditions' => $conds));
		if(!empty($lichcongtac))
		{
			foreach($lichcongtac as $item)
			{
				$editable = false;
				if($is_admin || ($this->check_permission('LichCongTac.tao_lichcongtac') && $item['Lichcongtac']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
					$editable = true;
				$tmp = array('id'	=>	$item['Lichcongtac']['id'],
							 'start'=>	$item['Lichcongtac']['ngay_batdau'],
							 'end'=>	$item['Lichcongtac']['ngay_ketthuc'],
							 'allDay'	=>	true,
							 'title'	=>	$item['Lichcongtac']['tieu_de'],
							 'description'	=>	'<b>' . $item['NguoiNhap']['full_name'] . ' viết:</b><div>' . $item['Lichcongtac']['noi_dung'] . '</div><div><b>Địa điểm: </b>' . $item['Lichcongtac']['noi_congtac'] . '</div><div><b>Từ ngày: </b>' . $time->format("d-m-Y", $item['Lichcongtac']['ngay_batdau']) . ' <b>đến ngày : </b>' . $time->format("d-m-Y", $item['Lichcongtac']['ngay_ketthuc']) . '</div>',
							 'editable'	=>	$editable,
							 'cal_type'	=>	1);	// 0: lịch làm việc, 1: lịch công tác
				array_push($arr, $tmp);
			}
		}
		die(json_encode($arr));
	}

	public	function	lamviec($phamvi = -1)
	{
		$this->set('title_for_layout', 'Xem lịch làm việc');
		$ds_phamvi = $this->Lichlamviec->pham_vi;
		$this->set(compact(array('phamvi', 'ds_phamvi')));
	}

	public	function	lamviec_ds($phamvi = -1)
	{
		if(!$this->check_permission('LichCongTac.xem_lichlamviec'))

			throw new InternalErrorException('Bạn không có quyền xem lịch làm việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$dStartDate = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));
		$dEndDate = date('Y-m-d', mktime(0, 0, 0, date("m")+1, 1, date("Y")));
		if(!empty($this->request->data['start']))
			$dStartDate = date("Y-m-d", $this->request->data['start']);
		if(!empty($this->request->data['end']))
			$dEndDate = date("Y-m-d", $this->request->data['end']);
		$view = new View($this);
        $time = $view->loadHelper('Time');
		$this->layout = null;
		$arr = array();
		// permission
		$is_admin = false;
		if($this->check_permission('HeThong.toanquyen'))
			$is_admin = true;
		$this->Lichlamviec->bindModel(array('belongsTo' => array(
					'Nguoinhap'	=>	array('className'	=>	'Nhanvien',
										  'foreignKey'	=>	'nguoi_nhap_id',
										  'fields'		=>	array('full_name'))
					)), false);
		$conds = array(	'Lichlamviec.ngay_ghinho >=' 	=> $dStartDate,																																	 						'Lichlamviec.ngay_ghinho <=' 	=> $dEndDate);
		switch($phamvi)
		{
			case 0:			// lịch làm việc tất cả nhân viên đều xem
				$conds['Lichlamviec.pham_vi'] = 0;
				// load lịch làm việc phạm vi toàn đơn vị
				$lichlamviec = $this->Lichlamviec->find('all', array('conditions' => $conds));
				if(!empty($lichlamviec))
				{
					foreach($lichlamviec as $item)
					{
						$editable = false;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'	=>	$item['Lichlamviec']['id'],
									 'start'=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div><b>Địa điểm : </b>' . $item['Lichlamviec']['dia_diem'],
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);	// cal_type = 0: lịch làm việc, 1: lịch công tác
						array_push($arr, $tmp);
					}
				}
				break;
			case 1:			// lịch làm việc chỉ phòng mà nhân viên công tác xem
				// load lịch phòng
				$this->loadModel('Nhanvien');
				$conds['Lichlamviec.pham_vi'] = 1;
				if(!$is_admin)
					$conds['Lichlamviec.phong_id']	= $this->Auth->user('phong_id');
				$lichphong = $this->Lichlamviec->find('all', array('conditions' => $conds));
				if(!empty($lichphong))
				{
					foreach($lichphong as $item)
					{
						$editable = false;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'		=>	$item['Lichlamviec']['id'],
									 'start'	=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div>',
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);
						array_push($arr, $tmp);
					}
				}
				break;
			case 2:			// lịch làm việc cá nhân, chỉ có nhân viên xem
				// load lịch cá nhân
				$conds['Lichlamviec.pham_vi'] = 2;
				$conds['Lichlamviec.nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
				$personal = $this->Lichlamviec->find('all', array('conditions' => $conds));
				if(!empty($personal))
				{
					foreach($personal as $item)
					{
						$editable = true;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'		=>	$item['Lichlamviec']['id'],
									 'start'	=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div>',
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);
						array_push($arr, $tmp);
					}
				}
				break;
			case 3:			// lịch làm việc chỉ nhóm mà nhân viên công tác xem
				// load lịch phòng
				$this->loadModel('Nhanvien');
				$this->loadModel('Group');
				$groups = $this->Group->query("SELECT DISTINCT `Group`.id
FROM nhansu_groups `Group`, nhansu_group_nhanvien `GroupNhanvien`
WHERE `Group`.id = `GroupNhanvien`.group_id AND ( `GroupNhanvien`.nhanvien_id = " . $this->Auth->user('nhanvien_id') . " OR `Group`.user_id=" . $this->Auth->user('nhanvien_id') . ")");
				$groups = Set::extract($groups, '/Group/id');
				$conds['Lichlamviec.group_id'] = $groups;
				$conds['Lichlamviec.pham_vi'] = 3;
				$lichnhom = $this->Lichlamviec->find('all', array('conditions' => $conds));
				if(!empty($lichnhom))
				{
					foreach($lichnhom as $item)
					{
						$editable = false;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'		=>	$item['Lichlamviec']['id'],
									 'start'	=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div>',
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);
						array_push($arr, $tmp);
					}
				}
				break;
			default:		// tất cả
				$conds['Lichlamviec.pham_vi'] = 0;
				// load lịch làm việc phạm vi toàn đơn vị
				$lichlamviec = $this->Lichlamviec->find('all', array('conditions' => $conds));
				if(!empty($lichlamviec))
				{
					foreach($lichlamviec as $item)
					{
						$editable = false;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'	=>	$item['Lichlamviec']['id'],
									 'start'=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div><b>Địa điểm : </b>' . $item['Lichlamviec']['dia_diem'],
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);	// cal_type = 0: lịch làm việc, 1: lịch công tác
						array_push($arr, $tmp);
					}
				}
				// load lịch phòng
				$conds1 = array('Lichlamviec.ngay_ghinho >=' 	=> $dStartDate,																																	 								'Lichlamviec.ngay_ghinho <=' 	=> $dEndDate,
								'Lichlamviec.pham_vi'			=>	1);
				if(!$is_admin)
					$conds1['Lichlamviec.phong_id']	= $this->Auth->user('phong_id');
				$lichphong = $this->Lichlamviec->find('all', array('conditions' => $conds1));
				if(!empty($lichphong))
				{
					foreach($lichphong as $item)
					{
						$editable = false;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'		=>	$item['Lichlamviec']['id'],
									 'start'	=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div>',
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);
						array_push($arr, $tmp);
					}
				}
				// load lịch cá nhân
				$conds2 = array('Lichlamviec.ngay_ghinho >=' 	=> $dStartDate,																																	 								'Lichlamviec.ngay_ghinho <=' 	=> $dEndDate,
								'Lichlamviec.pham_vi'			=>	2,
								'Lichlamviec.nguoi_nhap_id'	=> 	$this->Auth->user('nhanvien_id'));
				$personal = $this->Lichlamviec->find('all', array('conditions' => $conds2));
				if(!empty($personal))
				{
					foreach($personal as $item)
					{
						$editable = true;
						if($is_admin || ($this->check_permission('LichCongTac.tao_lichlamviec') && $item['Lichlamviec']['nguoi_nhap_id'] == $this->Auth->user('nhanvien_id')))
							$editable = true;
						$tmp = array('id'		=>	$item['Lichlamviec']['id'],
									 'start'	=>	$item['Lichlamviec']['ngay_ghinho'],
									 'allDay'	=>	false,
									 'title'	=>	$item['Lichlamviec']['tieu_de'],
									 'description'	=>	'<b>' . $item['Nguoinhap']['full_name'] . ' viết:</b><div>' . $item['Lichlamviec']['noi_dung'] . '</div>',
									 'editable'	=>	$editable,
									 'cal_type'	=>	0);
						array_push($arr, $tmp);
					}
				}
				break;
		}
		die(json_encode($arr));
	}

	public function sendSMS()
	{

			//$sdts = $_POST['sdt'];
			//$tn = $_POST ['message'];
			$sdts = array('0914173222','0911968666','0911297332');
			$tn = 'Test sms tren binplus';
			foreach ($sdts as $key => $value) {
				$content = self::TEMPLATE;
				$content = str_replace('{{sdt}}',$value,$content);
				$content = str_replace('{{message}}',$tn,$content);
				$ch = curl_init();
				$opts = array(
					CURLOPT_URL 						=> 'http://10.70.28.200:8080/service1.asmx',
					CURLOPT_RETURNTRANSFER 	=> 1,
					CURLOPT_TIMEOUT 				=> 60,
					CURLOPT_HTTPHEADER 			=> array(
																					'POST /service1.asmx HTTP/1.1',
																					'Host: 10.70.28.200',
																					'Content-Type: text/xml; charset=utf-8',
																					'Content-Length: ' . strlen($content),
																					'SOAPAction: "http://tempuri.org/sendsms"'

																		),
					CURLOPT_POST 						=> 1,
					CURLOPT_POSTFIELDS			=> $content
				);

				if(curl_setopt_array($ch, $opts)) {
						$str = curl_exec($ch);
						if(!$str)
						{
							echo curl_error($ch);
						}
						else {
							echo $str;
						}
						curl_close($ch);
				}
			}
		die();
	}
}
