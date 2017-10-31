<?php

App::uses('File', 'Utility');

App::uses('Folder', 'Utility');

App::uses('PagesController', 'Controller');

class ApiPagesController extends PagesController{

	public	$uses = array('Vanban', 'Congviec', 'Nhanvanban', 'Chitiettinnhan', 'Lichlamviec', 'Nhanvien');

	// public function beforeFilter() {
	// 	// parent::beforeFilter();
	//      	$user = $this->Auth->user();
	// 	    if ( ( $user == null ) &&
	// 		        ( $this->request->params['controller'] == 'ApiPages'
	// 		        	        || $this->request->params['action'] == 'noticeForNotification'
	// 		        	        || $this->request->params['action'] == 'notice'
	// 		       	)
	// 	        )
	// 	    {
	// 	    	$this->Auth->allow();
	// 	    }
	//     }

	public function noticeForNotification() {

		$res = array();

		/**
		* Notification for Tinnhan
		*/	
		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';
		

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

	/*
		Notice
	*/
	public	function	notice() {

		$nhanvien_id = isset($this->request->data['nhanvien_id']) ? $this->request->data['nhanvien_id'] : '';
		$donvi_id = isset($this->request->data['donvi_id']) ? $this->request->data['donvi_id'] : '';
		$phong_id = isset($this->request->data['phong_id']) ? $this->request->data['phong_id'] : '';


		$res = array();

		$this->loadModel('Lichlamviec');

		$lichcanhan = $this->Lichlamviec->find('all',

			array('conditions'	=>	array(

						'nguoi_nhap_id'		=>	$nhanvien_id,

						'pham_vi'			=>	2,	// cá nhân

						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),

						'enabled'			=>	1

						),

				  'order'	=>	'ngay_ghinho ASC',

				  'fields'	=>	array('tieu_de', 'ngay_ghinho'))

			);

		//var_dump($lichcanhan);die();
		$res["lichcanhan"] = $lichcanhan;

		$this->Nhanvanban->bindModel(array('belongsTo'	=>	array('Vanban')), false);

		if($nhanvien_id == 681) {

			$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' 	=> $nhanvien_id,

									   'ngay_xem' 		=> NULL,

								   'Vanban.tinhtrang_duyet'	=> 1,

								   'Vanban.chuyen_bypass <>' => 1,

								   'Vanban.chuyen_nguoiduyet <>' => 1,

								   'Vanban.loaivanban_id <>' 		=> NULL,

								   'OR' => array(

												'Vanban.nguoi_duyet_id <>' 	=> 681,

												'Vanban.nguoi_duyet_id' => NULL

											)

								   )

			)

);
		} else {
			$vanban = $this->Nhanvanban->find('count', array('conditions' => array(
				'nguoi_nhan_id' => $nhanvien_id,
				'ngay_xem' => NULL, 
				'Vanban.tinhtrang_duyet = 1',
				'Vanban.loaivanban_id <>'	=> NULL)));
		}
		$res["vanban"] = $vanban;

		// Van ban den va trinh
		$vanbanden_trinh = $this->Nhanvanban->find('count', array('conditions' => array(
			 'Nhanvanban.nguoi_nhan_id' => $nhanvien_id,
			'Vanban.tinhtrang_duyet = 0',
			'Vanban.chieu_di = 1' )));
		$res["vanbanden_trinh"] = $vanbanden_trinh;


		$vanbanden_duyet = $this->Vanban->find('count', array('conditions' => array(
			'Vanban.nguoi_duyet_id' => $nhanvien_id, 
			'Vanban.tinhtrang_duyet = 2', 
			'Vanban.chieu_di = 1' )));
		$res["vanbanden_duyet"] = $vanbanden_duyet;


		$vanban_thaydoi = $this->Nhanvanban->find('count', array('conditions' => array(
			'Nhanvanban.nguoi_nhan_id' => $nhanvien_id,
			'Nhanvanban.ngayxem_chidao' => NULL,
			'Vanban.gd_chidao <>' => NULL,
			'Vanban.tinhtrang_duyet' => 1 )));
		$res["vanban_thaydoi"] = $vanban_thaydoi;


		//Văn bản có trao đổi, phản hồi
		// BlindModel to relationship VanBan(vanban_thongtin) and TraoDoiVanBan(vanban_traodoi)
		$this->Vanban->bindModel(
				array(
					'hasOne'	=>	array('Traodoivanban' => array('foreignKey'	=>	'vanban_id',
										  						'className'	=>	'Traodoivanban'))
				), false);

		$vb_traodoi = $this->Vanban->find('count', array('conditions' => array(
			'Traodoivanban.nguoi_nhan_id' => $nhanvien_id,
			'Traodoivanban.ngay_xem' => NULL)));
		$res["vb_traodoi"] = $vb_traodoi;

		if($donvi_id== ''){

			$donvi_id = $phong_id; 
		}

		// phát sinh từ khi chia nhóm P.THHC
		if($nhanvien_id== 683) {
			
			$donvi_id = 20;
		}

		// Comment to run file -> uncomment when Done
		$vbgap_notice = $this->Vanban->query("

				select count(vanban.id) as vb from (SELECT a.id

				FROM vanban_thongtin a

				left JOIN vanban_nhan b ON a.ID = b.vanban_id

				LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$nhanvien_id."

				where a.vb_gap = 1

				and a.tinhtrang_duyet = 1

				and a.chieu_di = 1

				and a.phongchutri_id = ".$donvi_id."

				and b.nguoi_nhan_id = ".$nhanvien_id."

				and DATE_FORMAT(a.vbgap_ngayhoanthanh, '%Y-%m-%d') < DATE_FORMAT(NOW(), '%Y-%m-%d') 

					and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL )

				and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$nhanvien_id." and m.capnhat_mucdo = 10)

				GROUP BY a.id) vanban

												");

		$vbgap_notice = $vbgap_notice[0][0]['vb'];
		$res["vbgap_notice"] = $vbgap_notice;

		$vbkhac_notice = $this->Vanban->query("

				select count(vanban.id) as vb from (SELECT a.id

				FROM vanban_thongtin a

				left JOIN vanban_nhan b ON a.ID = b.vanban_id

				LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$nhanvien_id."

				where a.vb_gap = 0

				and a.tinhtrang_duyet = 1

				and a.chieu_di = 1

				and a.phongchutri_id = ".$donvi_id."

				and b.nguoi_nhan_id = ".$nhanvien_id."

				and CheckNgayVB(a.ngay_duyet,NOW()) > 2

				and a.ngay_duyet is not null

				and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL)

				and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$nhanvien_id." and m.capnhat_mucdo = 10)

				GROUP BY a.id) vanban

				");


		$vbkhac_notice = $vbkhac_notice[0][0]['vb'];
		$res["vbkhac_notice"] = $vbgap_notice;

		$tinnhan = $this->Chitiettinnhan->find('count', array('conditions' => array(

			'nguoinhan_id' => $nhanvien_id,

			'ngay_nhan' => NULL, 'mark_deleted' => 0)));
		$res["tinnhan"] = $tinnhan;

		$duocgiao_chuahoanthanh = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoinhan_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'ngay_ketthuc <'	=>	date('Y-m-d', time())

				)));
		$res["duocgiao_chuahoanthanh"] = $duocgiao_chuahoanthanh;

		$duocgiao_dangthuchien = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoinhan_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'ngay_ketthuc >='	=>	date('Y-m-d', time())

				)));
		$res["duocgiao_dangthuchien"] = $duocgiao_dangthuchien;

		$dagiao_chuahoanthanh = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoi_giaoviec_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'ngay_ketthuc <'	=>	date('Y-m-d', time())

				)));
		$res["dagiao_chuahoanthanh"] = $dagiao_chuahoanthanh;

		$dagiao_dangthuchien = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoi_giaoviec_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'parent_id'			=> NULL, 

				'ngay_ketthuc >='	=>	date('Y-m-d', time())

				)));
		$res["dagiao_dangthuchien"] = $dagiao_dangthuchien;

		$dagiao_khan = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoi_giaoviec_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'tinhchat_id'	=>	11

				)));
		$res["dagiao_khan"] = $dagiao_khan;


		$duocgiao_khan = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoinhan_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'tinhchat_id'	=>	11

				)));
		$res["duocgiao_khan"] = $duocgiao_khan;


		//trễ tiến độ
		$dagiao_baocao = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoi_giaoviec_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'loaicongviec_id'	=>	1,

				'ngay_ketthuc <'	=>	date('Y-m-d', time())

				)));
		$res["dagiao_baocao"] = $dagiao_baocao;

		//trễ tiến độ
		$duocgiao_baocao = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoinhan_id' => $nhanvien_id,

				'mucdo_hoanthanh <'	=>	10,

				'loaicongviec_id'	=>	1,

				'ngay_ketthuc <'	=>	date('Y-m-d', time())

				)));
		$res["duocgiao_baocao"] = $duocgiao_baocao;

		//$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien','dagiao_khan','duocgiao_khan', 'lichcanhan', 'vanbanden_trinh', 'vanbanden_duyet','dagiao_baocao','duocgiao_baocao','vanban_thaydoi','vb_traodoi','vbgap_notice','vbkhac_notice'));

		

		/********** Hà thêm ********/

		$duocgiao_comment_moi = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoinhan_id' => $nhanvien_id,				

				'is_dadoc_comment'	=>	1

				)));
		$res["duocgiao_comment_moi"] = $duocgiao_comment_moi;

		$dagiao_comment_moi = $this->Congviec->find('count',

			array('conditions' => array(

				'nguoi_giaoviec_id' => $nhanvien_id,

				'parent_id'=>NULL,

				'giaoviec_comment'	=>	1

				)));
		$res["dagiao_comment_moi"] = $dagiao_comment_moi;

		//$this->set(compact('duocgiao_comment_moi','dagiao_comment_moi'));

		/*********** end Hà thêm ************/

		$this->set(compact('res'));
		$this->set('_serialize', 'res');

	}

}



