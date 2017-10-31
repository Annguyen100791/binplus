<?php 

	class ApiTinNhansTestController extends AppController
	{	
		public	$uses = array('Tinnhan', 'Chitiettinnhan', 'Nhanvien','Apiuser','Filetinnhan');
		public function beforeFilter() {
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
			        	        || $this->request->params['action'] == 'reply'
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
				$res[$i]['nguoiguiid'] = $v['Tinnhan']['nguoigui_id'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$num_chitiet = count($v['Chitiettinnhan']) - 6;
				for($k=0;$k < $num_chitiet; $k++){
					$res[$i]['Chitiettinnhan'][$k]['id'] = $v['Chitiettinnhan'][$k]['id'];
					$res[$i]['Chitiettinnhan'][$k]['nguoinhanid'] = $v['Chitiettinnhan'][$k]['nguoinhan_id'];
					$res[$i]['Chitiettinnhan'][$k]['ngaynhan'] = $v['Chitiettinnhan'][$k]['ngay_nhan'];
					$res[$i]['Chitiettinnhan'][$k]['loainguoinhan'] = $v['Chitiettinnhan'][$k]['loai_nguoinhan'];
				}
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$res[$i]['FileTinnhan'][$j]['ten_moi'] = $k['ten_moi'];
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
				$res[$i]['nguoiguiid'] = $v['Tinnhan']['nguoigui_id'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$num_chitiet = count($v['Chitiettinnhan']) - 6;
				for($k=0;$k < $num_chitiet; $k++){
					$res[$i]['Chitiettinnhan'][$k]['id'] = $v['Chitiettinnhan'][$k]['id'];
					$res[$i]['Chitiettinnhan'][$k]['nguoinhanid'] = $v['Chitiettinnhan'][$k]['nguoinhan_id'];
					$res[$i]['Chitiettinnhan'][$k]['ngaynhan'] = $v['Chitiettinnhan'][$k]['ngay_nhan'];
					$res[$i]['Chitiettinnhan'][$k]['loainguoinhan'] = $v['Chitiettinnhan'][$k]['loai_nguoinhan'];
				}
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$res[$i]['FileTinnhan'][$j]['ten_moi'] = $k['ten_moi'];
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
				$res[$i]['nguoiguiid'] = $v['Tinnhan']['nguoigui_id'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$l=0;
				foreach ($v['Chitiettinnhan'] as $k ) {
					$res[$i]['Chitiettinnhan'][$l]['id'] = $k['id'];
					$res[$i]['Chitiettinnhan'][$l]['nguoinhanid'] = $k['nguoinhan_id'];
					$res[$i]['Chitiettinnhan'][$l]['ngaynhan'] = $k['ngay_nhan'];
					$res[$i]['Chitiettinnhan'][$l]['loainguoinhan'] = $k['loai_nguoinhan'];
					$l++;
				}
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$res[$i]['FileTinnhan'][$j]['ten_moi'] = $k['ten_moi'];
						$res[$i]['FileTinnhan'][$j]['ten_moi'] = $k['ten_moi'];
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
				$res[$i]['nguoiguiid'] = $v['Tinnhan']['nguoigui_id'];
				$res[$i]['nguoigui'] = $v['Nguoigui']['full_name'];
				$num_chitiet = count($v['Chitiettinnhan']) - 6;
				for($k=0;$k < $num_chitiet; $k++){
					$res[$i]['Chitiettinnhan'][$k]['id'] = $v['Chitiettinnhan'][$k]['id'];
					$res[$i]['Chitiettinnhan'][$k]['nguoinhanid'] = $v['Chitiettinnhan'][$k]['nguoinhan_id'];
					$res[$i]['Chitiettinnhan'][$k]['ngaynhan'] = $v['Chitiettinnhan'][$k]['ngay_nhan'];
					$res[$i]['Chitiettinnhan'][$k]['loainguoinhan'] = $v['Chitiettinnhan'][$k]['loai_nguoinhan'];
				}
				if(count($v['FileTinnhan'])==0){
					$res[$i]['FileTinnhan']=array();
				}
				else{
					$j=0;
					foreach ($v['FileTinnhan'] as $k) {
						$res[$i]['FileTinnhan'][$j]['id'] = $k['id'];
						$res[$i]['FileTinnhan'][$j]['ten_cu'] = $k['ten_cu'];
						$res[$i]['FileTinnhan'][$j]['ten_moi'] = $k['ten_moi'];
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
				if (!empty($this->request->data['nvselected']))
					$this->request->data['Tinnhan']['nv_selected'] = $this->request->data['nvselected'];			
				$this->request->data['Tinnhan']['nguoinhan_to'] = $this->request->data['nguoinhanto'];
				if (!empty($this->request->data['idRelated'])) {
					$this->request->data['Tinnhan']['related_id'] = $this->request->data['idRelated'];
				}				
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
						if(!empty($this->request->data['nguoinhancc'])){
							$this->request->data['Tinnhan']['nguoinhan_cc'] = $this->request->data['nguoinhancc'];
							$nguoinhancc = explode(",", $this->request->data['Tinnhan']['nguoinhan_cc']);
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
						if(!empty($this->request->data['nguoinhanbcc'])){
							$this->request->data['Tinnhan']['nguoinhan_bcc'] = $this->request->data['nguoinhanbcc'];
							$nguoinhanbcc = explode(",", $this->request->data['Tinnhan']['nguoinhan_bcc']);
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
						if(!empty($this->request->data['FileOld']))
						{
							$this->loadModel('Filetinnhan');
							$fileold = json_decode($this->request->data['FileOld'], true);
							foreach ($fileold as $fileitem) {
								$file = array(
								'id'			=>	NULL,
								'tinnhan_id'	=>	$tinnhan_id,
								'file_path'		=> $fileitem['ten_cu'],
								'ten_cu'		=> $fileitem['ten_cu'],
								'ten_moi'		=>	$fileitem['ten_moi']
								);
								if(!$this->Tinnhan->FileTinnhan->save($file))
								{
									unlink(WWW_ROOT . $attach_path . $fileitem['ten_moi']);
									$dataSource->rollback();
									$res =0;
									exit();
								}
							}
						}
						if (!empty($_FILES)) 
						{						    
							$attach_path = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
							$attach_path = substr($attach_path, 1, strlen($attach_path)-1);
							$tmp_path = str_replace("/", DS, Configure::read('File.tmp'));
							$tmp_path = substr($tmp_path, 1, strlen($tmp_path)-1);
							$newfile = '';
							foreach ($_FILES as $fileitem) {
								$this->loadModel('Filetinnhan');
						        $t = explode(".", $fileitem['name']);
								$ext = end($t);
								$ten_moi = md5(time() + rand());
								if(!move_uploaded_file($fileitem['tmp_name'], WWW_ROOT . $attach_path. $ten_moi)) 
									{
										$res =0;
										exit();
									}
								$file = array(
									'id'			=>	NULL,
									'tinnhan_id'	=>	$tinnhan_id,
									'file_path'		=> $fileitem['name'],
									'ten_cu'		=> $fileitem['name'],
									'ten_moi'		=>	$ten_moi
								);
								if(!$this->Tinnhan->FileTinnhan->save($file))
								{
									unlink(WWW_ROOT . $attach_path . $v['ten_moi']);
									$dataSource->rollback();
									$res =0;
									exit();
								}
						    }
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
		function reply(){
			if(!empty($this->request->data['nhanvien_id']) && !empty($this->request->data['tinnhan_id']))
			{
				$id = $this->request->data['tinnhan_id'];
				$nhanvien_id = $this->request->data['nhanvien_id'];
				$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $id)));		
				$chitiettinnhan = $tinnhan['Chitiettinnhan'];
				$arraystrselect = array();
				$arraynvselect = array();
				$arrayto = array();
				$arraycc = array();
				$arraybcc = array();
				array_push($arrayto,$this->getname($tinnhan['Tinnhan']['nguoigui_id']));
				foreach ($chitiettinnhan as $item) {
					if ($item['nguoinhan_id']!=$nhanvien_id) {
						if ($item['loai_nguoinhan'] =="0") {
							array_push($arraystrselect,$this->getname($item['nguoinhan_id']));
							array_push($arraynvselect,$item['nguoinhan_id']);
						}
						if ($item['loai_nguoinhan'] =="1") {
							array_push($arrayto,$this->getname($item['nguoinhan_id']));
						}
						if ($item['loai_nguoinhan'] =="2") {
							array_push($arraycc,$this->getname($item['nguoinhan_id']));
						}
						if ($item['loai_nguoinhan'] =="3") {
							array_push($arraybcc,$this->getname($item['nguoinhan_id']));
						}
					}
				}
				$res = array();

				if (!empty($arraystrselect)){
					$strselect = implode(", ", $arraystrselect).", ";
					$res['strnvselect'] = $strselect;
					$nvselected = implode(",", $arraynvselect);
					$res['nvselected'] = $nvselected;
				}
				else{
					$res['nvselected'] = null;
					$res['strnvselect'] = null;
				}

				if (!empty($arrayto)){
					$to = implode(", ", $arrayto).", ";
					$res['to'] = $to;
				}
				else
					$res['to'] = null;

				if (!empty($arraycc)){
					$cc = implode(", ", $arraycc).", ";				
					$res['cc'] = $cc;
				}
				else
					$res['cc'] = null;
				if (!empty($arraybcc)) {
					$bcc = implode(", ", $arraybcc).", ";	
					$res['bcc'] = $bcc;
				}
				else
					$res['bcc'] =null;
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
		function getname($idnhanvien){
			if(is_numeric($idnhanvien)){
				$this->loadModel('Apiuser');
				$dataname = $this->Apiuser->find('first',array('conditions' => array('Apiuser.id' => $idnhanvien)));
				$name = $dataname['Apiuser']['username']." - ".$dataname['Apiuser']['fullname'];
				return $name;
			}
			
		}
	}

 ?>