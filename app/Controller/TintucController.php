<?php
class TintucController extends AppController {

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
		
		if(!$this->check_permission('ThongBao.xem'))
			throw new InternalErrorException();

		$quyen = $this->Session->read('Auth.User.quyen');
		$conds = array();
		/*
		if(isset($quyen['HeThong.toanquyen']))
		{
			//
		}
		elseif($quyen['ThongBao.xem'] == 1)	// nhân viên trong phòng post tin tức
		{
			$this->loadModel('Phong');
			$cur_phong = $this->Phong->read(array('lft', 'rght'), $this->Session->read('Auth.User.phong_id'));
			$phong = $this->Phong->find('list', array(
								'conditions'	=>	array(	'lft >=' 	=> $cur_phong['Phong']['lft'],
															'rght <='	=> $cur_phong['Phong']['rght']
															),
								'fields'	=>	array('id'),
								'order'		=>	'lft ASC'
							));
			$this->loadModel('Nhanvien');
			$nhanvien = $this->Nhanvien->find('list', array(
								'conditions'	=>	array( 'phong_id'	=>	$phong),
								'fields'	=>	array('id')
			));
			$conds['nguoigui_id'] = $nhanvien;
		}elseif($quyen['ThongBao.xem'] == 2)
		{
			$conds['nguoigui_id'] = $this->Session->read('Auth.User.nhanvien_id');
		}
		*/
		$ds = $this->paginate('Tintuc', $conds);
		
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		
		$this->set('ds', $ds);
		
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Quản lý Thông tin mới');
			$this->render('index');
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('tintuc');
		}
	}
	
	public	function	compose()
	{
		if(!$this->check_permission('ThongBao.nhap'))
			throw new InternalErrorException();
		$f = false;
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			$this->request->data['Tintuc']['nguoigui_id'] 	= $this->Auth->user('nhanvien_id');
			$this->request->data['Tintuc']['ngay_gui']		= date("Y-m-d H:i:s");
			
			$this->request->data['Tintuc']['tieu_de'] = Sanitize::stripAll($this->request->data['Tintuc']['tieu_de']);
			$this->request->data['Tintuc']['noi_dung'] = Sanitize::stripAll($this->request->data['Tintuc']['noi_dung']);
			
            if ($this->Tintuc->save($this->request->data)) 
				$f = true;
			else
				$f = false;
			//pr($f);die();	
			if ($f) 
			{
				$this->Session->setFlash('Thêm mới thành công.', 'flash_success');
				/*die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));*/
				$this->redirect('/tintuc/index');									  
                
            } else {
				/*die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));*/
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/tintuc/index');
				
            }
        }
	}
	
	public	function	edit($id = null)
	{
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			
			$this->request->data['Tintuc']['tieu_de'] = Sanitize::stripAll($this->request->data['Tintuc']['tieu_de']);
			$this->request->data['Tintuc']['noi_dung'] = Sanitize::stripAll($this->request->data['Tintuc']['noi_dung']);
			
			if ($this->Tintuc->save($this->request->data)) 
			{
				$this->Session->setFlash('Hiệu chỉnh thành công.', 'flash_success');
                /*die(json_encode(array('success'	=>	true,
									  'message'	=>	'Thêm mới thành công.')));*/
				$this->redirect('/tintuc/index');
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi khi nhập liệu. Vui lòng thử lại.', 'flash_error');
				/*die(json_encode(array('success'	=>	false,
									  'message'	=>	'Đã phát sinh lỗi trong khi lưu dữ liệu. Vui lòng thử lại.')));*/
				$this->redirect('/tintuc/index');
            }
        }else
		{
			$data = $this->Tintuc->read(null, $id);
			if(empty($data))
				throw new InternalErrorException('Không tìm thấy tin tức cần chỉnh sửa');
			$this->data = $data;
			
			if(!$this->Bin->check('ThongBao.sua', $data['Tintuc']['nguoigui_id']))
				throw new InternalErrorException('Bạn không có quyền chỉnh sửa tin tức này.');
		}
	}
	
	public	function	delete()
	{
		/*
		if(!$this->check_permission('ThongBao.xoa'))
			throw new InternalErrorException();
		*/	
		$this->layout = null;
		if(!empty($this->data))
		{
			$success = 0;
			$ids = explode(",", $this->data['v_id']);
			foreach($ids as $k=>$v)
			{
				$nguoigui_id = $this->Tintuc->field('nguoigui_id', array('id' => $v));
				if($this->Bin->check('ThongBao.xoa', $nguoigui_id))	
				{
					if($this->Tintuc->delete($v))
						$success++;
				}
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('/tintuc/index');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('/tintuc/index');
	}
	
	public	function	view($id = null)
	{
		if(!$this->check_permission('ThongBao.xem'))
			throw new InternalErrorException();
		$data = $this->Tintuc->read(null, $id);
		
		if(empty($data))
			die('Không tìm thấy thông báo này.');
		$this->data = $data;
	}
}