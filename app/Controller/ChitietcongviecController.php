<?php
class ChitietcongviecController extends AppController {

	public	function	update_progress($id = null)
	{
		$this->Chitietcongviec->bindModel(array('belongsTo' => array('Congviec' => array('fields' => 'ten_congviec'))), false);
		$data = $this->Chitietcongviec->find('first', array('conditions' => array('Chitietcongviec.id' => $id, 'nguoinhan_id' => $this->Auth->user('nhanvien_id')), 'recursive' => 1));
		
		if(empty($data))
			throw new InternalErrorException();
			
		$progress = $this->Chitietcongviec->progress;
		
		if(!empty($this->request->data))
		{
			if(!array_key_exists($this->request->data['Chitietcongviec']['mucdo_hoanthanh'], $this->Chitietcongviec->progress))
				throw new InternalErrorException();
			$this->request->data['Chitietcongviec']['ngay_capnhat'] = date('Y-m-d H:i:s');
			if($this->Chitietcongviec->save($this->request->data))
			{
				$this->Session->setFlash('Cập nhật công việc thành công.', 'flash_success');
				
			}else
				$this->Session->setFlash('Đã phát lỗi khi cập nhật công việc.', 'flash_error');
			$this->redirect('/congviec/duocgiao');
		}
		//pr($data); die();
		
		$this->data = $data;
		$this->set(compact('progress'));
	}
}