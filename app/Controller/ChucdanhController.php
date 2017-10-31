<?php
/**
 * Chucdanh controller
 *
 * controller dành cho đối tượng chức danh (danh mục chức danh)
 *
 * PHP version 5
 *
 * @category Controllers
 * @package  BIN
 * @version  1.0
 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */

class ChucdanhController extends AppController {
	
/*
	Function	: 	beforeFilter
	Desc		:	kiểm tra quyền hạn, nếu user có quyền HeThong.chucdanh
	input		:	none
	output		:	none
*/

	protected $paginate = array(
        'limit' => 	1000,
		'order'	=>	'thu_tu ASC'
        );
	
	public	function	beforeFilter()
	{
		parent::beforeFilter();
		if(!$this->check_permission('HeThong.chucdanh'))
			throw new InternalErrorException();
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
			$conds = array('ten_chucdanh LIKE' => '%' . $keyword . '%');
			
		$ds = $this->paginate('Chucdanh', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Chức danh');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('chucdanh');
		}
	}

/*
	Function	: 	move
	Desc		:	thay đổi thứ tự sắp xếp chức danh
	input		:	@dir 	(up/down) 
					@id 	(id của mục cần thay đổi)
	output		:	json object {"success": true, "message": "message content"}
*/	
	
	public	function	move($dir = 'up', $id = null)
	{
		$this->layout = null;
		
		if($dir == 'up')
		{
			if($this->Chucdanh->moveUp($id))
				die(json_encode(array('success' => true)));
			else
				die(json_encode(array('success' => false)));
		}elseif($dir == 'down')
		{
			if($this->Chucdanh->moveDown($id))
				die(json_encode(array('success' => true)));
			else
				die(json_encode(array('success' => false)));
		}else
			die(json_encode(array('success' => false, 'message' => 'Không xác định được tham số.')));
			
		die();
	}

/*
	Function	: 	active
	Desc		:	enabled/disabled	chức danh
	input		:	@id 	(id của chức danh cần thay đổi)
	output		:	json object {"success": true, "id": "id", "status": "status"}
*/	

	
	public	function	active($id)
	{
		$data = $this->Chucdanh->read(array('enabled'), $id);
		$status = 0;
		$success = 0;
		
		if(!empty($data))
		{
			if($data['Chucdanh']['enabled'] == 0)
			{
				$status = 1;
			}
			else
				$status = 0;
			$data['Chucdanh']['enabled'] = $status;
			
			$data['Chucdanh']['id'] = $id;
			if($this->Chucdanh->save($data))
				$success = 1;
		}
		
		die(json_encode(array(
			'success'	=>	$success,
			'id'		=>	$id,
			'status'	=>	$status
		)));
	}
	
/*
	Function	: 	add
	Desc		:	show/save form nhập mới chức danh
	input		:	$this->request->data
	output		:	json object {"success": true/false}
*/	

	
	public	function	add()
	{
		if (!empty($this->data)) 
		{
            if ($this->Chucdanh->save($this->data)) 
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
			$this->loadModel('Nhomquyen');
			$this->set('nhomquyen', $this->Nhomquyen->find('list', array(
																		 'fields' 		=> array('Nhomquyen.id', 'Nhomquyen.ten_nhomquyen'))));
			//$this->set('highest_value', $this->Chucdanh->ordered2options());
		}
	}
	
/*
	Function	: 	edit
	Desc		:	show/save form hiệu chỉnh chức danh
	input		:	$this->request->data OR @id (id chức danh cần hiệu chỉnh)
	output		:	json object {"success": true/false}
*/	

	
	public	function	edit($id = null)
	{
		$this->layout = null;
		if (!empty($id)) 
		{
			$this->loadModel('Nhomquyen');
			$this->set('nhomquyen', $this->Nhomquyen->find('list', array('fields' => array('Nhomquyen.id', 'Nhomquyen.ten_nhomquyen'))));
			
			$data = $this->Chucdanh->read(null, $id);
			$this->data = $data;
		}else
		{
            if ($this->Chucdanh->save($this->data)) 
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
                die(json_encode(array('success'	=>	true,
									  'message'	=>	'Hiệu chỉnh thành công.')));
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));
            }
		}
	}
	
/*
	Function	: 	delete
	Desc		:	xóa chức danh
	input		:	$this->request->data @v_id (danh sách id chức danh cần xóa)
					Ex:	1,5,6,7 ....
	output		:	json object {"success": true/false}
*/	


	public	function	delete()
	{
		$this->layout = null;
		if(!empty($this->data))
		{
			$success = 0;
			$ids = explode(",", $this->data['v_id']);
			foreach($ids as $k=>$v)
			{
				if($this->Chucdanh->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/chucdanh');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/chucdanh');
	}
	
	public	function	search()
	{
	}
	
	function	getnhomquyen($chucdanh_id = null)
	{
		//$this->Chucdanh->Id = $chucdanh_id;
		$nhomquyen_id = $this->Chucdanh->field('nhomquyen_id', array('id' => $chucdanh_id));
		die(json_encode(array('nhomquyen_id' => $nhomquyen_id))); 
	}
}