<?php
/*
 * Tinnhan controller
 *
 * Controller cho đối tượng Tin nhắn
 *
 * PHP version 5
 *
 * @category Controllers
 * @package  BIN
 * @version  1.0
 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
class TinnhanController extends AppController {

	protected $paginate = array(
        'limit' => 	20,
		'order'	=>	'Tinnhan.id DESC'
        );
	
	public $helpers = array(
        'Bin'
    );
	
	public $components = array(
		'Bin'
    );
	
	public function 	index()
	{
		if(!$this->check_permission('TinNhan.danhsach'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		$this->set('title_for_layout', 'Quản lý Tin nhắn');
		
		// kiểm tra quyền hạn giao việc theo phòng
		$nv = $this->cboNhanvien('TinNhan.soanthao');
	
		$this->set(compact('nv'));
	}
	
	/*public function csl($page = 1)
	{
		$this->loadModel('Filetinnhan');
		$ds = $this->Filetinnhan->find('all', array('page' => $page, 'limit' => '50', 'order' => 'id ASC '));
		//pr($ds);die();
		foreach($ds as $file)
		{
			$ten_moi = md5(time().$file['Filetinnhan']['file_path']);
			//pr($file);die();
			
			if($this->Filetinnhan->save(array(
										'id' 		=> $file['Filetinnhan']['id'],
										'ten_cu' 	=> $file['Filetinnhan']['file_path'],
										'ten_moi' 	=> $ten_moi
									)))
			{
				//pr($ten_moi);die();
				$path = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
				$path = substr($path, 1, strlen($path)-1);
				$path = WWW_ROOT . $path;
				//pr($path);die();
				//echo $path . $file['Filevanban']['file_path']. "<br>";
				
				//pr($path.$file['Filevanban']['path']);die();
					@rename($path . $file['Filetinnhan']['file_path'], $path . $ten_moi);
			}
			else
				$this->Session->setFlash('Chuyển số liệu bị lỗi.', 'flash_attention');
		}
		$count = $this->Filetinnhan->find('count', array('conditions' => array('not'=>array('ten_cu' => null))));
		pr($count);die();	
	}*/
	
	public	function	mark_read()
	{
		$this->layout = null;
		$f = false;
		//pr($this->request->data);die();
		if(!empty($this->request->data))
		{
			$this->loadModel('Chitiettinnhan');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			//pr($ids);die();
			foreach($ids as $k=>$v)
			{
				if($this->Chitiettinnhan->updateAll(array('ngay_nhan' => "'" . date('Y-m-d H:i:s') . "'"), array('Chitiettinnhan.id' => $v, 'Chitiettinnhan.nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'Chitiettinnhan.ngay_nhan' => NULL)))
					$success++;
			}
			if($success > 0)
				$f = true;
		
		}
		if($f)
			$this->Session->setFlash('Đã đánh dấu thành công ' . $success . ' mục.', 'flash_success');
		else
			$this->Session->setFlash('Đã phát sinh lỗi khi đánh dấu tin nhắn.', 'flash_error');
		$this->redirect('/tinnhan/unread');
	}
	
	public	function	unread()
	{
			
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
						),
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					), false); 
		
		$conds = array();
		
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				$conds	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
									&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoigui_id']))
			{
				$conds['Tinnhan.nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
				$this->passedArgs['nguoigui_id'] = $this->data['Tinnhan']['nguoigui_id'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoigui_id']))
				$this->passedArgs['nguoigui_id'] = $this->passedArgs['nguoigui_id'];
		}
		
		$conds['Chitiettinnhan.nguoinhan_id']	= $this->Auth->user('nhanvien_id');
		$conds['Chitiettinnhan.ngay_nhan'] 	= NULL;
		$conds['Chitiettinnhan.mark_deleted'] 	= 0;
		$ds =  $this->paginate('Tinnhan', $conds);
		
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	
	public	function	read()
	{
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
						),
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  'className'	=>	'Chitiettinnhan')
						)
					), false); 
		$conds = array("NOT"	=>	array('Chitiettinnhan.ngay_nhan' => NULL),
					   "Chitiettinnhan.nguoinhan_id"	=>	$this->Auth->user('nhanvien_id'),
					   'Chitiettinnhan.mark_deleted'	=>	0);
		
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				$t	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
				&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoigui_id']))
			{
				$conds['Tinnhan.nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
				$this->passedArgs['nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
			{
				$t	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoigui_id']))
				$conds['Tinnhan.nguoigui_id'] = $this->passedArgs['nguoigui_id'];
		}
		
		$ds =  $this->paginate('Tinnhan', $conds);
		
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	
	public	function	sent()
	{
		$this->Tinnhan->bindModel(array(
			'hasMany'		=>	array(
					'Chitiettinnhan'	=>	array(
												'foreignKey'	=>	'tinnhan_id',
										  		'className'		=>	'Chitiettinnhan')
								)
					), false); 
					
		$this->Tinnhan->recursive = 2;
		$conds = array("Tinnhan.nguoigui_id"	=>	$this->Auth->user('nhanvien_id'),
						'Tinnhan.mark_deleted'	=>	0);
						
		
		if(!empty($this->request->data))	// if search
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				
				$t	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
				&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoinhan_id']))
			{
				$conds['Chitiettinnhan.nguoinhan_id'] = $this->request->data['Tinnhan']['nguoinhan_id'];
				$this->passedArgs['nguoinhan_id'] = $this->request->data['Tinnhan']['nguoinhan_id'];
				$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')), false);
				$this->Tinnhan->bindModel(array(
					'hasOne'	=>	array(
							'Chitiettinnhan'	=>	array(
												'foreignKey'=>	'tinnhan_id',
												'className'	=>	'Chitiettinnhan')
								)
							), false); 
			}
		}elseif(isset($this->passedArgs))	// phân trang của search
		{
			if(!empty($this->passedArgs['keyword']))
			{
				$t	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoinhan_id']))
			{
				$conds['Chitiettinnhan.nguoinhan_id'] = $this->passedArgs['nguoinhan_id'];
				$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')), false);
				$this->Tinnhan->bindModel(array(
					'hasOne'	=>	array(
							'Chitiettinnhan'	=>	array(
												'foreignKey'=>	'tinnhan_id',
												'className'	=>	'Chitiettinnhan')
								)
							), false); 
			}
		}
		$ds =  $this->paginate('Tinnhan', $conds);
		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	//Thêm all theo chỉ đạo GĐ VTĐN
	public	function	all()
	{
		
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
						),
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					), false);
		
		$conds = array();
		
		//////////
		$conds	= array('Tinnhan.mark_deleted' => 0,
					"OR"	=>	array(
						  "Tinnhan.nguoigui_id"	=>	$this->Auth->user('nhanvien_id'),
						  "Chitiettinnhan.nguoinhan_id"	=>	$this->Auth->user('nhanvien_id')
									  )
					);
		//////////
		
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				$conds	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
									&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			
		}
		$this->Tinnhan->recursive = 2;
		$dumpPaginate = $this->paginate;
		$this->paginate = array_merge($dumpPaginate,array("conditions" => $conds, "group" => array("Tinnhan.id")));
		$ds =  $this->paginate('Tinnhan');
		// pr($ds);die();
		$this->paginate = $dumpPaginate;
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}
	//
	public	function	compose()
	{
		
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền soạn thảo tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->set('title_for_layout', 'Soạn thảo Tin nhắn');
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			$this->request->data['Tinnhan']['nguoigui_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Tinnhan']['ngay_gui'] = date("Y-m-d H:i:s");
			$this->request->data['Tinnhan']['chuyen_tiep'] = isset($this->request->data['Tinnhan']['chuyen_tiep']) ? 0 : 1;
			//pr($this->request->data); die();
			$dataSource = $this->Tinnhan->getDataSource();
			$dataSource->begin();	// begin transaction
			
			$nguoinhan = explode(",", $this->request->data['Tinnhan']['nv_selected']);
				
			$this->request->data['Chitiettinnhan'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Chitiettinnhan'], array('nguoinhan_id' => $n));
				}
			}else
			{
				$this->Session->setFlash('Vui lòng chọn người nhận.', 'flash_error');
				$this->redirect('/tinnhan/compose');
			}
			//pr($this->request->data['File']);die();
			if ($this->Tinnhan->saveAssociated($this->request->data)) 
			{
				$tinnhan_id = $this->Tinnhan->getLastInsertID();	// new Tinnhan ID
				
				if(!empty($this->request->data['File']))	// attach file
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
					}	// end for
				}	// end of if
				
				$dataSource->commit();
				$this->Session->setFlash('Tin nhắn đã gửi thành công.', 'flash_success');
				$this->redirect('/tinnhan/compose');
				
            } else {	// lỗi khi save data
				
				$dataSource->rollback();
				$this->Session->setFlash('Đã phát sinh lỗi trong khi gửi tin nhắn. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/tinnhan/compose');
            }
        }else	// show form compose
		{
			$this->loadModel('Nhanvien');
			$nguoi_gui = $this->Auth->user('nhanvien_id');
			$tinnhan = null;
			if(!empty($this->passedArgs['replyto']))
			{
//				//$this->Tinnhan->recursive = -1;
				//$this->Tinnhan->unbind(array('hasMany' => array('Chitiettinnhan')));
				$this->Tinnhan->bindModel(array(
					'hasOne'		=>	array(
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
												  'className'	=>	'Chitiettinnhan')
								)
							), false); 
				$tinnhan = $this->Tinnhan->find('first', array(
					'conditions' => array(
						'Tinnhan.id' 					=> $this->passedArgs['replyto'],
						'Chitiettinnhan.nguoinhan_id' 	=> $this->Auth->user('nhanvien_id'),
						'Chitiettinnhan.mark_deleted'	=>	0)));
				$sl=1;
				//pr($sl);die();
				$this->set('sl',$sl);
				$this->set('related_id', !empty($tinnhan['Tinnhan']['related_id']) ? $tinnhan['Tinnhan']['related_id'] : $this->passedArgs['replyto']);
				//}
			}elseif(!empty($this->passedArgs['replytoall']))
			{
				$this->Tinnhan->bindModel(array(
					'hasOne'		=>	array(
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
												  'className'	=>	'Chitiettinnhan')
								)
							), false); 
				$tinnhan = $this->Tinnhan->find('first', array(
					'conditions' => array(
						'Tinnhan.id' 					=> $this->passedArgs['replytoall'],
						'Chitiettinnhan.nguoinhan_id' 	=> $this->Auth->user('nhanvien_id'),
						'Chitiettinnhan.mark_deleted'	=>	0)));
				$ds_nguoinhan = $this->Tinnhan->Chitiettinnhan->find('all',array('conditions' =>
									array('tinnhan_id' => $this->passedArgs['replytoall'], 'nguoinhan_id <>' => $this->Auth->user('nhanvien_id')) 
									));
				/*$ds_nguoinhan = $this->Tinnhan->Chitiettinnhan->find('all',array('conditions' =>
									array('tinnhan_id' => $this->passedArgs['replytoall'])
									));*/
				//pr($ds_nguoinhan);die();					
				//$sl=count($ds_nguoinhan);
				$nguoi_gui = $this->Tinnhan->field('nguoigui_id', array('Tinnhan.id' => $this->passedArgs['replytoall']));
				//pr($nguoi_gui);die();	
				$this->set(compact('ds_nguoinhan','nguoi_gui'));		
				$this->set('related_id', !empty($tinnhan['Tinnhan']['related_id']) ? $tinnhan['Tinnhan']['related_id'] : $this->passedArgs['replytoall']);
				//}
			}elseif(!empty($this->passedArgs['sendto']))
			{
				$tinnhan['Tinnhan']['nguoigui_id'] = $this->passedArgs['sendto'];
			} elseif ( !empty( $this->passedArgs['forwardto'] ) )
			{
				$this->Tinnhan->bindModel(array(
					'hasOne'		=>	array(
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
												  'className'	=>	'Chitiettinnhan')
								)
							), false); 
				$tinnhan = $this->Tinnhan->find('first', array(
					'conditions' => array(
						'Tinnhan.id' 					=> $this->passedArgs['forwardto'],
						'Chitiettinnhan.nguoinhan_id' 	=> $this->Auth->user('nhanvien_id'),
						'Chitiettinnhan.mark_deleted'	=>	0)));
				$tinnhan = $this->Tinnhan->read(null, $this->passedArgs['forwardto']);
				unset($tinnhan['Tinnhan']['nguoigui_id']);
			}
			
			$signature = $this->Nhanvien->field('signature', array('Nhanvien.id' => $this->Auth->user('nhanvien_id')));
			
			$this->set(compact('tinnhan', 'signature','nguoi_gui'));
		}
	}
	////

	////
	function	get_files($id = null) 
	{
		$this->Tinnhan->bindModel(array(
			'hasOne'	=>	array('Filetinnhan' => array('foreignKey' => 'tinnhan_id'),
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id')
							),
		), false);
	
		$nv = $this->Auth->user('nhanvien_id');
		$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Filetinnhan.id' => $id, 'Tinnhan.nguoigui_id' => $nv )));
		//pr($tinnhan);die();
		
		if (empty($tinnhan))
			$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Filetinnhan.id' => $id, 'Chitiettinnhan.nguoinhan_id' => $nv )));			
		
		//var_dump($tinnhan);die();
		if(empty($tinnhan))
			throw new InternalErrorException('Bạn không được phép download file này. Vui lòng chọn file khác.');
			
		$this->loadModel('Filetinnhan');
		
		//$data = $this->Filevanban->find('first', array('conditions' => array('Filevanban.id' => $id)));
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
		//gởi header đến cho browser
		/*header('Content-type: application/octet-stream');
		header('Content-Transfer-Encoding: Binary');
		header('Content-disposition: attachment; filename="'.$file_cu.'"');
		*/
		//readfile($path.$file_moi);
		//đọc file và trả dữ liệu về cho browser

	}
	
	public	function	unread_delete()
	{
		if(!$this->check_permission('TinNhan.soanthao'))	// có quyền soạn thảo tin nhắn thì được quyền xóa
			throw new InternalErrorException('Bạn không có quyền xóa tin nhắn. Vui lòng liên hệ quản trị để cấp quyền.');	
			
		$this->layout = null;
		if(!empty($this->request->data))
		{
			$this->loadModel('Chitiettinnhan');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach( $ids as $k => $v )
			{
				if(!empty($v))
				{
					$data = $this->Chitiettinnhan->find('first', array('conditions' => array('Chitiettinnhan.id' => $v, 'nguoinhan_id' => $this->Auth->user('nhanvien_id'))));
					if(!empty($data) && $this->Chitiettinnhan->save(array('id' => $v, 'mark_deleted' => 1)))
					{
						if($this->Tinnhan->Chitiettinnhan->find('count', 
							array('conditions' => array('tinnhan_id' => $v, 'Chitiettinnhan.mark_deleted' => 0))) == 0
						&&  $this->Tinnhan->find('count', 
							array('conditions' => array('Tinnhan.id' => $v, 'Tinnhan.mark_deleted' => 0))) == 0)
						
						$this->Tinnhan->delete($v);	// nếu tất cả người gửi và nhận đều đánh dấu deleted thì xóa
						$success++;
					}
				}
				
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('tinnhan/unread');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('tinnhan/unread');
	}
	
	public	function	read_delete()
	{
		if(!$this->check_permission('TinNhan.soanthao'))	// có quyền soạn thảo tin nhắn thì được quyền xóa
			throw new InternalErrorException('Bạn không có quyền xóa tin nhắn. Vui lòng liên hệ quản trị để cấp quyền.');	
			
		$this->layout = null;
		if(!empty($this->request->data))
		{
			$this->loadModel('Chitiettinnhan');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach( $ids as $k => $v )
			{
				if(!empty($v))
				{
					$data = $this->Chitiettinnhan->find('first', array('conditions' => array('Chitiettinnhan.id' => $v, 'nguoinhan_id' => $this->Auth->user('nhanvien_id'))));
					if(!empty($data) && $this->Chitiettinnhan->save(array('id' => $v, 'mark_deleted' => 1)))
					{
						if($this->Tinnhan->Chitiettinnhan->find('count', 
							array('conditions' => array('tinnhan_id' => $v, 'Chitiettinnhan.mark_deleted' => 0))) == 0
						&&  $this->Tinnhan->find('count', 
							array('conditions' => array('Tinnhan.id' => $v, 'Tinnhan.mark_deleted' => 0))) == 0)
						
						$this->Tinnhan->delete($v);	// nếu tất cả người gửi và nhận đều đánh dấu deleted thì xóa
						$success++;
					}
				}
				
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('tinnhan/read');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('tinnhan/read');
	}
	
	public	function	sent_delete()
	{
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền xóa tin nhắn. Vui lòng liên hệ quản trị để cấp quyền.');	
			
		$this->layout = null;
		if(!empty($this->request->data))
		{
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach( $ids as $k => $v )
			{
				//if($this->Tinnhan->delete($v))	$success++;
				if(!empty($v) && $this->Tinnhan->updateAll(
						array('mark_deleted' => 1), 
						array('Tinnhan.id' => $v, 'nguoigui_id' => $this->Auth->user('nhanvien_id')
						)))
				{
					if($this->Tinnhan->Chitiettinnhan->find('count', 
							array('conditions' => array('tinnhan_id' => $v, 'Chitiettinnhan.mark_deleted' => 0))) == 0
						&&  $this->Tinnhan->find('count', 
							array('conditions' => array('Tinnhan.id' => $v, 'Tinnhan.mark_deleted' => 0))) == 0)
						
						$this->Tinnhan->delete($v);
					$success++;
				}
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect('tinnhan/sent');
			}
		}
		
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect('tinnhan/sent');
	}
	
	function	view($id = null)
	{
		if(!$this->check_permission('TinNhan.danhsach'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		$this->loadModel('Chitiettinnhan');
		
		$this->Chitiettinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Tinnhan'	=>	array('className'	=>	'Tinnhan')
					)), false);
		
		
		$this->Chitiettinnhan->recursive = 2;
		$data = $this->Chitiettinnhan->find('first', array('conditions' => array('Tinnhan.id' => $id, 'Chitiettinnhan.nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'Chitiettinnhan.mark_deleted' => 0)));
		
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy tin nhắn này. Vui lòng thử lại.');
		//pr($data);die();
		
		$this->Tinnhan->query("UPDATE tinnhan_nguoinhan SET ngay_nhan='" . date('Y-m-d H:i:s', time()) . "' WHERE id=" . $data['Chitiettinnhan']['id'] . " AND ngay_nhan IS NULL");	
		//if($this->Auth->user('nhanvien_id') == 342)
		//{
		//Lưu log view(GĐ)
		//Configure::write('debug',2);
		$t = array();
		$t['id'] = NULL;
		$t['vanban_id'] = NULL;
		$t['tinnhan_id'] = $id;
		$t['action'] = 'view tin nhắn';
		$t['date'] = date('Y-m-d H:i:s', time());
		$t['user_name'] = $this->Auth->user('username');
		//pr($t);die();
		$this->loadModel('Logview');
		if(!$this->Logview->save($t))
			{
				break;
 			}
		
		//}
		/*
		$this->Chitiettinnhan->id = $data['Chitiettinnhan']['id'];
		$this->Chitiettinnhan->saveField('ngay_nhan', date("Y-m-d H:i:s"));
		*/
		$this->data = $data;
		
		$ds_nguoinhan = $this->Tinnhan->Chitiettinnhan->find('count',array('conditions' =>
									array('tinnhan_id' => $id), 'recursive' => -1, 'fields' => 'id'
									));
											
		//pr($ds_nguoinhan);die();					
		//$sl_nguoinhan = count($ds_nguoinhan);
		//pr($sl_nguoinhan);die();					
		$this->set('ds_nguoinhan',$ds_nguoinhan);			

		$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')), false);
		
		$this->Tinnhan->bindModel(
			array(
					'hasOne' => array(
							'Chitiettinnhan'	=>	array(
														'foreignKey'	=>	'tinnhan_id',
									  		  	     	'className'		=>	'Chitiettinnhan', 'limit' => 1)
							)
				), false);
		if(!empty($data['Tinnhan']['related_id']))
		{
			//$tn = $this->Tinnhan->Chitiettinnhan->find('list', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'mark_deleted' => 0), 'fields' => array('tinnhan_id')));
			//pr($tn); die();
			$related_messages = $this->Tinnhan->find('all', 
				array(
				'conditions' => array(
					'related_id' 			=> $data['Tinnhan']['related_id'], 
					'Tinnhan.mark_deleted' 	=> 0, 
					'Tinnhan.id <' 			=> $data['Tinnhan']['id'], 
					'OR'	=>	array(
								'nguoigui_id'	=>	$this->Auth->user('nhanvien_id'),
								'nguoinhan_id'	=>	$this->Auth->user('nhanvien_id')
								//'Tinnhan.id'	=>	$tn
							)
						),
				'order' => 'Tinnhan.id DESC'));
			//pr($related_messages); die();	
			$root = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $data['Tinnhan']['related_id'], 'Tinnhan.mark_deleted' => 0)));
			if(!empty($root))
				array_push($related_messages, $root);
		}else
		{
			
			$related_messages = $this->Tinnhan->find('all', 
				array(
					'conditions' => array(
								'related_id' 			=> $data['Tinnhan']['id'], 
								'OR'		=>	array(
										'nguoigui_id'	=>	$this->Auth->user('nhanvien_id'),
										'nguoinhan_id'	=>	$this->Auth->user('nhanvien_id')
												),
								'Tinnhan.mark_deleted' 	=> 0), 
					'order' => 'Tinnhan.id DESC'));
		}
		
		//pr($related_messages); die();
		$this->set('related_messages', $related_messages);
		
		$this->set('title_for_layout', 'Xem chi tiết Tin nhắn');
	}
	
	function	view_sent($id = null)
	{
		if(!$this->check_permission('TinNhan.danhsach'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		$this->Tinnhan->recursive = 2;
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
					)), false);
			
		$data = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $id, 'Tinnhan.nguoigui_id' => $this->Auth->user('nhanvien_id'), 'Tinnhan.mark_deleted' => 0)));
		
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy tin nhắn này. Vui lòng thử lại.');
		$this->data = $data;
		
		$this->set('title_for_layout', 'Xem chi tiết Tin nhắn đã gửi');
	}
	
	
	public	function	attachfile()
	{
		$this->loadModel('FileManager');
		$path = str_replace("/", DS, Configure::read('File.tmp'));
		$path = substr($path, 1, strlen($path)-1);
		$filename = $this->request->data['FileManager']['file']['name'];
		$ten_cu = $filename;
		$ten_moi = $this->FileManager->upload_tinnhan($this->request->data, $path);
		if($ten_moi !== false) 
		{
			App::uses('File', 'Utility');
			App::uses('CakeNumber', 'Utility');
			//$file = new File(APP . WEBROOT_DIR . DS . $path . $filename);
			$file = new File(APP . WEBROOT_DIR . DS . $path . $ten_moi);
			$file_info = $file->info();
			die(json_encode(array('success' => true, 'filename' => $filename, 'ten_cu' => $ten_cu, 'ten_moi' => $ten_moi, 'filesize' => CakeNumber::toReadableSize($file_info['filesize']))));
		}
		else
			die(json_encode(array('success' => false, 'message' => $this->FileManager->error)));
	}
	
	public	function	remove_attach()	// xem lại cái này
	{
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền xóa file đính kèm. Vui lòng liên hệ quản trị để cấp quyền.');	
			
		if(!empty($this->request->data['key']))
		{
			$path = str_replace("/", DS, Configure::read('File.tmp'));
			$path = WWW_ROOT . substr($path, 1, strlen($path)-1);
			
			@unlink($path . $this->request->data['key']);	
			die(json_encode(array('success' => true, 'id' => $this->request->data['key'])));
		}
		die(json_encode(array('success' => false)));
	}
	
	public	function	view_nguoinhan($tinnhan_id)
	{
		$this->Tinnhan->recursive = 2;
		$data = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $tinnhan_id)));		
		$nguoinhan = array();
		foreach($data['Chitiettinnhan'] as $it){
			//if($it['loai_nguoinhan']!=3){
				array_push($nguoinhan,$it); 
			//}
		}
		$this->set(compact('nguoinhan'));			
		//$this->set('nguoinhan', $data['Chitiettinnhan']);
		//pr($data); die();
	}
	
	
	public	function	mobile_index()
	{
		$this->set('title_for_layout', 'BIN+ Mobile - Tin nhắn');
		
		$this->loadModel('Chitiettinnhan');
		$chuadoc = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_nhan' => NULL, 'Chitiettinnhan.mark_deleted' => 0)));
		
		$tatca = $this->Chitiettinnhan->find('count', array('conditions' => array('nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_nhan <>' => NULL, 'Chitiettinnhan.mark_deleted' => 0)));
		
		$dagui = $this->Tinnhan->find('count', array('conditions' => array('nguoigui_id' => $this->Auth->user('nhanvien_id'), 'Tinnhan.mark_deleted' => 0)));
				
		$this->set(compact('chuadoc', 'tatca', 'dagui'));	
	}
	
	
	public	function	mobile_chuadoc()
	{
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
						),
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  			'className'	=>	'Chitiettinnhan')
						)
					), false); 
		
		$conds = array();
		
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				$conds	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
									&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoigui_id']))
			{
				$conds['Tinnhan.nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
				$this->passedArgs['nguoigui_id'] = $this->data['Tinnhan']['nguoigui_id'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoigui_id']))
				$this->passedArgs['nguoigui_id'] = $this->passedArgs['nguoigui_id'];
		}
		
		$conds['Chitiettinnhan.nguoinhan_id']	= $this->Auth->user('nhanvien_id');
		$conds['Chitiettinnhan.ngay_nhan'] 	= NULL;
		$conds['Chitiettinnhan.mark_deleted'] 	= 0;
		
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Tinnhan', $conds);
		
		$this->set('ds', $ds);
	}
	
	
	function	mobile_view($id = null)
	{
		$this->loadModel('Chitiettinnhan');
		
		$this->Chitiettinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Tinnhan'	=>	array('className'	=>	'Tinnhan')
					)), false);
		
		$this->Chitiettinnhan->recursive = 2;
		$data = $this->Chitiettinnhan->find('first', array('conditions' => array('Tinnhan.id' => $id, 'Chitiettinnhan.nguoinhan_id' => $this->Auth->user('nhanvien_id'), 'Chitiettinnhan.mark_deleted' => 0)));
		
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy tin nhắn này. Vui lòng thử lại.');
		
		$this->Tinnhan->query('UPDATE tinnhan_nguoinhan SET ngay_nhan=NOW() WHERE id=' . $data['Chitiettinnhan']['id'] . ' AND ngay_nhan IS NULL');	
		/*
		$this->Chitiettinnhan->id = $data['Chitiettinnhan']['id'];
		$this->Chitiettinnhan->saveField('ngay_nhan', date("Y-m-d H:i:s"));
		*/
		$files = array();
		if(!empty($data['Tinnhan']['FileTinnhan']))
			foreach($data['Tinnhan']['FileTinnhan'] as $file)
				array_push($files,  $file['file_path']);
		App::import('Helper', 'Time');
		$time = new TimeHelper();
		$message = array(
			'id'		=>	$data['Tinnhan']['id'],
			'tieu_de'	=>	$data['Tinnhan']['tieu_de'],
			'noi_dung'	=>	$data['Tinnhan']['noi_dung'],
			'nguoi_gui'	=>	$data['Tinnhan']['Nguoigui']['full_name'],
			'ngay_gui'	=>	$time->format('d-m-Y H:i:s', $data['Tinnhan']['ngay_gui']),
			'file_path'	=>	Configure::read('TinNhan.attach_path'),
			'files'		=>	$files
		);
		
		die(json_encode($message));
	}
	
	public	function	mobile_tatca()
	{
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
						),
			'hasOne'		=>	array(
					'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
										  'className'	=>	'Chitiettinnhan')
						)
					), false); 
		$conds = array("NOT"	=>	array('Chitiettinnhan.ngay_nhan' => NULL),
					   "Chitiettinnhan.nguoinhan_id"	=>	$this->Auth->user('nhanvien_id'),
					   'Chitiettinnhan.mark_deleted'	=>	0);
		
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				$t	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
				&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoigui_id']))
			{
				$conds['Tinnhan.nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
				$this->passedArgs['nguoigui_id'] = $this->request->data['Tinnhan']['nguoigui_id'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
			{
				$t	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoigui_id']))
				$this->passedArgs['nguoigui_id'] = $this->passedArgs['nguoigui_id'];
		}
		
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Tinnhan', $conds);
		
		$this->set('ds', $ds);
	}
	
	public	function	mobile_dagui()
	{
		// default
		$this->Tinnhan->bindModel(array(
			'hasMany'		=>	array(
					'Chitiettinnhan'	=>	array(
												'foreignKey'	=>	'tinnhan_id',
										  		'className'		=>	'Chitiettinnhan')
								)
					), false); 
					
		$this->Tinnhan->recursive = 2;
		$conds = array("Tinnhan.nguoigui_id"	=>	$this->Auth->user('nhanvien_id'),
						'Tinnhan.mark_deleted'	=>	0);
						
		
		if(!empty($this->request->data))	// if search
		{
			if(!empty($this->request->data['Tinnhan']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Tinnhan']['keyword'];
				
				$t	= array(
					"OR"	=>	array(
					  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%",
					  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->request->data['Tinnhan']['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			
			if(!empty($this->request->data['Tinnhan']['tu_ngay']) 
				&& !empty($this->request->data['Tinnhan']['den_ngay']))
			{
				$tu_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['tu_ngay']);
				$den_ngay = $this->Bin->vn2sql($this->request->data['Tinnhan']['den_ngay']);
				
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"]  = array( $tu_ngay,  $den_ngay);
				
				$this->passedArgs['tu_ngay'] = $tu_ngay;
				$this->passedArgs['den_ngay'] = $den_ngay;
			}
			
			if(!empty($this->request->data['Tinnhan']['nguoinhan_id']))
			{
				$conds['Chitiettinnhan.nguoinhan_id'] = $this->request->data['Tinnhan']['nguoinhan_id'];
				$this->passedArgs['nguoinhan_id'] = $this->request->data['Tinnhan']['nguoinhan_id'];
				$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')), false);
				$this->Tinnhan->bindModel(array(
					'hasOne'	=>	array(
							'Chitiettinnhan'	=>	array(
												'foreignKey'=>	'tinnhan_id',
												'className'	=>	'Chitiettinnhan')
								)
							), false); 
			}
		}elseif(isset($this->passedArgs))	// phân trang của search
		{
			if(!empty($this->passedArgs['keyword']))
			{
				$t	= array(
					"OR"	=>	array(
						  "Tinnhan.tieu_de LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
						  "Tinnhan.noi_dung LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
									  )
					);
				array_push($conds, $t);
			}
			if(!empty($this->passedArgs['tu_ngay']) && !empty($this->passedArgs['den_ngay']))
				$conds["DATE_FORMAT(Tinnhan.ngay_gui, '%Y-%m-%d') BETWEEN ? AND ?"] = array( $this->passedArgs['tu_ngay'],  $this->passedArgs['den_ngay']);
			if(!empty($this->passedArgs['nguoinhan_id']))
			{
				$conds['Chitiettinnhan.nguoinhan_id'] = $this->passedArgs['nguoinhan_id'];
				$this->Tinnhan->unbindModel(array('hasMany' => array('Chitiettinnhan')), false);
				$this->Tinnhan->bindModel(array(
					'hasOne'	=>	array(
							'Chitiettinnhan'	=>	array(
												'foreignKey'=>	'tinnhan_id',
												'className'	=>	'Chitiettinnhan')
								)
							), false); 
			}
		}
		
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Tinnhan', $conds);
		$this->set('ds', $ds);
	}
	
	
	public	function	mobile_compose()
	{
		if (!empty($this->request->data)) 
		{
			$this->request->data['Tinnhan']['nguoigui_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Tinnhan']['ngay_gui'] = date("Y-m-d H:i:s");
			
			//pr($this->request->data); die();
			$dataSource = $this->Tinnhan->getDataSource();
			$dataSource->begin();	// begin transaction
			
            if ($this->Tinnhan->saveAssociated($this->request->data)) 
			{
				$tinnhan_id = $this->Tinnhan->getLastInsertID();	// new Tinnhan ID
				
				$dataSource->commit();
				die(json_encode(array('success' => true, 'message' => 'Tin nhắn đã gửi thành công')));
				
            } else {	// lỗi khi save data
				
				$dataSource->rollback();
				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi trong khi gửi tin nhắn. Vui lòng thử lại')));
            }
        }else	// show form compose
		{
			
			$this->loadModel('Nhanvien');
			$this->Nhanvien->bindModel(array('belongsTo' 	=> array('Chucdanh')), false); 
			$this->Nhanvien->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen')), false);
			
			$quyen = $this->Session->read('Auth.User.quyen');
			
			if( !$this->check_permission('HeThong.toanquyen')
					&& $quyen['TinNhan.soanthao'] != 0)
			{
				$this->loadModel('Phong');
				$ds = $this->Phong->getList($this->Auth->user('phong_id'));
				
				for($i = 0; $i < $size=count($ds); $i++)
				{
					$nhanvien = $this->Nhanvien->find('all', 
						array(
						'conditions' 	=> array('phong_id' => $ds[$i]['Phong']['id'], 'tinh_trang' => 1),
						'order' 		=> array('nguoi_quanly' => 'DESC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC')
						)
					);
					$ds[$i]['Nhanvien'] = $nhanvien;
				}
				
			}else
				$ds = $this->Nhanvien->listNhanvien();
			
			$tinnhan = null;
			
			if(!empty($this->passedArgs['replyto']))
			{
				//$this->Tinnhan->recursive = -1;
				$this->Tinnhan->bindModel(array(
					'hasOne'		=>	array(
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
												  'className'	=>	'Chitiettinnhan')
								)
							), false); 
				$tinnhan = $this->Tinnhan->find('first', array(
					'conditions' => array(
						'Tinnhan.id' 					=> $this->passedArgs['replyto'],
						'Chitiettinnhan.nguoinhan_id' 	=> $this->Auth->user('nhanvien_id'),
						'Chitiettinnhan.mark_deleted'	=>	0)));
				$this->set('related_id', !empty($tinnhan['Tinnhan']['related_id']) ? $tinnhan['Tinnhan']['related_id'] : $this->passedArgs['replyto']);
			}elseif(!empty($this->passedArgs['sendto']))
			{
				$tinnhan['Tinnhan']['nguoigui_id'] = $this->passedArgs['sendto'];
			} elseif ( !empty( $this->passedArgs['forwardto'] ) )
			{
				$this->Tinnhan->bindModel(array(
					'hasOne'		=>	array(
							'Chitiettinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
												  'className'	=>	'Chitiettinnhan')
								)
							), false); 
				$tinnhan = $this->Tinnhan->find('first', array(
					'conditions' => array(
						'Tinnhan.id' 					=> $this->passedArgs['forwardto'],
						'Chitiettinnhan.nguoinhan_id' 	=> $this->Auth->user('nhanvien_id'),
						'Chitiettinnhan.mark_deleted'	=>	0)));
				//$tinnhan = $this->Tinnhan->read(null, $this->passedArgs['forwardto']);
				unset($tinnhan['Tinnhan']['nguoigui_id']);
			}
			
			//pr($ds); die();
			$signature = $this->Nhanvien->field('signature', array('Nhanvien.id' => $this->Auth->user('nhanvien_id')));
			
			$this->set(compact('ds', 'tinnhan', 'signature'));
		}
	}
	
	
	public	function	mobile_unread_delete($tinnhan_id)
	{
		$data = $this->Tinnhan->Chitiettinnhan->find('first', array('conditions' => array('Chitiettinnhan.tinnhan_id' => $tinnhan_id, 'nguoinhan_id' => $this->Auth->user('nhanvien_id'))));
		
		if(!empty($data) && $this->Tinnhan->Chitiettinnhan->save(array('id' => $data['Chitiettinnhan']['id'], 'mark_deleted' => 1)))
		{
			if($this->Tinnhan->Chitiettinnhan->find('count', 
				array('conditions' => array('tinnhan_id' => $tinnhan_id, 'Chitiettinnhan.mark_deleted' => 0))) == 0
			&&  $this->Tinnhan->find('count', 
				array('conditions' => array('Tinnhan.id' => $tinnhan_id, 'Tinnhan.mark_deleted' => 0))) == 0)
			
			$this->Tinnhan->delete($tinnhan_id);
			die(json_encode(array('success'	=>	true,
									  'message'	=>	'Xóa thành công.')));
		}
		die(json_encode(array('success'	=>	false,
								  'message'	=>	'Xóa bị lỗi.')));
	}
	
	public	function	mobile_sent_delete($tinnhan_id = null)
	{
		$return = array();
		if(!$this->check_permission('TinNhan.soanthao'))
			$return = array(
				'success'	=>	false,
				'message'	=>	'Bạn không có quyền xóa tin nhắn này'
			);
		else if(empty($tinnhan_id))
		{
			$return = array(
				'success'	=>	false,
				'message'	=>	'Không tìm thấy tin nhắn này.'
			);
		}else
		{
			if($this->Tinnhan->updateAll(
						array('mark_deleted' => 1), 
						array('Tinnhan.id' 	=> $tinnhan_id, 'nguoigui_id' => $this->Auth->user('nhanvien_id')
						)))
			{
				if($this->Tinnhan->Chitiettinnhan->find('count', 
							array('conditions' => array('tinnhan_id' => $tinnhan_id, 'Chitiettinnhan.mark_deleted' => 0))) == 0
						&&  $this->Tinnhan->find('count', 
							array('conditions' => array('Tinnhan.id' => $tinnhan_id, 'Tinnhan.mark_deleted' => 0))) == 0)
						
					$this->Tinnhan->delete($tinnhan_id);
				$return = array(
					'success'	=>	true,
					'message'	=>	'Tin nhắn đã được xóa.'
				);
			}else
				$return = array(
					'success'	=>	false,
					'message'	=>	'Đã phát sinh lỗi khi xóa tin nhắn này.'
				);
		}
		
		die(json_encode($return));
			
	}
	
	
	function	mobile_view_sent($id = null)
	{
		if(!$this->check_permission('TinNhan.danhsach'))
			throw new InternalErrorException('Bạn không có quyền xem danh sách tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
			
		$this->Tinnhan->recursive = 2;
		$this->Tinnhan->bindModel(array(
			'belongsTo' 	=> array(
					'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
										  'foreignKey'	=>	'nguoigui_id',
										  'fields'		=>	array('full_name'))
					)), false);
			
		$data = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $id, 'Tinnhan.nguoigui_id' => $this->Auth->user('nhanvien_id'), 'Tinnhan.mark_deleted' => 0)));
		
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy tin nhắn này. Vui lòng thử lại.');
		
		$files = array();
		if(!empty($data['Tinnhan']['FileTinnhan']))
			foreach($data['Tinnhan']['FileTinnhan'] as $file)
				array_push($files,  $file['file_path']);
		$nguoinhan = array();
		if(!empty($data['Chitiettinnhan']))
			foreach($data['Chitiettinnhan'] as $n)
				array_push($nguoinhan,  $n['Nguoinhan']['full_name']);
		App::import('Helper', 'Time');
		$time = new TimeHelper();
		$message = array(
			'id'		=>	$data['Tinnhan']['id'],
			'tieu_de'	=>	$data['Tinnhan']['tieu_de'],
			'noi_dung'	=>	$data['Tinnhan']['noi_dung'],
			'ngay_gui'	=>	$time->format('d-m-Y H:i:s', $data['Tinnhan']['ngay_gui']),
			'file_path'	=>	Configure::read('TinNhan.attach_path'),
			'files'		=>	$files,
			'nguoinhan'	=>	$nguoinhan
		);
		
		die(json_encode($message));
	}
	
	/**************** Hà thê *************************************/
	public	function	autocomplete($type)
	{
		$this->layout = null;
		$ret = array(
			'total'	=>	0,
			'results'	=>	array()
		);
		$q = $this->request->query['q'];
		if(!empty($q))
		{
			$ret['total'] = $this->Vanban->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Vanban->find('count', array('conditions' => array("$type LIKE" => $q . '%'), 'order' => array($type => 'ASC'), 'fields' => "DISTINCT($type)", 'recursive' => -1));
			$data = $this->Vanban->find('all', array(
				'conditions' => array(
					"$type LIKE" => $q . '%'
					), 
				'fields'	=>	array("DISTINCT($type)"),
				'order'	=>	array($type => 'ASC'),
				'limit' => $this->request->query['page_limit'], 
				'page' => $this->request->query['page']
			));

			if(!empty($data))
				foreach($data as $item)
					array_push($ret['results'], array('id' => $item['Vanban']["$type"], 'text' => $item['Vanban']["$type"]));
		}

		die(json_encode($ret));

	}
	
	public	function	edit($tinnhan_id= null)
	{
		
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền soạn thảo tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->set('title_for_layout', 'Soạn thảo Tin nhắn');
		if (!empty($this->request->data)) 
		{
			
			App::uses('Sanitize', 'Utility');
			//$this->request->data['Tinnhan']['tieu_de'] = $this->request->data['Tinnhan']['tieu_de'];			
			$this->request->data['Tinnhan']['nguoigui_id'] = $this->Auth->user('nhanvien_id');
			//$this->request->data['Tinnhan']['ngay_gui'] = $this->request->data['Tinnhan']['ngay_gui'];
			$this->request->data['Tinnhan']['ngay_capnhat'] = date("Y-m-d H:i:s");
			$this->request->data['Tinnhan']['chuyen_tiep'] = isset($this->request->data['Tinnhan']['chuyen_tiep']) ? 0 : 1;
			
			
			
			$dataSource = $this->Tinnhan->getDataSource();
			$dataSource->begin();	// begin transaction
			
			$nguoinhan = explode(",", $this->request->data['Tinnhan']['nv_selected']);
				
			$this->request->data['Chitiettinnhan'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Chitiettinnhan'], array('nguoinhan_id' => $n));
				}
			}else
			{
				$this->Session->setFlash('Vui lòng chọn người nhận.', 'flash_error');
				$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
			}
			//pr($this->request->data); die();
			if ($this->Tinnhan->saveAssociated($this->request->data)) 
			{
				$tinnhan_id = $this->Tinnhan->getLastInsertID();	// new Tinnhan ID
				
				if(!empty($this->request->data['File']))	// attach file
				{
					$attach_path = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
					$attach_path = substr($attach_path, 1, strlen($attach_path)-1);
					
					$tmp_path = str_replace("/", DS, Configure::read('File.tmp'));
					$tmp_path = substr($tmp_path, 1, strlen($tmp_path)-1);
					
					$newfile = '';
					
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
							$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
						}
						
						if(!$this->Tinnhan->FileTinnhan->save($file))
						{
							unlink(WWW_ROOT . $attach_path . $v['ten_moi']);
							$dataSource->rollback();
							$this->Session->setFlash('Đã phát sinh lỗi đính kèm file. Vui lòng thử lại.', 'flash_error');
							$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
						}
					}	// end for
				}	// end of if
				
				$dataSource->commit();
				$this->Session->setFlash('Tin nhắn đã cập nhật thành công.', 'flash_success');
				$this->redirect('/tinnhan/index/#message-sent');	
				
            } else {	// lỗi khi save data
				
				$dataSource->rollback();
				$this->Session->setFlash('Đã phát sinh lỗi trong khi cập nhật tin nhắn. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/tinnhan/view_sent/'.$tinnhan_id);	
            }
        }else	// show form compose
		{
			$this->loadModel('Nhanvien');
			$nguoi_gui = $this->Auth->user('nhanvien_id');	
			
			$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $tinnhan_id, 'Tinnhan.nguoigui_id' => $this->Auth->user('nhanvien_id')), 'recursive' => 1));
			
			$signature = $this->Nhanvien->field('signature', array('Nhanvien.id' => $this->Auth->user('nhanvien_id')));
		//	pr($tinnhan); die();
			$this->set(compact('tinnhan', 'signature','nguoi_gui'));
		}
	}	
	
	public	function	edit_dachay_themtruongmoi($tinnhan_id=Null)
	{
		
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền soạn thảo tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->set('title_for_layout', 'Soạn thảo Tin nhắn');
		if (!empty($this->request->data)) 
		{
			App::uses('Sanitize', 'Utility');
			$this->request->data['Tinnhan']['nguoigui_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Tinnhan']['ngay_gui'] = date("Y-m-d H:i:s");
			$this->request->data['Tinnhan']['chuyen_tiep'] = isset($this->request->data['Tinnhan']['chuyen_tiep']) ? 0 : 1;
							
			$dataSource = $this->Tinnhan->getDataSource();
			$dataSource->begin();	// begin transaction
			
			$nguoinhan = explode(",", $this->request->data['Tinnhan']['nv_selected']);
				
			$this->request->data['Chitiettinnhan'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Chitiettinnhan'], array('nguoinhan_id' => $n));
				}
			}else
			{
				$this->Session->setFlash('Vui lòng chọn người nhận.', 'flash_error');
				$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
			}
			pr($this->request->data); die();
			$this->Tinnhan->id=$tinnhan_id;
			if ($this->Tinnhan->saveall($this->request->data)) 
			{
				//$tinnhan_id = $this->Tinnhan->getLastInsertID();	// new Tinnhan ID
				
				if(!empty($this->request->data['File']))	// attach file
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
							$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
						}

						if(!$this->Tinnhan->FileTinnhan->save($file))
						{
							unlink(WWW_ROOT . $attach_path . $v['ten_moi']);
							$dataSource->rollback();
							$this->Session->setFlash('Đã phát sinh lỗi đính kèm file. Vui lòng thử lại.', 'flash_error');
							$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
						}
					}	// end for
				}	// end of if
				
				$dataSource->commit();
				$this->Session->setFlash('Tin nhắn đã cập nhật thành công.', 'flash_success');
				$this->redirect('/tinnhan/view_sent/'.$tinnhan_id);	
				
            } else {	// lỗi khi save data
				
				$dataSource->rollback();
				$this->Session->setFlash('Đã phát sinh lỗi trong khi cập nhật tin nhắn. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/tinnhan/view_sent/'.$tinnhan_id);	
            }
        }else	// show form compose
		{
			$this->loadModel('Nhanvien');
			$nguoi_gui = $this->Auth->user('nhanvien_id');	
			
			$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $tinnhan_id, 'Tinnhan.nguoigui_id' => $this->Auth->user('nhanvien_id')), 'recursive' => 1));
			
			$signature = $this->Nhanvien->field('signature', array('Nhanvien.id' => $this->Auth->user('nhanvien_id')));
			
			$this->set(compact('tinnhan', 'signature','nguoi_gui'));
		}
	}

	public	function	edit_dachay_kothemtruong($tinnhan_id=Null)
	{
		/*$this->Tinnhan->unbindModel(array(
			'hasMany' 	=> array('FileTinnhan', 'Chitiettinnhan'))); */
		
		if(!$this->check_permission('TinNhan.soanthao'))
			throw new InternalErrorException('Bạn không có quyền soạn thảo tin nhắn. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
		$this->set('title_for_layout', 'Cập nhật Tin nhắn');
		
		if (!empty($this->request->data)) 
		{
			//pr($this->request->data); die();
			
			App::uses('Sanitize', 'Utility');			
			$this->request->data['Tinnhan']['nguoigui_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Tinnhan']['ngay_gui'] = date("Y-m-d H:i:s");
			$this->request->data['Tinnhan']['chuyen_tiep'] = isset($this->request->data['Tinnhan']['chuyen_tiep']) ? 0 : 1;
			
			$dataSource = $this->Tinnhan->getDataSource();			
			$dataSource->begin();	// begin transaction
			//pr($this->request->data['Tinnhan']);// die();
					
			if ($this->Tinnhan->updateAll(
						array(
						'tieu_de'=>"'".$this->request->data['Tinnhan']['tieu_de']."'",
						'noi_dung'=>"'".$this->request->data['Tinnhan']['noi_dung']."'",
						'nguoigui_id'=>$this->request->data['Tinnhan']['nguoigui_id'],
						'ngay_gui'=>"'".$this->request->data['Tinnhan']['ngay_gui']."'",
						'chuyen_tiep'=>$this->request->data['Tinnhan']['chuyen_tiep']
						),
						array('Tinnhan.id'=>$tinnhan_id)
						)
				) 
			{
				/****************Update người nhận************************/
				$dsnhan=array();
				$nguoinhan = explode(",", $this->request->data['Tinnhan']['nv_selected']);				
				$this->request->data['Chitiettinnhan'] = array();
				if(!empty($nguoinhan))
				{
					foreach($nguoinhan as $n)
					{
						array_push($dsnhan, array('nguoinhan_id' => $n));
					}
				}else
				{
					$this->Session->setFlash('Vui lòng chọn người nhận.', 'flash_error');
					$this->redirect('/tinnhan/edit/'.$tinnhan_id);	
				}
				
				$f = true;
				$this->loadModel('Chitiettinnhan');
				$old = $this->Chitiettinnhan->find('list', array('conditions' => 'tinnhan_id=' . $tinnhan_id, 'fields' => array('nguoinhan_id')));
				$del = array_diff($old, $dsnhan);
				$ins = array_diff($dsnhan, $old);
				//pr($ins); die();
				//insert
				if($f)
					foreach($ins as $k => $v)
					{
						$t['id'] = NULL;
						$t['tinnhan_id'] = $tinnhan_id;
						$t['nguoinhan_id'] = $v['nguoinhan_id'];						
						if(!$this->Chitiettinnhan->save($t))
						{
							$f = false;	break;
						}
					}
				//delete
				if($f)
					foreach($del as $k=>$v)
					{
						if(!$this->Chitiettinnhan->delete($k))
						{
							$f = false;	break;
						}
					}				
				/************* End update người nhận **********************/
				/***************** Update file kèm *******************************/
				$this->loadModel('Filetinnhan');
				$old = $this->Filetinnhan->find('list', array('conditions' => 'tinnhan_id=' . $tinnhan_id, 'fields' => array('id')));				
				//pr($old);die();

				$new = $this->request->data['File'];
				if(!empty($new))
					$del = array_diff_key($old, $new);
				else
					$del = $old;
				//pr($del); die();
				if(!empty($new))
					$ins = array_diff_key($new, $old);
				else
					$ins = null;
				//pr($ins); die();
				if($f)
				{
					foreach($del as $k => $v)
					{
						//pr($v);die();
						if(!$this->Filetinnhan->delete($v))
						{
							$f = false;	break;
						}
					}
				}
				//insert
				
				if($f)
				{
					// vanban files
					$old = str_replace("/", DS, Configure::read('File.tmp'));
					$old = substr($old, 1, strlen($old)-1);
					$new =str_replace("/", DS, Configure::read('TinNhan.attach_path'));
					$new = substr($new, 1, strlen($new)-1);
					if(!empty($ins))
						foreach($ins as $k => $v)
						{						

							$t['id'] = NULL;
							$t['tinnhan_id'] = $tinnhan_id;
							$t['file_path'] = $v['file_path'];
							$t['ten_cu'] = $v['ten_cu'];
							$t['ten_moi']= $v['ten_moi'];
							//pr($t); die();
							if(!$this->Filetinnhan->save($t))
							{
								$f = false;	break;
							}
							if(copy(WWW_ROOT . $old . $v['ten_moi'],  WWW_ROOT . $new .$v['ten_moi']))
								unlink(WWW_ROOT . $old . $v['ten_moi']);
						}
				}
				/************* End Update file kèm ************************/
				
				$dataSource->commit();
				$this->Session->setFlash('Tin nhắn đã cập nhật thành công.', 'flash_success');
				$this->redirect('/tinnhan/view_sent/'.$tinnhan_id);
				
            } else {	// lỗi khi save data
				
				$dataSource->rollback();
				$this->Session->setFlash('Đã phát sinh lỗi trong khi gửi tin nhắn. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/tinnhan/edit/'.$tinnhan_id);
            }
        }else	// show form compose
		{
			$this->loadModel('Nhanvien');
			$nguoi_gui = $this->Auth->user('nhanvien_id');	
			
			$tinnhan = $this->Tinnhan->find('first', array('conditions' => array('Tinnhan.id' => $tinnhan_id, 'Tinnhan.nguoigui_id' => $this->Auth->user('nhanvien_id')), 'recursive' => 1));
			
			$signature = $this->Nhanvien->field('signature', array('Nhanvien.id' => $this->Auth->user('nhanvien_id')));
			
			$this->set(compact('tinnhan', 'signature','nguoi_gui'));
		}
	}	
}