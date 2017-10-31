<?php 

	class ApitinnhansController extends AppController
	{	
		public	$uses = array('Tinnhan', 'Chitiettinnhan', 'Nhanvien','Apiuser','Filetinnhan');
		public function beforeFilter() {
	     	//parent::beforeFilter();
	     	$user = $this->Auth->user();
		    if ( ( $user == null ) &&
			        ( $this->request->params['controller'] == 'apitinnhans'
			        	        || $this->request->params['action'] == 'unread'
			        	        || $this->request->params['action'] == 'read'
			        	        || $this->request->params['action'] == 'sent'
			        	        || $this->request->params['action'] == 'all'
			        	        || $this->request->params['action'] == 'update'
			        	        || $this->request->params['action'] == 'compose'
			        	        || $this->request->params['action'] == 'get_files'
			        	        || $this->request->params['action'] == 'listdanhsach'
			       	)
		        )
		    {
		    	$this->Auth->allow();
		    }
	    }

	    public	function unread()
		{	
			$iduser = $this->request->data['nhanvien_id']; 
			$this->Tinnhan->bindModel(array(
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					),false); 
			$conds = array(
				'conditions' => array(
					'Chitiettinnhan.ngay_nhan' => NULL,
					'Chitiettinnhan.nguoinhan_id' => $iduser, 
					'Chitiettinnhan.mark_deleted' => 0),
				'order' => array('Tinnhan.ngay_gui' => 'DESC'),
				'limit' => 50
				);

			$res = array();
			$data = $this->Tinnhan->find('all',$conds);
			$i=0;
			foreach ($data as $n => $v) {
				$res[$i]['id'] = $v['Tinnhan']['id'];
				$res[$i]['tieude'] = $v['Tinnhan']['tieu_de'];
				$res[$i]['noidung'] = $v['Tinnhan']['noi_dung'];
				$res[$i]['ngaygui'] = $v['Tinnhan']['ngay_gui'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$res[$i]['nguoinhanid'] = $v['Chitiettinnhan']['nguoinhan_id'];
				$res[$i]['idchitiettinnhan'] = $v['Chitiettinnhan']['id'];
				$res[$i]['ngaynhan'] = $v['Chitiettinnhan']['ngay_nhan'];
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$j++;
					}
				}
				$i++;
			}
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');
		}
		public function read(){

			$iduser = $this->request->data['nhanvien_id'];
			$this->Tinnhan->bindModel(array(
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					), false); 
			$conds = array('conditions' => array(
				'not'=>array(
						'Chitiettinnhan.ngay_nhan' => NULL),
				'Chitiettinnhan.nguoinhan_id' => $iduser, 
				'Chitiettinnhan.mark_deleted' => 0),
				'order' => array('Tinnhan.ngay_gui' => 'DESC'),
				'limit' => 50
				);

			$res = array();
			$data = $this->Tinnhan->find('all',$conds);

			$i=0;
			foreach ($data as $n => $v) {
				$res[$i]['id'] = $v['Tinnhan']['id'];
				$res[$i]['tieude'] = $v['Tinnhan']['tieu_de'];
				$res[$i]['noidung'] = $v['Tinnhan']['noi_dung'];
				$res[$i]['ngaygui'] = $v['Tinnhan']['ngay_gui'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$res[$i]['nguoinhanid'] = $v['Chitiettinnhan']['nguoinhan_id'];
				$res[$i]['idchitiettinnhan'] = $v['Chitiettinnhan']['id'];
				$res[$i]['ngaynhan'] = $v['Chitiettinnhan']['ngay_nhan'];
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$j++;
					}
				}
				$i++;
			}
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');

		}
		public	function	sent()
		{
			$iduser = $this->request->data['nhanvien_id'];
			$this->Tinnhan->bindModel(array(
				'hasMany'		=>	array(
						'Chitiettinnhan'	=>	array(
													'foreignKey'	=>	'tinnhan_id',
											  		'className'		=>	'Chitiettinnhan')
									)
						), false); 
			$conds = array('conditions'	=>array(
							"Tinnhan.nguoigui_id"	=>	$iduser,
							'Tinnhan.mark_deleted'	=>	0),							
						'order' => array('Tinnhan.ngay_gui' => 'DESC'),
						'limit' => 50
						);
							
			$res = array();
			$data = $this->Tinnhan->find('all',$conds);
			$i=0;
			foreach ($data as $n => $v) {
				$res[$i]['id'] = $v['Tinnhan']['id'];
				$res[$i]['tieude'] = $v['Tinnhan']['tieu_de'];
				$res[$i]['noidung'] = $v['Tinnhan']['noi_dung'];
				$res[$i]['ngaygui'] = $v['Tinnhan']['ngay_gui'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$res[$i]['nguoinhanid'] = $v['Chitiettinnhan'][0]['nguoinhan_id'];
				$res[$i]['idchitiettinnhan'] = $v['Chitiettinnhan'][0]['id'];
				$res[$i]['ngaynhan'] = $v['Chitiettinnhan'][0]['ngay_nhan'];
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$j++;
					}
				}
				$i++;
			}
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');
		}
		public	function	all($page = null)
		{	
			// $pagedone = isset($page) ? $page : 1;
			// $intitem = intval($pagedone) * 10;
			$iduser = $this->request->data['nhanvien_id'];
			$this->Tinnhan->bindModel(array(
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					), false); 
			$conds = array('conditions' => array(
				'Tinnhan.mark_deleted' => 0,
						"OR"	=>	array(
							  "Tinnhan.nguoigui_id"	=>	$iduser,
							  "Chitiettinnhan.nguoinhan_id"	=>	$iduser
										  )),
				'order' => array('Tinnhan.ngay_gui' => 'DESC'),
				'limit' => 50
				);

			$res = array();
			$data = $this->Tinnhan->find('all',$conds);
			// if((count($datatmp) - $intitem)>10){
			// 	for ($k= $intitem - 10 ; $k < $intitem; $k++) { 
			// 		$data[$k] = $datatmp[$k];
			// 	}
			// }
			// else{
			// 	for ($k= $intitem - 10 ; $k < count($datatmp); $k++) { 
			// 		$data[$k] = $datatmp[$k];
			// 	}
			// }
			$i=0;
			foreach ($data as $n => $v) {
				$res[$i]['id'] = $v['Tinnhan']['id'];
				$res[$i]['tieude'] = $v['Tinnhan']['tieu_de'];
				$res[$i]['noidung'] = $v['Tinnhan']['noi_dung'];
				$res[$i]['ngaygui'] = $v['Tinnhan']['ngay_gui'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$res[$i]['nguoinhanid'] = $v['Chitiettinnhan']['nguoinhan_id'];
				$res[$i]['idchitiettinnhan'] = $v['Chitiettinnhan']['id'];
				$res[$i]['ngaynhan'] = $v['Chitiettinnhan']['ngay_nhan'];
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$j++;
					}
				}
				$i++;
			}
			
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');
			
		}
		public function update(){
			$idchitiettinnhan = $this->request->data['idchitiettinnhan'];
			$this->Tinnhan->query("UPDATE tinnhan_nguoinhan SET ngay_nhan='" . date('Y-m-d H:i:s', time()) . "' WHERE id=" . $idchitiettinnhan . " AND ngay_nhan IS NULL");

			$ngaynhan = $this->Tinnhan->query("SELECT ngay_nhan FROM tinnhan_nguoinhan WHERE id=" . $idchitiettinnhan);
			$res = array();			
			foreach ($ngaynhan as $value){
				$ngaynhan= $value['tinnhan_nguoinhan']['ngay_nhan'];
			}
			if($ngaynhan!=NULL){
				$res = 1;
			}
			else{
				$res = 0;
			}
			$this->set(compact('res'));
	        $this->set('_serialize', 'res');
		}
		public function listdanhsach(){
			$listdanhsach = array();
			$listdanhsach = $this->Apiuser->find('all',array('fields'=>array('id','username','fullname')));
			$res = array();
			$i = 0;
			foreach ($listdanhsach as $value) {
				$res[$i]['id'] = $value['Apiuser']['id'];
				$res[$i]['username'] = $value['Apiuser']['username'];
				$res[$i]['fullname'] = $value['Apiuser']['fullname'];
				$i++;
			}

			$this->set(compact('res'));
	        $this->set('_serialize', 'res');		
		}
		public function compose(){
			$res = array();
			if (!empty($this->request->data)) 
			{
				App::uses('Sanitize', 'Utility');
				$this->request->data['Tinnhan']['nguoigui_id'] = $this->request->data['nhanvien_id'];
				$this->request->data['Tinnhan']['ngay_gui'] = date("Y-m-d H:i:s");
				$this->request->data['Tinnhan']['chuyen_tiep'] = isset($this->request->data['Tinnhan']['chuyen_tiep']) ? 0 : 1;
				$this->request->data['Tinnhan']['tieu_de'] = $this->request->data['tieude'];
				$this->request->data['Tinnhan']['noi_dung'] = $this->request->data['noidung'];			
				$this->request->data['Tinnhan']['nv_selected'] = $this->request->data['nvselected'];			
				$this->request->data['Tinnhan']['nguoinhan_to'] = $this->request->data['nguoinhanto'];					
				$dataSource = $this->Tinnhan->getDataSource();
				$dataSource->begin();	// begin transaction
				if(!empty($this->request->data['Tinnhan']['nv_selected']))
				$nguoinhanall = explode(",", $this->request->data['Tinnhan']['nv_selected']);
				$nguoinhanto = explode(",", $this->request->data['Tinnhan']['nguoinhan_to']);
				$tam = array();
				$this->request->data['Chitiettinnhan'] = array();	
				if(!empty($nguoinhanall))
					{
						foreach($nguoinhanall as $n)
						{
							array_push($tam, $n);
						}
						if(!empty($this->request->data['Tinnhan']['nguoinhan_to'])){				
							$tam0 = array_diff($tam, $nguoinhanto );
							$tam = $tam0;					
						}
						if(!empty($this->request->data['nguoinhancc'])){
							$this->request->data['Tinnhan']['nguoinhan_cc'] = $this->request->data['nguoinhancc'];
							$nguoinhancc = explode(",", $this->request->data['Tinnhan']['nguoinhan_cc']);				
							$tam1 = array_diff($tam, $nguoinhancc );
							$tam = $tam1;					
						}
						if(!empty($this->request->data['nguoinhanbcc'])){			
							$this->request->data['Tinnhan']['nguoinhan_bcc'] = $this->request->data['nguoinhanbcc'];
							$nguoinhanbcc = explode(",", $this->request->data['Tinnhan']['nguoinhan_bcc']);
							$tam2 = array_diff($tam, $nguoinhanbcc );	
							$tam = $tam2;				
						}
						foreach($tam as $k=>$v)
						{
							array_push($this->request->data['Chitiettinnhan'], array('nguoinhan_id' => $v));					
						}
					}
				else
					{
						$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')));	
					}
				if ($this->Tinnhan->saveAssociated($this->request->data)) 
					{
						$tinnhan_id = $this->Tinnhan->getLastInsertID();	// new Tinnhan ID
						$this->loadModel('Chitiettinnhan');
						// if(!empty($this->request->data['Tinnhan']['nguoinhan_to'])){					
						// 	foreach($this->request->data['Tinnhan']['nguoinhan_to'] as $k => $v)
						if(!empty($nguoinhanto)){					
							foreach($nguoinhanto as $k => $v)
							{						
								$tam = array(
									'id'			=>	NULL,
									'tinnhan_id'	=>	$tinnhan_id,
									'nguoinhan_id'		=> $v,
									'loai_nguoinhan'		=> 1							
										);
								$this->Chitiettinnhan->save($tam);
							}										
						}
						if(!empty($nguoinhancc)){
							$nguoinhancc = array_diff($nguoinhancc, $nguoinhanto);
							foreach($nguoinhancc as $k => $v)
							{
								$tam = array(
									'id'			=>	NULL,
									'tinnhan_id'	=>	$tinnhan_id,
									'nguoinhan_id'		=> $v,
									'loai_nguoinhan'		=> 2							
										);
								$this->Chitiettinnhan->save($tam);
							}
						}
						if(!empty($nguoinhanbcc)){
							$nguoinhanbcc = array_diff($nguoinhanbcc, $nguoinhancc, $nguoinhanto);
							foreach($nguoinhanbcc as $k => $v)
							{
								$tam = array(
									'id'			=>	NULL,
									'tinnhan_id'	=>	$tinnhan_id,
									'nguoinhan_id'		=> $v,
									'loai_nguoinhan'		=> 3							
										);
								$this->Chitiettinnhan->save($tam);
							}
						}
						/*if(!empty($this->request->data['File']))	// attach file

						{
							$attach_path = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
							$attach_path = substr($attach_path, 1, strlen($attach_path)-1);
							$tmp_path = str_replace("/", DS, Configure::read('File.tmp'));
							$tmp_path = substr($tmp_path, 1, strlen($tmp_path)-1);
							$newfile = '';
							//pr($this->request->data['File']);die();
							foreach( $this->request->data['File'] as $k => $v )
							{
								//pr($v);die();
								if(is_numeric($k))	// attach file from reply message
								{
									$this->loadModel('Filetinnhan');
									$tinnhan = $this->Filetinnhan->find('first', array('conditions' => array('Filetinnhan.ten_moi' => $v)));	
									//pr($tinnhan);die();
									$file = array(
									'id'			=>	NULL,
									'tinnhan_id'	=>	$tinnhan_id,
									'file_path'		=> $tinnhan['Filetinnhan']['file_path'],
									'ten_cu'		=> $tinnhan['Filetinnhan']['ten_cu'],
									'ten_moi'		=>	$tinnhan['Filetinnhan']['ten_moi']
											);
									//pr($file);die();
									copy(WWW_ROOT . $tmp_path . $v,  WWW_ROOT . $attach_path . $v);
								} elseif(copy(WWW_ROOT . $tmp_path . $v['ten_moi'],  WWW_ROOT . $attach_path . $v['ten_moi']))
								{
									unlink(WWW_ROOT . $tmp_path . $v['ten_moi']);
									$file = array(
										'id'			=>	NULL,
										'tinnhan_id'	=>	$tinnhan_id,
										'file_path'		=> $v['file_path'],
										'ten_cu'		=> $v['ten_cu'],
										'ten_moi'		=>	$v['ten_moi']
									);
								}
								else // ko copy duoc
								{
									$this->Session->setFlash('Đã phát sinh lỗi khi copy file. Vui lòng thử lại.', 'flash_error');
									$this->redirect('/tinnhan/compose');	
								}
								if(!$this->Tinnhan->FileTinnhan->save($file))
								{
									unlink(WWW_ROOT . $attach_path . $v['ten_moi']);
									$dataSource->rollback();
									$this->Session->setFlash('Đã phát sinh lỗi đính kèm file. Vui lòng thử lại.', 'flash_error');
									$this->redirect('/tinnhan/compose');
								}
							}	// end foreach
						}	// end of if */
						$dataSource->commit();
						$res =1;		
					}
				else 
					{			
						$dataSource->rollback();
						$res =0;
					}
				$this->set(compact('res'));
				$this->set('_serialize', 'res');
	        }
		}
		function get_files($id = null)
		{
			$this->Tinnhan->bindModel(array(
				'hasOne'	=>	array('Filetinnhan' => array('foreignKey' => 'tinnhan_id'),
								'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id')
								),
			), false);
			$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Filetinnhan.id' => $id)));	
			$this->loadModel('Filetinnhan');
			$path = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
			$path = substr($path, 1, strlen($path)-1);
			$path = WWW_ROOT . $path;
			$file_moi = $tinnhan['Filetinnhan']['ten_moi'];
			$file_cu = $tinnhan['Filetinnhan']['ten_cu'];
			$file_contents = file_get_contents($path.$file_moi, true);			
			$this->layout = null;
			Configure::write('debug',0);
			header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $file_cu .'"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path.$file_moi));
			ob_clean();
			flush();
			readfile($path.$file_moi);
			exit;
		}
	}

 ?>