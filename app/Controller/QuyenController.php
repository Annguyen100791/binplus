<?php
class QuyenController extends AppController {

	protected $paginate = array(
        'limit' => 	10,
		'order'	=>	'tu_khoa ASC'
        );
	
	public	function	beforeFilter()
	{
		parent::beforeFilter();
		if(!$this->check_permission('HeThong.quyen'))
			throw new InternalErrorException();
	}
	
	public 	function index()
	{
		$conds = array();
		if(!empty($this->request->data['prefix']))
		{
			$prefix = $this->request->data['prefix'];
			$this->passedArgs['prefix'] = $this->request->data['prefix'];
		}elseif(isset($this->passedArgs['prefix']))
		{
			$prefix = $this->passedArgs['prefix'];
		}
		
		if(!empty($this->request->data['ten_quyen']))
		{
			$ten_quyen = $this->request->data['ten_quyen'];
			$this->passedArgs['ten_quyen'] = $this->request->data['ten_quyen'];
		}elseif(isset($this->passedArgs['ten_quyen']))
		{
			$ten_quyen = $this->passedArgs['ten_quyen'];
		}
		
		if(!empty($prefix))
			$conds = array('Quyen.tu_khoa LIKE' => $prefix . '.%');
			
		if(isset($ten_quyen))
			$conds[] = array('Quyen.ten_quyen LIKE' => '%' . $ten_quyen . '%');
			
		$ds = $this->paginate('Quyen', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Quyền hạn');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('quyen');
		}
	}
	
	public	function	view($id = null)
	{
		$data = $this->Quyen->read(null, $id);
		if(empty($data))
			die('Not found');
		$this->data = $data;
	}
	
	public	function	add()
	{
		if(!empty($this->request->data)) 
		{
			if($this->Quyen->save($this->request->data))
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
			if($this->Quyen->save($this->request->data))
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
				die(json_encode(array('success'	=>	true,
										  'message'	=>	'Hiệu chỉnh thành công.')));
			}else
			{
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
			}
        }else
		{
			$data = $this->Quyen->read(null, $id);
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
				if($this->Quyen->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/quyen');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/quyen');
	}
	
	
	public	function	checkkey($id = null)
	{
		$this->layout = null;
		$count = 0;
		
		if(is_null($id))
			$count = $this->Quyen->find('count', array('conditions' => array("tu_khoa" => $this->data['Quyen']['tu_khoa'])));
		else
			$count = $this->Quyen->find('count', array('conditions' => array("tu_khoa" => $this->data['Quyen']['tu_khoa'], "id <>" => $id)));
			
		if($count > 0)
				echo "false";
			else
				echo "true";
		die();
	}
	
	public	function	search()
	{
		$this->set('prefixs', $this->Quyen->prefix);
	}
}