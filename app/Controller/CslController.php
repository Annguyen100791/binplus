<?php
class CslController extends AppController {

	public	$name = 'Csl';
	public	 $uses = array(
			'User', 'UserOld', 'Nhanvien'
    );
	
	public function beforeFilter() {
        parent::beforeFilter();
		Configure::write('debug', 1);
    }
	
	public	function	index()
	{
		/*$this->layout = null;
		
		//$this->User->primaryKey = 'username';
		$this->UserOld->primaryKey = 'tkUsername';
		
		$this->User->bindModel(array('belongsTo' => array('UserOld' => array('className' => 'UserOld', 'foreignKey' => 'username'))), false);
		
		$a = $this->User->find('all');
		//pr($a); die();
		$this->Nhanvien->recursive = -1;
		$r = 0;
		foreach($a as $item)
		{
			if(!empty($item['UserOld']['nvID']))
			{
				$data['Nhanvien'] = array('id' => $item['Nhanvien']['id'], 'id_old' => $item['UserOld']['nvID']);
				if($this->Nhanvien->save($data))
					$r++;
			}
		}
		die('records:' .$r);
		*/
	}
	
	public	function	nhanvien()
	{
		$this->layout = null;
		
		$this->UserOld->primaryKey = 'tkUsername';
		
		$this->User->bindModel(array('belongsTo' => array('UserOld' => array('foreignKey' => 'username'))), false);
		
		$a = $this->User->find('all');
		//pr($a); die();
		//$this->Nhanvien->recursive = -1;
		$r = 0;
		
		$this->loadModel('NhanvienOld');
		$this->NhanvienOld->primaryKey = 'nvID';
		foreach($a as $item)
		{
			if(!empty($item['UserOld']['tkID']))
			{
				$data['NhanvienOld'] = array('nvID' => $item['UserOld']['nvID'], 'id_new' => $item['Nhanvien']['id']);
				if($this->NhanvienOld->save($data))
					$r++;
			}
		}
		die('records:' .$r . ' updated');
	}
	
	public	function	tinhchatvanban()
	{
		$this->layout = null;
		// update old id
		//$this->User->bindModel(array('belongsTo' => array('UserOld' => array('className' => 'UserOld', 'foreignKey' => 'username'))), false);
		$this->loadModel('TinhchatVanbanOld');
		
		$a = $this->TinhchatVanbanOld->find('all', array('order' => 'tcvbID ASC'));
		$this->loadModel('Tinhchatvanban');
		$r = 0;
		foreach($a as $item)
		{
			$data['Tinhchatvanban'] = array(
				'id' 		=> $item['TinhchatVanbanOld']['tcvbID'],
				'ten_tinhchat' => $item['TinhchatVanbanOld']['tcvbTen'],
				'mo_ta' 	=> $item['TinhchatVanbanOld']['tcvbMota'],
				'enabled'	=>	1);
			if($this->Tinhchatvanban->save($data))	
				$r++;
		}
		die('records:' .$r);
	}
	
	public	function	loaivanban()
	{
		$this->layout = null;
		// update old id
	$this->loadModel('LoaiVanbanOld');
		
		$a = $this->LoaiVanbanOld->find('all', array('order' => 'lvbID ASC'));
		$this->loadModel('Loaivanban');
		$r = 0;
		foreach($a as $item)
		{
			$data['Loaivanban'] = array(
				'id' 		=> $item['LoaiVanbanOld']['lvbID'],
				'ten_loaivanban' => $item['LoaiVanbanOld']['lvbTen'],
				'mo_ta' 	=> $item['LoaiVanbanOld']['lvbMota'],
				'chieu_di' 	=> $item['LoaiVanbanOld']['lvbDiden'],
				'enabled'	=>	1);
			if($this->Loaivanban->save($data))	
				$r++;
		}
		die('records:' .$r);
	}
	
	public	function	vanban()
	{
		
		$this->loadModel('Vanban');
		$this->loadModel('VanbanOld');
		
		if(!empty($this->request->data))
		{
			$this->layout = null;
			$r = $this->VanbanOld->find('all', 
				array(
					'page' 	=> 	$this->request->data['Csl']['page'],
					'limit'	=> 	$this->request->data['Csl']['limit'],
					'order'	=>	'vbID ASC')
			);
			//pr($r); die();
			$success = 0;
			foreach($r as $item)
			{
				$data = array(
					'Vanban'	=>	array(
										'id'		=>	$item['VanbanOld']['vbID'],
										'noidung'	=>	$item['VanbanOld']['vbNoidung'],
										'ngay_nhan'	=>	$item['VanbanOld']['vbNgaynhan'],
										'ngay_phathanh'	=>	$item['VanbanOld']['vbNgayphathanh'],
										'ngay_nhap'	=>	$item['VanbanOld']['vbNgaynhap'],
										'ngay_gui'	=>	$item['VanbanOld']['vbNgaygui'],
										'so_hieu'	=>	$item['VanbanOld']['vbSohieu'],
										'chieu_di'	=>	$item['VanbanOld']['vbDiden'],
										'trich_yeu'	=>	$item['VanbanOld']['vbTrichyeu'],
										'nguoi_ky'	=>	$item['VanbanOld']['vbNguoiky'],
										'tinhtrang_duyet'	=>	$item['VanbanOld']['vbDuyet'],
										'nguoi_duyet'	=>	$item['VanbanOld']['vbGhichu'],
										'noidung_duyet'	=>	$item['VanbanOld']['vbNoidungduyet'],
										'noi_gui'	=>	$item['VanbanOld']['vbNoigui'],
										'noi_luu'	=>	$item['NoiluuOld']['id_new'],
										'loaivanban_id'	=>	$item['VanbanOld']['vbLoaiID'],
										'tinhchatvanban_id'	=>	$item['VanbanOld']['vbTinhchatID'],
										'nguoi_nhap_id'	=>	$item['NguoinhapID']['id_new']
									)
				);
				if(!empty($item['NhanvanbanOld']))
				{
					$theodoi = array();
					$nhanvanban = array();
					foreach($item['NhanvanbanOld'] as $nhan)
					{
						if(empty($nhan['nguoinhan_id_new']))
							continue;
						array_push($nhanvanban, array(
							'id'		=>	$nhan['nvbID'],
							'nguoi_nhan_id'	=>	$nhan['nguoinhan_id_new'],
							'vanban_id'		=>	$nhan['nvbVanbanID'],
							'ngay_xem'		=>	$nhan['nvbNgayxem'],
						));
						if(!empty($nhan['theo_doi']))
							array_push($theodoi, array(
													'nguoi_theodoi_id' 	=> $nhan['nguoinhan_id_new'], 
													'vanban_id'			=>	$nhan['nvbVanbanID']));
						
					}
					$data['Nhanvanban'] = $nhanvanban;
					$data['Theodoivanban'] =  $theodoi;
				}
				if(!empty($item['FilevanbanOld']))
				{
					$attach = array();
					foreach($item['FilevanbanOld'] as $file)
					{
						array_push($attach, array(
													'id'			=>	$file['fileID'],
													'	' 	=> 	end(explode('/', $file['fileDuongdan'])), 
													'vanban_id'		=>	$file['fileVanbanID'],
													'file_type'		=>	$file['fileKieu'],
													'file_size'		=>	$file['fileDolon']));
					}
					$data['Filevanban'] = $attach;
				}
				
				if(!empty($item['XulyvanbanOld']))
				{
					$xuly = array();
					foreach($item['XulyvanbanOld'] as $x)
					{
						array_push($xuly, array(
													'id'			=>	$x['xlvbID'],
													'noi_dung' 		=> 	$x['xlvbNoidung'], 
													'vanban_id'		=>	$x['xlvbVanbanID'],
													'nguoi_xuly_id'	=>	$x['nguoixuly_id_new'],
													'ngay_xuly'		=>	$x['xlvbNgay']));
					}
					$data['Xulyvanban'] = $xuly;
				}
				
				if($this->Vanban->saveAssociated($data, array('validate' => false)))
					$success++;
			}
			die(json_encode(array('success' => true, 'total' => $success)));
		}else
		{
			$this->layout = 'default';
		}
	}
	
	public	function	xulyvanban()
	{
		/*
		update tblVanban_xuly A
SET A.nguoixuly_id_new = (select B.id_new from tblNhansu_nhanvien B WHERE B.nvID = A.xlvbNguoixulyID)

		update tblVanban_nhan A
SET A.nguoinhan_id_new = (select B.id_new from tblNhansu_nhanvien B WHERE B.nvID = A.nvbNguoinhanID)
		*/
	}
	
	public	function	tinnhan()
	{
		$this->loadModel('TinnhanOld');
		$this->loadModel('Tinnhan');
		
		if(!empty($this->request->data))
		{
			$groups = $this->TinnhanOld->find('all', 
				array(
					'fields' => array('thongtinNguoigoiID', 'thongtinNgaygoi'),
					'group'	=>	array('thongtinNguoigoiID', 'thongtinNgaygoi'),
					'order'	=>	'thongtinNgaygoi ASC',
					'page'	=>	$this->request->data['Csl']['page'], 
					'limit'	=>	$this->request->data['Csl']['limit'], )
			);
			
			if(!empty($groups))
			{
				$success = 0;
				foreach($groups as $group)
				{
					$tinnhan = $this->TinnhanOld->find('all', array(
							'conditions'	=>	array(
										'thongtinNguoigoiID'	=> $group['TinnhanOld']['thongtinNguoigoiID'],
										'thongtinNgaygoi' 		=> $group['TinnhanOld']['thongtinNgaygoi']),
					));
					if(empty($tinnhan[0]['NguoiguiOld']['id_new']))
						continue;
					//pr($tinnhan);die();
					$data = array(
							'Tinnhan'	=>	array(
								'nguoigui_id'	=>	$tinnhan[0]['NguoiguiOld']['id_new'],
								'tieu_de'		=>	$tinnhan[0]['TinnhanOld']['thongtinTieude'],
								'noi_dung'		=>	$tinnhan[0]['TinnhanOld']['thongtinNoidung'],
								'ngay_gui'		=>	$tinnhan[0]['TinnhanOld']['thongtinNgaygoi'],
								'mark_deleted'	=>	0,
							)
						);
					if(!empty($tinnhan[0]['TinnhanOld']['thongtinFilekem']))
					{
						$file = array(
							'file_path'	=>	end(explode('/', $tinnhan[0]['TinnhanOld']['thongtinFilekem']))
						);
						$data['FileTinnhan'][0] = $file;
					}
					
					$nguoinhan = array();
					foreach($tinnhan as $item)
					{
						if(empty($item['NguoinhanOld']['id_new']))
							continue;
						$n = array(
							'nguoinhan_id'	=>	$item['NguoinhanOld']['id_new'],
							'ngay_nhan'		=>	$item['TinnhanOld']['thongtinNgaygoi'],
							'mark_deleted'	=>	0
						);
						array_push($nguoinhan, $n);
					}
					$data['Chitiettinnhan'] = $nguoinhan;
					//pr($data);die();
					if($this->Tinnhan->saveAssociated($data, array('deep' => true, 'validate' => false)))
						$success++;
				}
				die(json_encode(array('success' => true, 'total' => $success)));
			}else
				die(json_encode(array('success' => false, 'total' => $success)));
		}
		
	}
	
	function	attachfile()
	{
		$this->loadModel('VanbanNew');
		$this->loadModel('Filevanban');
		$this->VanbanNew->bindModel(array('hasMany' => array('FilevanbanOld' => array('foreignKey' => 'fileVanbanID'))), false);
		
		$vanban = $this->VanbanNew->find('all', array('limit' => 10, 'order' => 'id DESC'));
		foreach($vanban as $item)
		{
			$data = 
		}
	}
}