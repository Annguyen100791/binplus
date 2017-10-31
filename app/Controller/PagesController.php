<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
class PagesController extends AppController
{
	public $components = array(
        //'RequestHandler',
		'ImageResizer'
		);
	public	$uses = array('Vanban', 'Congviec', 'Nhanvanban', 'Chitiettinnhan', 'Lichlamviec', 'Nhanvien');

	public 	function 	home()
	{
		$this->set('title_for_layout', 'Web điều hành - ' . Configure::read('Site.title'));

		$this->loadModel('Phong');
		$lichtrungtam = null;
		if($this->Auth->user('donvi_id'))
		{
			$lichtrungtam = $this->Lichlamviec->find('all',
						array(
						'conditions'	=> 	array('pham_vi'		=>	2,
												  'phong_id'	=>	$this->Auth->user('donvi_id'),
												  "DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"	=>	date('Y-m-d')),
						'order'			=>	'ngay_ghinho ASC'
						)
			);

			if(!empty($lichtrungtam))
			{

				$phong = $this->Phong->read(array('ten_phong'), $this->Auth->user('donvi_id'));
				$this->set(compact('lichtrungtam', 'phong'));
			}
		}
		//pr($lichtrungtam);die();

		$lichlamviec = $this->Lichlamviec->find('all',
					array(
					'conditions'	=> 	array('pham_vi'		=>	0,
											  "DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"	=>	date('Y-m-d')),
					'order'			=>	'ngay_ghinho ASC'
					)
		);

		$ds_phong = $this->listPhong('LichCongTac.birthday');

		$conds = array(
						"DATE_FORMAT(Nhanvien.ngay_sinh, '%m-%d')" 	=>  date("m-d"),
						"Nhanvien.tinh_trang"						=>	1
						);
		if($ds_phong !== -1)
			$conds['phong_id'] = $ds_phong;
		$lichphong_vt = $this->Lichlamviec->find('all',
			array('conditions'	=>	array(
						'phong_id'		=>	$this->Auth->user('phong_id'), //phòng đang công tác
						'pham_vi'			=>	1,	// cá nhân
						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),
						'enabled'			=>	1
						),
				  'order'	=>	'ngay_ghinho ASC',
				  'fields'	=>	array('id','tieu_de', 'ngay_ghinho'))
			);
		//pr($lichphong);die();
		if(!empty($lichphong_vt))
			{
				$phong_ban = $this->Phong->read(array('ten_phong'), $this->Auth->user('phong_id'));
				//pr($phong_ban);die();
				$this->set(compact('lichphong_vt','phong_ban'));
			}
		$lichphong_tt = $this->Lichlamviec->find('all',
			array('conditions'	=>	array(
						'phong_id'		=>	$this->Auth->user('phong_id'), //phòng đang công tác
						'pham_vi'			=>	3,	// cá nhân
						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),
						'enabled'			=>	1
						),
				  'order'	=>	'ngay_ghinho ASC',
				  'fields'	=>	array('id','tieu_de', 'ngay_ghinho'))
			);
		//pr($lichphong_tt);die();
		if(!empty($lichphong_tt))
			{
				$phong_ban = $this->Phong->read(array('ten_phong'), $this->Auth->user('phong_id'));
				//pr($phong_ban);die();
				$this->set(compact('lichphong_tt','phong_ban'));
			}
		$lichcanhan = $this->Lichlamviec->find('all',
			array('conditions'	=>	array(
						'nguoi_nhap_id'		=>	$this->Auth->user('nhanvien_id'),
						'pham_vi'			=>	2,	// cá nhân
						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),
						'enabled'			=>	1
						),
				  'order'	=>	'ngay_ghinho ASC',
				  'fields'	=>	array('tieu_de', 'ngay_ghinho'))
			);
		//pr($lichcanhan);die();
		$birthday = $this->Nhanvien->find('all',
					array(
						 'conditions' 	=> 	$conds,
						 'fields'		=>	array('id', 'full_name', 'ngay_sinh'),
						 'order'		=>	"Nhanvien.full_name ASC"
						 )
					);
		$this->set(compact('lichlamviec','lichcanhan', 'birthday'));
	}

	public	function	notice()
	{
		/*
			Notice
		*/
		$this->loadModel('Lichlamviec');

		$lichcanhan = $this->Lichlamviec->find('all',
			array('conditions'	=>	array(
						'nguoi_nhap_id'		=>	$this->Auth->user('nhanvien_id'),
						'pham_vi'			=>	2,	// cá nhân
						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),
						'enabled'			=>	1
						),
				  'order'	=>	'ngay_ghinho ASC',
				  'fields'	=>	array('tieu_de', 'ngay_ghinho'))
			);
		//pr($lichcanhan);die();
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);

		///////////
		if($this->Auth->user('nhanvien_id') == 681) 
			$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' 	=> $this->Auth->user('nhanvien_id'),
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
			$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_xem' => NULL, 'Vanban.tinhtrang_duyet = 1', 'Vanban.loaivanban_id <>'	=> NULL)));
		$vanbanden_trinh = $this->Nhanvanban->find('count', array('conditions' => array('Nhanvanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'Vanban.tinhtrang_duyet = 0', 'Vanban.chieu_di = 1' )));//văn bản đến
		$vanbanden_duyet = $this->Vanban->find('count', array('conditions' => array('Vanban.nguoi_duyet_id' => $this->Auth->user('nhanvien_id'), 'Vanban.tinhtrang_duyet = 2', 'Vanban.chieu_di = 1' )));// văn bản đến
		$vanban_thaydoi = $this->Nhanvanban->find('count', array('conditions' => array('Nhanvanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id'),'Nhanvanban.ngayxem_chidao' => NULL, 'Vanban.gd_chidao <>' => NULL, 'Vanban.tinhtrang_duyet' => 1 )));// văn bản do PGĐ duyệt có sự chỉ đạo của GĐ VTĐN
		//Văn bản có trao đổi, phản hồi
		$this->Vanban->bindModel(
				array(
					'hasOne'	=>	array('Traodoivanban' => array('foreignKey'	=>	'vanban_id',
										  						'className'	=>	'Traodoivanban')
											)
				), false);

		$vb_traodoi = $this->Vanban->find('count', array('conditions' => array('Traodoivanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'Traodoivanban.ngay_xem' => NULL)));
		//pr($vb_traodoi);die();
		if($this->Auth->User('donvi_id')== '')
			$donvi_id = $this->Auth->User('phong_id'); 
		else
			$donvi_id = $this->Auth->User('donvi_id');
		if($this->Auth->User('nhanvien_id')== 683) // phát sinh từ khi chia nhóm P.THHC
			$donvi_id = 20;
		$vbgap_notice = $this->Vanban->query("
												select count(vanban.id) as vb from (SELECT a.id
												FROM vanban_thongtin a
												left JOIN vanban_nhan b ON a.ID = b.vanban_id
												LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$this->Auth->user('nhanvien_id')."
												where a.vb_gap = 1
												and a.tinhtrang_duyet = 1
												and a.chieu_di = 1
												and a.phongchutri_id = ".$donvi_id."
												and b.nguoi_nhan_id = ".$this->Auth->user('nhanvien_id')."
												and DATE_FORMAT(a.vbgap_ngayhoanthanh, '%Y-%m-%d') < DATE_FORMAT(NOW(), '%Y-%m-%d') 
     											and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL )
												and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$this->Auth->user('nhanvien_id')." and m.capnhat_mucdo = 10)
												GROUP BY a.id) vanban
												");
		//pr($vb_notice);die();
		$vbgap_notice = $vbgap_notice[0][0]['vb'];
		//pr($vb_notice);die();
		$vbkhac_notice = $this->Vanban->query("
												select count(vanban.id) as vb from (SELECT a.id
												FROM vanban_thongtin a
												left JOIN vanban_nhan b ON a.ID = b.vanban_id
												LEFT JOIN vanban_nhanketqua c ON a.id = c.vanban_id and c.nguoi_capnhat_id = ".$this->Auth->user('nhanvien_id')."
												where a.vb_gap = 0
												and a.tinhtrang_duyet = 1
												and a.chieu_di = 1
												and a.phongchutri_id = ".$donvi_id."
												and b.nguoi_nhan_id = ".$this->Auth->user('nhanvien_id')."
												and CheckNgayVB(a.ngay_duyet,NOW()) > 2
												and a.ngay_duyet is not null
												and (c.capnhat_mucdo < 10 OR c.ngay_capnhat IS NULL)
												and a.id NOT IN (select distinct m.vanban_id from vanban_nhanketqua m where m.nguoi_capnhat_id = ".$this->Auth->user('nhanvien_id')." and m.capnhat_mucdo = 10)
												GROUP BY a.id) vanban
												");
		//pr($vb_notice);die();
		$vbkhac_notice = $vbkhac_notice[0][0]['vb'];
		
		
		//////////
		$tinnhan = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_nhan' => NULL, 'mark_deleted' => 0)));

		$duocgiao_chuahoanthanh = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		$duocgiao_dangthuchien = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc >='	=>	date('Y-m-d', time())
				)));
		$dagiao_chuahoanthanh = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		$dagiao_dangthuchien = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'parent_id'			=> NULL, 
				'ngay_ketthuc >='	=>	date('Y-m-d', time())
				)));
		$dagiao_khan = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'tinhchat_id'	=>	11
				)));
		$duocgiao_khan = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'tinhchat_id'	=>	11
				)));
		//trễ tiến độ
		$dagiao_baocao = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'loaicongviec_id'	=>	1,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		//trễ tiến độ
		$duocgiao_baocao = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'loaicongviec_id'	=>	1,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien','dagiao_khan','duocgiao_khan', 'lichcanhan', 'vanbanden_trinh', 'vanbanden_duyet','dagiao_baocao','duocgiao_baocao','vanban_thaydoi','vb_traodoi','vbgap_notice','vbkhac_notice'));
		
				/********** Hà thêm ********/
		$duocgiao_comment_moi = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),				
				'is_dadoc_comment'	=>	1
				)));
		$dagiao_comment_moi = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'parent_id'=>NULL,
				'giaoviec_comment'	=>	1
				)));
		$this->set(compact('duocgiao_comment_moi','dagiao_comment_moi'));
		/*********** end Hà thêm ************/

		
		$this->render();

	}
	/*public	function	notice()
	{

			//Notice

		$this->loadModel('Lichlamviec');

		$lichcanhan = $this->Lichlamviec->find('all',
			array('conditions'	=>	array(
						'nguoi_nhap_id'		=>	$this->Auth->user('nhanvien_id'),
						'pham_vi'			=>	2,	// cá nhân
						"DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"		=>	date('Y-m-d'),
						'enabled'			=>	1
						),
				  'order'	=>	'ngay_ghinho ASC',
				  'fields'	=>	array('tieu_de', 'ngay_ghinho'))
			);

		$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_xem' => NULL)));


		$tinnhan = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_nhan' => NULL, 'mark_deleted' => 0)));

		$duocgiao_chuahoanthanh = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		$duocgiao_dangthuchien = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc >='	=>	date('Y-m-d', time())
				)));
		$dagiao_chuahoanthanh = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc <'	=>	date('Y-m-d', time())
				)));
		$dagiao_dangthuchien = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'ngay_ketthuc >='	=>	date('Y-m-d', time())
				)));
		$dagiao_khan = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'tinhchat_id'	=>	11
				)));
		$duocgiao_khan = $this->Congviec->find('count',
			array('conditions' => array(
				'nguoinhan_id' => $this->Auth->user('nhanvien_id'),
				'mucdo_hoanthanh <'	=>	10,
				'tinhchat_id'	=>	11
				)));

		$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien','dagiao_khan','duocgiao_khan', 'lichcanhan'));

		$this->render();

	}*/

	public	function	thongtin()
	{
		$this->layout = null;

		$this->loadModel('Tintuc');
		$this->paginate = array(
        		'limit' => 	1,
				'order'	=>	'Tintuc.id DESC'
        );

		$this->Tintuc->recursive = -1;
		$thongtin =  $this->paginate('Tintuc', null);
		$this->set(compact('thongtin'));
	}

	public	function	chinhsach()
	{
		$this->layout = null;

		$this->loadModel('Chinhsach');
		$this->paginate = array(
        		'limit' => 	1,
				'order'	=>	'Chinhsach.id DESC'
        );
		$this->Chinhsach->recursive = -1;
		$chinhsach =  $this->paginate('Chinhsach', null);
		$this->set(compact('chinhsach'));

	}

	public	function	mobile_home()
	{
		$vanban = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_xem' => NULL)));
		$tinnhan = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_nhan' => NULL, 'mark_deleted' => 0)));

		$duocgiao_chuahoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10)));
		$dagiao_chuahoanthanh = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc <' => date('Y-m-d'))));
		$dagiao_dangthuchien = $this->Chitietcongviec->find('count', array('conditions' => array('nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'), 'mucdo_hoanthanh <' => 10, 'ngay_ketthuc >=' => date('Y-m-d'))));

		$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien'));

		$this->set('title_for_layout', 'BIN+ Mobile - Trang tin');
	}

	public	function	fix()
	{
		$this->loadModel('Nhanvanban');

		$ds = $this->Nhanvanban->query("select * from ds");
		$success = 0;
		foreach($ds as $item)
		{
			$count = $this->Nhanvanban->find('count', array(
				'conditions' => array('vanban_id' 	=> $item['ds']['vanban_id'],
				'nguoi_nhan_id'	=>		$item['ds']['nguoinhan_id'])));
			if($count <= 0)
			{
				if($this->Nhanvanban->save(array(
					'id'		=>	NULL,
					'vanban_id'	=>	$item['ds']['vanban_id'],
					'nguoi_nhan_id'	=>	$item['ds']['nguoinhan_id']
				)))
					$success++;
			}
		}
		echo $success;
		die();
	}

	public	function	csl()
	{
		$this->loadModel('CongviecOld');
		$this->loadModel('ChitietcongviecOld');
		/*
		$this->CongviecOld->bindModel(array(
			'hasMany'	=>	array(
				'ChitietcongviecOld' => array('foreignKey' => 'congviec_id')
			)
		));
		*/

		$this->loadModel('Congviec');
		$ds = $this->CongviecOld->find('all', array('order' => 'id ASC'));

		foreach($ds as $item)
		{
			$congviec_chinh = array(
				'id'			=>	NULL,
				'ten_congviec'	=>	$item['CongviecOld']['ten_congviec'],
				'vanban_id'	=>	$item['CongviecOld']['vanban_id'],
				'tinhchat_id'	=>	$item['CongviecOld']['tinhchat_id'],
				'ngay_batdau'	=>	$item['CongviecOld']['ngay_batdau'],
				'ngay_ketthuc'	=>	$item['CongviecOld']['ngay_ketthuc'],
				'nguoi_giaoviec_id'	=>	$item['CongviecOld']['nguoi_giaoviec_id'],
				'ngay_giao'	=>	$item['CongviecOld']['ngay_giao'],
				'nguoinhan_id'	=>	$item['CongviecOld']['nv_chiutrachnhiem'],
				'giaoviec_tiep'	=>	$item['CongviecOld']['quyen_giaoviec'],
				'noi_dung'	=>	$item['CongviecOld']['noi_dung'],
				'mucdo_hoanthanh'	=>	$item['CongviecOld']['mucdo_hoanthanh'],
				'parent_id'	=>	NULL,
				'ngay_capnhat'	=>	$item['CongviecOld']['ngay_capnhat']
			);

			if($this->Congviec->save($congviec_chinh))
			{
				$congviec_id = $this->Congviec->getLastInsertID();
				$this->Congviec->query("UPDATE congviec_thongtin SET congviecchinh_id=" . $congviec_id . " WHERE id=" . $congviec_id);

				$chitiet = $this->ChitietcongviecOld->generateTree($item['CongviecOld']['id']);
				if(!empty($chitiet))
					foreach($chitiet as $child)
					{
						$data = array(
							'id'	=>	NULL,
							'ten_congviec'	=>	$child['ChitietcongviecOld']['ten_congviec'],
							'vanban_id'	=>	$item['CongviecOld']['vanban_id'],
							'tinhchat_id'	=>	$item['CongviecOld']['tinhchat_id'],
							'ngay_batdau'	=>	$child['ChitietcongviecOld']['ngay_batdau'],
							'ngay_batdau'	=>	$child['ChitietcongviecOld']['ngay_ketthuc'],
							'nguoi_giaoviec_id'	=>	$child['ChitietcongviecOld']['nguoi_giaoviec_id'],
							'ngay_giao'	=>	$child['ChitietcongviecOld']['ngay_giaoviec'],
							'nguoinhan_id'	=>	$child['ChitietcongviecOld']['nguoinhan_id'],
							'giaoviec_tiep'	=>	$child['ChitietcongviecOld']['quyen_giaoviec'],
							'noi_dung'	=>	$child['ChitietcongviecOld']['noi_dung'],
							'mucdo_hoanthanh'	=>	$child['ChitietcongviecOld']['mucdo_hoanthanh'],
							'parent_id'	=>	$congviec_id,
							'ngay_capnhat'	=>	$child['ChitietcongviecOld']['ngay_capnhat']
						);

						$this->Congviec->save($data);
					}
			}
		}
		die();
	}

	public	function	test()
	{
		$this->loadModel('FileManager');
		$path = APP . WEBROOT_DIR . DS . 'anh';
		$files = $this->FileManager->readFolder('anh');

//		pr($files); die();


		$dest = APP . WEBROOT_DIR . DS . 'result';


		Configure::write('debug', 1);
        foreach($files[1] as $k => $v)
        {
            //$item = new File($path . DS . $v);
           //echo mb_convert_encoding($v);
          $f = explode('.', $v);
          $ext = end($f);
          $n = explode('-', $f[0]);
          //echo trim($n[0]) . '.' . $ext . '<BR>';
          //echo $v;

          //echo urlencode($item->name);
          //rename('/files/' . $v, '/files/' . trim($n[0]) . '.' . $ext );

		  $filename = trim($n[0]) . '.' . $ext;
		  //echo $filename;

		  $f = $this->ImageResizer->resizeImage($path . DS . $v, array(
															  'maxWidth'	=>	203,
															  'maxHeight'	=>	271,
															  'output'		=>	$dest . DS . $filename,
															  'cropZoom'	=>	false,
															  'deleteSource'=>	false
															  ));

           if($f) echo $filename;

        }
        die();
	}
}
