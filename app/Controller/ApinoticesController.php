<?php 
	/**
	* 
	*/
	class ApinoticesController extends AppController
	{	
		// public $components = array(

  //       'RequestHandler',

		// 'ImageResizer'

		// );

		public	$uses = array('Vanban', 'Congviec', 'Nhanvanban', 'Chitiettinnhan', 'Lichlamviec', 'Nhanvien');	
		public function beforeFilter() {
	     	parent::beforeFilter();
	     // 	$user = $this->Auth->user();
		    // if ( ( $user == null ) &&
			   //      ( $this->request->params['controller'] == 'apinotices'
			   //      	        || $this->request->params['action'] == 'notice'
			   //     	)
		    //     )
		    // {
		    // 	$this->Auth->allow();
		    // }
	     }
	     public	function	notice()

		{
			$iduser = $this->request->data['iduser'];
			$res = array();
			$this->loadModel('Lichlamviec');
			$lichcanhan = $this->Lichlamviec->find('all',
				array('conditions'	=>	array(

							'nguoi_nhap_id'		=>	$iduser,

							'pham_vi'			=>	2,	// cá nhân

							"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),

							'enabled'			=>	1

							),

					  'order'	=>	'ngay_ghinho ASC',

					  'fields'	=>	array('tieu_de', 'ngay_ghinho'))

				);

			$this->Nhanvanban->bindModel(array(

									'belongsTo'	=>	array('Vanban')

											   ), false);

			if($iduser == 681) 

				$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' 	=> $iduser,

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

			else

				$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $iduser, 'ngay_xem' => NULL, 'Vanban.tinhtrang_duyet = 1', 'Vanban.loaivanban_id <>'	=> NULL)));

			$vanbanden_trinh = $this->Nhanvanban->find('count', array('conditions' => array('Nhanvanban.nguoi_nhan_id' => $iduser, 'Vanban.tinhtrang_duyet = 0', 'Vanban.chieu_di = 1' )));//văn bản đến

			$vanbanden_duyet = $this->Vanban->find('count', array('conditions' => array('Vanban.nguoi_duyet_id' => $iduser, 'Vanban.tinhtrang_duyet = 2', 'Vanban.chieu_di = 1' )));// văn bản đến

			$vanban_thaydoi = $this->Nhanvanban->find('count', array('conditions' => array('Nhanvanban.nguoi_nhan_id' => $iduser,'Nhanvanban.ngayxem_chidao' => NULL, 'Vanban.gd_chidao <>' => NULL, 'Vanban.tinhtrang_duyet' => 1 )));// văn bản do PGĐ duyệt có sự chỉ đạo của GĐ VTĐN

			//Văn bản có trao đổi, phản hồi

			$this->Vanban->bindModel(

					array(

						'hasOne'	=>	array('Traodoivanban' => array('foreignKey'	=>	'vanban_id',

											  						'className'	=>	'Traodoivanban')

												)

					), false);
			$vb_traodoi = $this->Vanban->find('count', array('conditions' => array('Traodoivanban.nguoi_nhan_id' => $iduser, 'Traodoivanban.ngay_xem' => NULL)));
			if($this->Auth->User('donvi_id')== '')

				$donvi_id = $this->Auth->User('phong_id'); 

			else

				$donvi_id = $this->Auth->User('donvi_id');

			if($this->Auth->User('id')== 683) // phát sinh từ khi chia nhóm P.THHC

				$donvi_id = 20;

			// $vbgap_notice = $this->Vanban->query("

			// 										select count(vanban.id) as vb from (SELECT a.id

			// 										FROM vanban_thongtin a

			// 										left JOIN vanban_nhan b ON a.ID = b.vanban_id

			// 										LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$this->Auth->user('id')."

			// 										where a.vb_gap = 1

			// 										and a.tinhtrang_duyet = 1

			// 										and a.chieu_di = 1

			// 										and a.phongchutri_id = ".$donvi_id."

			// 										and b.nguoi_nhan_id = ".$this->Auth->user('id')."

			// 										and DATE_FORMAT(a.vbgap_ngayhoanthanh, '%Y-%m-%d') < DATE_FORMAT(NOW(), '%Y-%m-%d') 

	  //    											and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL )

			// 										and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$this->Auth->user('id')." and m.capnhat_mucdo = 10)

			// 										GROUP BY a.id) vanban

			// 										");

			//pr($vb_notice);die();

			//$vbgap_notice = $vbgap_notice[0][0]['vb'];

			//pr($vb_notice);die();

			// $vbkhac_notice = $this->Vanban->query("

			// 										select count(vanban.id) as vb from (SELECT a.id

			// 										FROM vanban_thongtin a

			// 										left JOIN vanban_nhan b ON a.ID = b.vanban_id

			// 										LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$this->Auth->user('id')."

			// 										where a.vb_gap = 0

			// 										and a.tinhtrang_duyet = 1

			// 										and a.chieu_di = 1

			// 										and a.phongchutri_id = ".$donvi_id."

			// 										and b.nguoi_nhan_id = ".$this->Auth->user('id')."

			// 										and CheckNgayVB(a.ngay_duyet,NOW()) > 2

			// 										and a.ngay_duyet is not null

			// 										and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL)

			// 										and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$this->Auth->user('id')." and m.capnhat_mucdo = 10)

			// 										GROUP BY a.id) vanban

			// 										");
			// $vbkhac_notice = $vbkhac_notice[0][0]['vb'];
			$tinnhan = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $iduser, 'ngay_nhan' => NULL, 'mark_deleted' => 0)));
			$duocgiao_chuahoanthanh = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoinhan_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'ngay_ketthuc <'	=>	date('Y-m-d', time())

					)));

			$duocgiao_dangthuchien = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoinhan_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'ngay_ketthuc >='	=>	date('Y-m-d', time())

					)));

			$dagiao_chuahoanthanh = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoi_giaoviec_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'ngay_ketthuc <'	=>	date('Y-m-d', time())

					)));

			$dagiao_dangthuchien = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoi_giaoviec_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'parent_id'			=> NULL, 

					'ngay_ketthuc >='	=>	date('Y-m-d', time())

					)));

			$dagiao_khan = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoi_giaoviec_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'tinhchat_id'	=>	11

					)));

			$duocgiao_khan = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoinhan_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'tinhchat_id'	=>	11

					)));

			//trễ tiến độ

			$dagiao_baocao = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoi_giaoviec_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'loaicongviec_id'	=>	1,

					'ngay_ketthuc <'	=>	date('Y-m-d', time())

					)));

			//trễ tiến độ

			$duocgiao_baocao = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoinhan_id' => $iduser,

					'mucdo_hoanthanh <'	=>	10,

					'loaicongviec_id'	=>	1,

					'ngay_ketthuc <'	=>	date('Y-m-d', time())

					)));

			$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien','dagiao_khan','duocgiao_khan', 'lichcanhan', 'vanbanden_trinh', 'vanbanden_duyet','dagiao_baocao','duocgiao_baocao','vanban_thaydoi','vb_traodoi','vbgap_notice','vbkhac_notice'));
			$duocgiao_comment_moi = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoinhan_id' => $iduser,				

					'is_dadoc_comment'	=>	1

					)));

			$dagiao_comment_moi = $this->Congviec->find('count',

				array('conditions' => array(

					'nguoi_giaoviec_id' => $iduser,

					'parent_id'=>NULL,

					'giaoviec_comment'	=>	1

					)));
			if($iduser != 0)
			{
				$res["intmessage"]= 1;
			}
			else
			{
				$res["intmessage"]= 0;
			}
			$res["tinnhan"] = $tinnhan;
			$res["vanban"] = $vanban;
			$res["cvduocgiaochuahoanthanh"] = $duocgiao_chuahoanthanh;
			$res["cvduocgiaodangthuchien"] = $duocgiao_dangthuchien;
			$res["cvdagiaochuahoanthanh"] = $dagiao_chuahoanthanh;
			$res["cvdagiaodangthuchien"] = $duocgiao_dangthuchien;
			$res["cvdagiaokhan"] = $dagiao_khan;
			$res["cvduocgiaokhan"] = $duocgiao_khan;
	        $this->set(compact('res'));
	        $this->set('_serialize', 'res');
		}
	}
 ?>