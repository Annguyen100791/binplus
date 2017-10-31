<?php
class TinhchatcongviecController extends AppController {

	protected $paginate = array(
        'limit' => 	10,
		'order'	=>	'id DESC'
        );
	
	public function beforeFilter() {
        parent::beforeFilter();
		if(!$this->check_permission('HeThong.tinhchatcongviec'))
			throw new InternalErrorException('Bạn không có quyền quản lý Tính chất công việc. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
	}
	
	public function index()
	{
		$conds = array();
		if(!empty($this->data['keyword']))
		{
			$keyword = $this->data['keyword'];
			$this->passedArgs['keyword'] = $this->data['keyword'];
		}elseif(isset($this->passedArgs['keyword']))
		{
			$keyword = $this->passedArgs['keyword'];
		}
		
		if(!empty($keyword))
			$conds = array('ten_tinhchat LIKE' => '%' . $keyword . '%');
			
		$ds = $this->paginate('Tinhchatcongviec', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Tính chất văn bản');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('tinhchatcongviec');
		}
	}
	
	
	public	function	active($id)
	{
		$data = $this->Tinhchatcongviec->read(array('enabled'), $id);
		$status = 0;
		$success = 0;
		
		if(!empty($data))
		{
			if($data['Tinhchatcongviec']['enabled'] == 0)
			{
				$status = 1;
			}
			else
				$status = 0;
			$data['Tinhchatcongviec']['enabled'] = $status;
			
			$data['Tinhchatcongviec']['id'] = $id;
			if($this->Tinhchatcongviec->save($data))
				$success = 1;
		}
		
		die(json_encode(array(
			'success'	=>	$success,
			'id'		=>	$id,
			'status'	=>	$status
		)));
	}
	
	
	public	function	add()
	{
		
		if(!empty($this->request->data)) 
		{
			if($this->Tinhchatcongviec->save($this->request->data))
			{
				$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
										  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
        }
	}
	
	public	function	edit($id = null)
	{
		if(!empty($this->request->data)) 
		{
			if($this->Tinhchatcongviec->save($this->request->data))
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
										  'message'	=>	'Thêm mới thành công.')));
			}else
			{
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
        }else
		{
			$data = $this->Tinhchatcongviec->read(null, $id);
			if(empty($data))
				die('Not found');
			$this->data = $data;
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
				if($this->Tinhchatcongviec->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/tinhchatcongviec');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/tinhchatcongviec');
	}
	
	public	function	search()
	{
	}
}