<?php
class XulyvanbanController extends AppController {

	var $uses = array('Xulyvanban');
	protected $paginate = array(
        'limit' => 	10,
		'order'	=>	'id DESC'
        );
	
	
	public	function	ajaxlist()
	{
		$ds = $this->paginate('Xulyvanban', null);
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	
	
	public	function	add()
	{
		
		if(!empty($this->request->data)) 
		{
			$this->request->data['Xulyvanban']['ngay_xuly']  = date("Y-m-d H:i:s");
			$this->request->data['Xulyvanban']['nguoi_xuly_id']  =$this->Auth->user('nhanvien_id');
			if($this->Xulyvanban->save($this->request->data))
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
        }
	}
}