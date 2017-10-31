<?php

App::uses('File', 'Utility');

App::uses('Folder', 'Utility');

App::uses('PagesController', 'Controller');

class ApiPagesController extends PagesController{

	public	$uses = array('Vanban', 'Congviec', 'Nhanvanban', 'Chitiettinnhan', 'Lichlamviec', 'Nhanvien');
	public function beforeFilter() {
	     	parent::beforeFilter();
	     	$user = $this->Auth->user();
		    if ( ( $user == null ) &&
			        ( $this->request->params['controller'] == 'apipages'
			        	        || $this->request->params['action'] == 'noticeForNotification'
			        	        || $this->request->params['action'] == 'testserver'
			       	)
		        )
		    {
		    	$this->Auth->allow();
		    }
	    }

	public function noticeForNotification() {

		$res = array();

		/**
		* Notification for Tinnhan
		*/	
		$nhanvien_id = isset($this->request->data['iduser']) ? $this->request->data['iduser'] : '';
		

		$tinNhanChuaDoc = $this->Chitiettinnhan->find('count', array('conditions' => array(

			'nguoinhan_id' => $nhanvien_id,

			'ngay_nhan' => NULL, 'mark_deleted' => 0)));

		$this->loadModel('Chitiettinnhan');
		$this->Chitiettinnhan->bindModel(array(

			'belongsTo' 	=> array(

					'Tinnhan'	=>	array('className'	=>	'Tinnhan')

					)), false);

		$tinNhanCuoi = $this->Chitiettinnhan->find('first', array('conditions' => array(
			'Chitiettinnhan.ngay_nhan' => NULL,
			'Chitiettinnhan.nguoinhan_id' => $nhanvien_id, 
			'Chitiettinnhan.mark_deleted' => 0),
			'order' => array('Tinnhan.ngay_gui' => 'DESC')
			));

		/**
		* Notification for VanBan
		*/
		$this->Nhanvanban->bindModel(array('belongsTo'	=>	array('Vanban')), false);

		if($nhanvien_id == 681) {

			$vanBanChuaDoc = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' 	=> $nhanvien_id,

									   'ngay_xem' 		=> NULL,

								   'Vanban.tinhtrang_duyet'	=> 1,

								   'Vanban.chuyen_bypass <>' => 1,

								   'Vanban.chuyen_nguoiduyet <>' => 1,

								   'Vanban.loaivanban_id <>' 		=> NULL,

								   'OR' => array(

												'Vanban.nguoi_duyet_id <>' 	=> 681,

												'Vanban.nguoi_duyet_id' => NULL

											))));
		} else {
			$vanBanChuaDoc = $this->Nhanvanban->find('count', array('conditions' => array(
				'nguoi_nhan_id' => $nhanvien_id,
				'ngay_xem' => NULL, 
				'Vanban.tinhtrang_duyet = 1',
				'Vanban.loaivanban_id <>'	=> NULL)));
		}

		/*Van ban cuoi*/
		$this->Nhanvanban->bindModel(array('belongsTo'	=>	array('Vanban')), false);

		if($nhanvien_id == 681) {

			$vanBanCuoi = $this->Nhanvanban->find('first', array('conditions' => array('nguoi_nhan_id' 	=> $nhanvien_id,

									   'ngay_xem' 		=> NULL,

								   'Vanban.tinhtrang_duyet'	=> 1,

								   'Vanban.chuyen_bypass <>' => 1,

								   'Vanban.chuyen_nguoiduyet <>' => 1,

								   'Vanban.loaivanban_id <>' 		=> NULL,

								   'OR' => array(

												'Vanban.nguoi_duyet_id <>' 	=> 681,

												'Vanban.nguoi_duyet_id' => NULL),
								   'order' => array('Vanban.ngay_phathanh' => 'DESC'))));
		} else {
			$vanBanCuoi = $this->Nhanvanban->find('first', array('conditions' => array(
				'nguoi_nhan_id' => $nhanvien_id,
				'ngay_xem' => NULL, 
				'Vanban.tinhtrang_duyet = 1',
				'Vanban.loaivanban_id <>'	=> NULL),
				'order' => array('Vanban.ngay_phathanh' => 'DESC')));
		}

		//$res["NgayGoiTinNhan"] = $tinNhanCuoi->Tinnhan->ngay_gui;
		$res["TinNhanCuoi"] = $tinNhanCuoi["Tinnhan"]["ngay_gui"];
		$res["TinNhanChuaDoc"] = $tinNhanChuaDoc;

		//$res = $vanBanCuoi;
		$res["VanBanCuoi"] = $vanBanCuoi["Vanban"]["ngay_nhap"];
		$res["VanBanChuaDoc"] = $vanBanChuaDoc;

		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}	
	public function testserver(){
		$res = array();
		$res = 1;
		$this->set(compact('res'));
		$this->set('_serialize', 'res');
	}
}



