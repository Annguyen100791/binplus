<?php
class ChinhsachController extends AppController {

	protected 	$paginate = array(
        'limit' => 10,
		'order'	=>	'id DESC'
        );
	
	public $components = array(
		'Bin'
    );
	
	protected	$ds_phong = array();
	
	public function index()
	{
		
		if(!$this->check_permission('ChinhSach.xem'))
			throw new InternalErrorException();

		$quyen = $this->Session->read('Auth.User.quyen');
		$conds = array();
		$ds = $this->paginate('Chinhsach', $conds);
		
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Chính sách mới');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('chinhsach');
		}
	}
	
	public	function	compose()
	{
		if(!$this->check_permission('ChinhSach.nhap'))
			throw new InternalErrorException();
		$f = false;
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			$this->request->data['Chinhsach']['nguoigui_id'] 	= $this->Auth->user('nhanvien_id');
			$this->request->data['Chinhsach']['ngay_gui']		= date("Y-m-d H:i:s");
			$this->request->data['Chinhsach']['tieu_de'] = Sanitize::stripAll($this->request->data['Chinhsach']['tieu_de']);
			$this->request->data['Chinhsach']['noi_dung'] = Sanitize::stripAll($this->request->data['Chinhsach']['noi_dung']);
			
            if ($this->Chinhsach->save($this->request->data)) 
				$f = true;
			else
				$f = false;
			//pr($f);die();	
			if ($f) 
			{
				$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				/*die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));*/
				$this->redirect('/chinhsach/index');									  
                
            } else {
				/*die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));*/
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/chinhsach/index');
				
            }
        }
	}
	
	public	function	edit($id = null)
	{
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			
			$this->request->data['Chinhsach']['tieu_de'] = Sanitize::stripAll($this->request->data['Chinhsach']['tieu_de']);
			$this->request->data['Chinhsach']['noi_dung'] = Sanitize::stripAll($this->request->data['Chinhsach']['noi_dung']);
			
			if ($this->Chinhsach->save($this->request->data)) 
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
                /*die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));*/
				$this->redirect('/chinhsach/index');
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				/*die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));*/
				$this->redirect('/chinhsach/index');
            }
        }else
		{
			$data = $this->Chinhsach->read(null, $id);
			if(empty($data))
				throw new InternalErrorException('Không tìm thấy tin tức cần chỉnh sửa');
			$this->data = $data;
			
			if(!$this->Bin->check('ChinhSach.sua', $data['Chinhsach']['nguoigui_id']))
				throw new InternalErrorException('Bạn không có quyền chỉnh sửa tin tức này.');
		}
	}
	
	public	function	delete()
	{
		if(!$this->check_permission('ChinhSach.xoa'))
			throw new InternalErrorException();
		$this->layout = null;
		if(!empty($this->data))
		{
			$success = 0;
			$ids = explode(",", $this->data['v_id']);
			foreach($ids as $k=>$v)
			{
				$nguoigui_id = $this->Chinhsach->field('nguoigui_id', array('id' => $v));
				if($this->Bin->check('ChinhSach.xoa', $nguoigui_id))	
				{
					if($this->Chinhsach->delete($v))
						$success++;
				}
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/chinhsach/index');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/chinhsach/index');
	}
	
	public	function	view($id = null)
	{
		if(!$this->check_permission('ChinhSach.xem'))
			throw new InternalErrorException();
		$data = $this->Chinhsach->read(null, $id);
		
		if(empty($data))
			die('Không tìm thấy thông báo này.');
		$this->data = $data;
	}
}