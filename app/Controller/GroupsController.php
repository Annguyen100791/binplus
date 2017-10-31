<?php
class GroupsController extends AppController {

	protected $paginate = array(
        'limit' => 	10,
		'order'	=>	'thu_tu ASC'
        );
	
	public function index()
	{
		$conds = array();
		$conds['user_id'] = $this->Auth->user('nhanvien_id');
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
			
		$ds = $this->paginate('Group', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Nhóm làm việc');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('group');
		}
	}
	
	
	
	public	function	add()
	{
		
		if(!empty($this->request->data)) 
		{
			$data['Group'] = array(
				'id'		  =>	NULL,
				'ten_nhom'	=>	$this->request->data['Group']['ten_nhom'],
				'user_id'	 =>	$this->Auth->user('nhanvien_id')
			);
			
			$data['GroupNhanvien'] = array();
			if(!empty($this->request->data['Group']['nv_selected']))
			{
				$ds = explode(",", $this->request->data['Group']['nv_selected']);
				foreach($ds as $k => $v)
					array_push($data['GroupNhanvien'],  array('nhanvien_id' => $v));
			}
			//pr($data); die();
			if($this->Group->saveAssociated($data))
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
			$data['Group'] = array(
				'id'		  =>	$this->request->data['Group']['id'],
				'ten_nhom'	=>	$this->request->data['Group']['ten_nhom']
			);
			
			// old
			$old = $this->Group->GroupNhanvien->find('list', array('conditions' => 'group_id=' . $this->data['Group']['id'], 'fields' => array('nhanvien_id')));
			// new
			$new = explode(",", $this->request->data['Group']['nv_selected']);
			
			$del = array_diff($old, $new);
			$ins = array_diff($new, $old);
			
			if($this->Group->save($data))
			{
				$f = true;
				//insert
				if($f)
				foreach($ins as $k => $v)
				{
					$t['id'] = NULL;
					$t['group_id'] = $this->request->data['Group']['id'];
					$t['nhanvien_id'] = $v;
					if(!$this->Group->GroupNhanvien->save($t))
					{
						$f = false;
					}
				}
				
				//delete
				if($f)
				foreach($del as $k=>$v)
				{
					if(!$this->Group->GroupNhanvien->delete($k))
					{
						$f = false;	break;
					}
				}
					
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
			$data = $this->Group->read(null, $id);
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
				if($this->Group->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/groups');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/groups');
	}
	
	public	function	move($dir = 'up', $id = null)
	{
		$this->layout = null;
		
		if($dir == 'up')
			$this->Group->moveUp($id, 1);
		else
			$this->Group->moveDown($id, 1);
		die(json_encode(array('success' => true)));
			die(json_encode(array('success' => false, 'message' => 'Không xác định được tham số.')));
			
		die();
	}
	
	// return JSON data
	public	function	get_group($group_id = null)
	{
		$data = array();
		if(!empty($group_id))
		{
			$data = $this->Group->find('first', array(
				'conditions' => array('id' => $group_id, 'user_id'	=>	$this->Auth->user('nhanvien_id')),
				//'fields'	=>	array('GroupNhanvien.nhanvien_id')
			));
			
		}
		if(!empty($data['GroupNhanvien']))
			$data = Set::extract($data['GroupNhanvien'], '/nhanvien_id');
		die(json_encode($data));
		
	}
	
}