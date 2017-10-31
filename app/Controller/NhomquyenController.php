<?php
class NhomquyenController extends AppController {

	protected $paginate = array(
		'order'	=>	'id DESC'
        );
	
	public	function	beforeFilter()
	{
		parent::beforeFilter();
		if(!$this->check_permission('HeThong.nhomquyen'))
			throw new InternalErrorException();
	}
	
	public function index()
	{
		$this->Nhomquyen->recursive = 1;
		//pr($this->paginate('Nhomquyen', null));die();

		$ds = $this->paginate('Nhomquyen', null);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Nhóm quyền');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('nhomquyen');
		}
	}
	
	
	public	function	add()
	{
		
		if(!empty($this->request->data)) 
		{
			$current_quyen = array();
			$current_phamvi = array();
			
			$current_quyen = $this->data['QuyenNhomquyen']['quyen_id'];
			if(!empty($this->data['QuyenNhomquyen']['quyen_id']))
				foreach($this->data['QuyenNhomquyen']['quyen_id'] as $k => $v)
					$current_phamvi[$k] = $this->data['QuyenNhomquyen']['pham_vi'][$k];
			
			$this->loadModel('QuyenNhomquyen');		
			if($this->Nhomquyen->save($this->request->data))
			{
				$nhomquyen_id = $this->Nhomquyen->getLastInsertID();
				// ins
				foreach($current_quyen as $k => $v)
				{
					$t['QuyenNhomquyen'] = array(
											'id'		=>	null,
											'nhomquyen_id'	=>	$nhomquyen_id,
											'quyen_id'	=>	$k,
											'pham_vi'	=>	$current_phamvi[$v]
										);
					$this->QuyenNhomquyen->save($t);
				}
			
				$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
			}else
			{
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
			}
			$this->redirect('/nhomquyen');
        }
		
		$pham_vi = $this->pham_vi;
		$ten_quyen = $this->Nhomquyen->Quyen->prefix;
		$quyen = $this->Nhomquyen->Quyen->find('all', array('order' => 'tu_khoa ASC'));
		
		$this->set(compact("quyen", "pham_vi", "ten_quyen"));

	}
	
	public	function	edit($id = null)
	{
		if(!empty($this->request->data)) 
		{
			$current_quyen = array();
			$current_phamvi = array();
			
			$current_quyen = $this->data['QuyenNhomquyen']['quyen_id'];
			if(!empty($this->data['QuyenNhomquyen']['quyen_id']))
				foreach($this->data['QuyenNhomquyen']['quyen_id'] as $k => $v)
					$current_phamvi[$k] = $this->data['QuyenNhomquyen']['pham_vi'][$k];
			$this->loadModel('QuyenNhomquyen');
			$old = $this->QuyenNhomquyen->find('list', array('conditions' => array('nhomquyen_id' => $this->data['Nhomquyen']['id']), 'fields' => array('id', 'quyen_id')));
			
			$upd = array_intersect($old, $current_quyen);
			$del = array_diff($old, $current_quyen);
			$ins = array_diff($current_quyen, $old);
			
			// del
			foreach($del as $k => $v)
				$this->QuyenNhomquyen->delete($k);
			// upd
			foreach($upd as $k => $v)
			{
				$t['QuyenNhomquyen'] = array(
										'id'		=> 	$k,
										'quyen_id'	=>	$v,
										'pham_vi'	=>	$current_phamvi[$v]
									);
				$this->QuyenNhomquyen->save($t);
			}
			
			// ins
			foreach($ins as $k => $v)
			{
				$t['QuyenNhomquyen'] = array(
										'id'		=>	null,
										'nhomquyen_id'	=>	$this->data['Nhomquyen']['id'],
										'quyen_id'	=>	$k,
										'pham_vi'	=>	$current_phamvi[$v]
									);
				$this->QuyenNhomquyen->save($t);
			}
			if($this->Nhomquyen->save($this->request->data))
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
			}else
			{
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
			}
			$this->redirect('/nhomquyen');
        }
		
		$pham_vi = $this->pham_vi;
		$ten_quyen = $this->Nhomquyen->Quyen->prefix;
		
		//$quyen = $this->Nhomquyen->Quyen->find('list', array('fields' => array('Quyen.id', 'Quyen.ten_quyen')));
		$quyen = $this->Nhomquyen->Quyen->find('all', array('order' => 'tu_khoa ASC'));
		$this->set(compact("quyen", "pham_vi", "ten_quyen"));
		
		//$this->Nhomquyen->recursive = -1;
		$data = $this->Nhomquyen->read(null, $id);
		if(empty($data))
			die('Not found');
		
		
		//$nhomquyen_quyen = $this->
		$arr = array();
		foreach($data['Quyen'] as $item)
		{
			$arr[$item['SysQuyenNhomquyen']['quyen_id']] = $item['SysQuyenNhomquyen']['pham_vi'];
		}
		$data['Quyen'] = null;
		$data['Quyen'] = $arr;
		
		$this->data = $data;
		//pr($data); die();
	}
	
	public	function	delete()
	{
		$this->layout = null;
		if(!empty($this->data))
		{
			$success = 0;
			$ids = explode(",", $this->data['v_id']);
			foreach($ids as $k=>$v)
			{
				if($this->Nhomquyen->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/nhomquyen');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/nhomquyen');
	}
}