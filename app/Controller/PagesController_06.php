<?php
class PagesController extends AppController 
{
	
	public	$uses = array('Vanban', 'Congviec', 'Nhanvanban', 'Chitiettinnhan', 'Lichlamviec', 'Nhanvien');
	
	public 	function 	home()
	{
		$this->set('title_for_layout', 'Web điều hành - ' . Configure::read('Site.title'));
		
		$this->loadModel('Phong');
		
		$lichphong = null;
		if($this->Auth->user('donvi_id'))
		{
			$lichphong = $this->Lichlamviec->find('all', 
						array(
						'conditions'	=> 	array('pham_vi'		=>	1,
												  'phong_id'	=>	$this->Auth->user('donvi_id'),
												  "DATE_FORMAT(ngay_ghinho, '%Y-%m-%d')"	=>	date('Y-m-d')),
						'order'			=>	'ngay_ghinho ASC'
						)
			);
			
			if(!empty($lichphong))
			{
				
				$phong = $this->Phong->read(array('ten_phong'), $this->Auth->user('donvi_id'));
				$this->set(compact('lichphong', 'phong'));
			}
		}
		
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
			
		$birthday = $this->Nhanvien->find('all', 
					array(
						 'conditions' 	=> 	$conds,
						 'fields'		=>	array('id', 'full_name', 'ngay_sinh'),
						 'order'		=>	"Nhanvien.full_name ASC"
						 )
					);
		$this->set(compact('lichcanhan', 'lichlamviec', 'birthday'));
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
		

		$this->set(compact('vanban', 'tinnhan', 'duocgiao_chuahoanthanh', 'duocgiao_dangthuchien', 'dagiao_chuahoanthanh', 'dagiao_dangthuchien', 'lichcanhan'));
		
		$this->render();
		
	}
	
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
	
}