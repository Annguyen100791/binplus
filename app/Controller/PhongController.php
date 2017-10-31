<?php
class PhongController extends AppController {

	protected $paginate = array(
        'limit' => 10,
        );
	
	public	function	beforeFilter()
	{
		parent::beforeFilter();
		if(!$this->check_permission('HeThong.phongban'))
			throw new InternalErrorException();
	}
	
	public function index()
	{
		if(!$this->check_permission('HeThong.phongban'))
			throw new InternalErrorException();
		$ds = $this->Phong->find('all', array('order' => 'lft ASC'));
		$stack = array();
		foreach ($ds as $i => $result) 
		{
			while ($stack && ($stack[count($stack) - 1] < $result['Phong']['rght'])) {
				array_pop($stack);
			}
			$ds[$i]['Phong']['tree_prefix'] = str_repeat('---', count($stack));
			$ds[$i]['Phong']['padding']	 = 15*count($stack);
			$stack[] = $result['Phong']['rght'];
		}
		
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Phòng ban/ Đơn vị');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('phong');
		}
	}
	
	public	function	move($dir = 'up', $id = null)
	{
		$this->layout = null;
		
		if($dir == 'up')
			$this->Phong->moveUp($id, 1);
		else
			$this->Phong->moveDown($id, 1);
		die(json_encode(array('success' => true)));
			die(json_encode(array('success' => false, 'message' => 'Không xác định được tham số.')));
			
		die();
	}
	
	public	function	active($id)
	{
		$data = $this->Phong->read(array('enabled'), $id);
		$status = 0;
		$success = 0;
		
		if(!empty($data))
		{
			if($data['Phong']['enabled'] == 0)
			{
				$status = 1;
			}
			else
				$status = 0;
			$data['Phong']['enabled'] = $status;
			
			$data['Phong']['id'] = $id;
			if($this->Phong->save($data))
				$success = 1;
		}
		
		$this->set(compact('id', 'success', 'status'));
	}
	
	
	public	function	add()
	{
		if (!empty($this->request->data)) 
		{
			$this->request->data['Phong']['enabled'] = 1;
            if ($this->Phong->save($this->request->data)) 
			{
				$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
                die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
            }
        }else
		{
			$loai_donvi = $this->loai_donvi;
			$ds = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			$this->set(compact('ds', 'loai_donvi'));
		}
	}
	
	
	public	function	edit($id = null)
	{
		$this->layout = null;
		if (!empty($id)) 
		{
			$loai_donvi = $this->loai_donvi;
			$ds = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			$this->set(compact('ds', 'loai_donvi'));
			
			$data = $this->Phong->read(null, $id);
			$this->data = $data;
		}else
		{
            if ($this->Phong->save($this->request->data)) 
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
                die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
            }
		}
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
				if($this->Phong->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/phong');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/phong');
	}
	
	public	function	autocomplete()
	{
		$q = $this->request->query['q'];
		
		if(!empty($q))
		{
			$data = $this->Phong->find('all', array('conditions' => array('ten_phong LIKE' => '%' . $q . '%'), 'limit' => 10, 'order' => 'ten_phong ASC'));
			if(!empty($data))
			{
				foreach($data as $item)
					printf("%s|%s\n", $item['Phong']['ten_phong'], $item['Phong']['ten_phong']);
			}
		}
		die();
	}
}