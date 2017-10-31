<?php
/**
 * Baocao controller
 *
 * controller dành cho đối tượng văn bản
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
class BaocaoController extends AppController {
	protected $paginate = array(
        'limit' => 	20,
		'order'	=>	'Vanban.id DESC'
        );

	public $helpers = array('Xls', 'Bin');

	public $components = array(
		'Bin', 'PhpExcel'
    );

	public function beforeFilter() 
	{
		parent::beforeFilter();
		$this->Auth->allow('getfile_ggd');
	}	
	/*public function csl($page = 1)
	{
		$this->loadModel('Filevanban');
		//20140724143707_1270_14.tif
		$ds = $this->Filevanban->find('all', array('page' => $page, 'limit' => '500', 'order' => 'id ASC '));
		//pr($ds);die();
		foreach($ds as $file)
		{
			$ten_moi = md5(time().$file['Filevanban']['path']);
			//pr($ten_moi);die();
			if($this->Filevanban->save(array(
										'id' 		=> $file['Filevanban']['id'],
										'ten_cu' 	=> $file['Filevanban']['path'],
										'ten_moi' 	=> $ten_moi
									)))
			{
				//pr($ten_moi);die();
				$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
				$path = substr($path, 1, strlen($path)-1);
				$path = WWW_ROOT . $path;
				//echo $path . $file['Filevanban']['path']. "<br>";
				//pr($path.$file['Filevanban']['path']);die();
					@rename($path . $file['Filevanban']['path'], $path . $ten_moi);
			}
			else
				$this->Session->setFlash('Chuyển số liệu bị lỗi.', 'flash_attention');
		}
		$count = $this->Filevanban->find('count', array('conditions' => array('not'=>array('ten_cu' => null))));
		pr($count);die();	
	}*/

	public function 	index()
	{
		if(!$this->check_permission('VanBan.nhan'))
			throw new InternalErrorException('Bạn không có quyền nhận văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Danh sách văn bản');
	}
	public	function	unread()

	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
//		pr($this->Nhanvanban); die();
		$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Nhanvanban.ngay_xem' 		=> NULL);
//		array_push($conds, 'Nhanvanban.ngay_xem IS NULL');
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
					&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
					  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
					  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
						&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc'] );
			}
		}
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	vtdn_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		//$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'));
		$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'loaivanban_id' => 9);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		$ds = array();
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	vpth_all()//nay là phòng Tổng hợp - Hành chính
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.phong_id' => 20);
		
		if($this->Auth->User('phong_id') == 20 && ($this->Auth->User('nhanvien_id') <> 683)) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.phong_id' => 20);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('phong_id') == 20 && ($this->Auth->User('nhanvien_id') <> 683)) // người gửi
			$ds =  $this->paginate('Vanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	tcld_all()// hiện tại là phòng nhân sự
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.phong_id' => 34);
		
		if($this->Auth->User('phong_id') == 34 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.phong_id' => 34);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('phong_id') == 34 )
		{
			$ds =  $this->paginate('Vanban', $conds);
			//pr($ds);die();
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	khkd_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		//$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'));
		$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'phong_id' => 35);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	dt_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.phong_id' => 36);
		
		if($this->Auth->User('phong_id') == 36 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.phong_id' => 36);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('phong_id') == 36 )
		{
			$ds =  $this->paginate('Vanban', $conds);
			//pr($ds);die();
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	mdv_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.phong_id' => 37);
		
		if($this->Auth->User('phong_id') == 37 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.phong_id' => 37);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('phong_id') == 37 )
		{
			$ds =  $this->paginate('Vanban', $conds);
			//pr($ds);die();
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	tckt_all()//nay là phòng kế hoạch kế toán
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.phong_id' => 38);
		
		if($this->Auth->User('phong_id') == 38 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.phong_id' => 38);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('phong_id') == 38 )
		{
			$ds =  $this->paginate('Vanban', $conds);
			//pr($ds);die();
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttcntt_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 16);
		
		if($this->Auth->User('donvi_id') == 16) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 16);
							
		/*//pr($this->Auth->User('chucdanh_id'));die();
		if($this->Auth->User('donvi_id') == '') // GĐ VTĐN, CVP
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'donvi_id' => 16);
		if($this->Auth->User('donvi_id') == 16 && $this->Auth->User('chucdanh_id') == 30) // GĐ TTCNTT
			$conds = array('Vanban.donvi_id' => 16);
			
		if($this->Auth->User('donvi_id') == 16 && $this->Auth->User('chucdanh_id') <> 30) // người nhập thuộc TTCNTT
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
						'donvi_id' => 16);*/
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		//pr($conds); die();
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 16 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttkd_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 23);
		
		if($this->Auth->User('donvi_id') == 23 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 23);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 23 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttdh_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');								 
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 15);
		
		if($this->Auth->User('donvi_id') == 15 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 15);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 15 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttvt1_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 24);
		
		if($this->Auth->User('donvi_id') == 24 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 24);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 24 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttvt2_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 25);
		
		if($this->Auth->User('donvi_id') == 25 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 25);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 25 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttvt3_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 26);
		
		if($this->Auth->User('donvi_id') == 26 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 26);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 26 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttvt4_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 27);
		
		if($this->Auth->User('donvi_id') == 27 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 27);
		//pr($conds);die();
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 27 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	ttvt5_all()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));										   
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683) // GĐ VTĐN
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->User('nhanvien_id'),
							'Vanban.donvi_id' => 28);
		
		if($this->Auth->User('donvi_id') == 28 ) // người gửi
			$conds = array('Vanban.nguoi_nhap_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.donvi_id' => 28);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683)
		{
			$this->Nhanvanban->recursive = 2;
			$ds =  $this->paginate('Nhanvanban', $conds);
		}
		if($this->Auth->User('donvi_id') == 28 )
		{
			$ds =  $this->paginate('Vanban', $conds);
		}
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di', 'nv'));
	}
	public	function	den()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										  
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));		
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
					  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	di()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));		

		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
 						'Vanban.chieu_di'			=>	0);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}

			if(
				!empty($this->request->data['Vanban']['ngay_batdau'])
				&& !empty($this->request->data['Vanban']['ngay_ketthuc'])){
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$this->set(compact('ds'));
	}

	public	function	noibo()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->loadModel('Vanban');										   
		$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Theodoivanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));		
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	2);	// văn bản nội bộ
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Nhanvanban', $conds);
		
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	vtdn_theodoi()
	{
		$this->set('title_for_layout', 'Danh sách văn bản quan trọng đang được theo dõi');
		$this->loadModel('Theodoivanban');
		$this->Theodoivanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->Theodoivanban->Vanban->bindModel(array(
								'belongsTo'	=>	array('Loaivanban')
										   ), false);								   
		$conds = array( 'Theodoivanban.nguoi_theodoi_id'	=>	$this->Auth->user('nhanvien_id'));
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['ghi_chu']))
			{
				array_push($conds, array("Theodoivanban.ghi_chu LIKE"	=>	"%" . $this->request->data['Vanban']['ghi_chu']));
				$this->passedArgs['ghi_chu'] = $this->data['Vanban']['ghi_chu'];
			}
			if(!empty($this->request->data['Vanban']['ngay_batdau'])
				&& !empty($this->request->data['Vanban']['ngay_ketthuc'])){
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(!empty($this->passedArgs['ghi_chu']))
			{
				array_push($conds, array("Theodoivanban.ghi_chu LIKE"	=>	"%" . $this->passedArgs['ghi_chu']));
			}
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->Theodoivanban->recursive=2;
		$ds =  $this->paginate('Theodoivanban', $conds);

		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		//$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	add()
	{
		if(!$this->check_permission('VanBan.gui'))
			throw new InternalErrorException('Bạn không có quyền gửi văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Nhập mới văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id')
			)
		));
		if (!empty($this->request->data)) 
		{
			$this->request->data['Vanban']['nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Vanban']['ngay_nhap'] = date("Y-m-d H:i:s");
			$this->request->data['Vanban']['ngay_phathanh'] = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_phathanh']);
			$this->request->data['Vanban']['ngay_nhan'] = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_nhan']);
			$this->request->data['Vanban']['ngay_chuyen'] = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_chuyen']);
			$nguoinhan = explode(",", $this->request->data['Vanban']['nv_selected']);
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}
			if($this->request->data['Vanban']['chieu_di'] != 1)	// không phải VB đến
			{
				$this->request->data['Vanban']['so_hieu_den'] = NULL;
				$this->request->data['Vanban']['ngay_gui'] = $this->request->data['Vanban']['ngay_nhan'];
				$this->request->data['Vanban']['ngay_nhan'] = NULL;
			}
			$this->request->data['Vanban']['tinhtrang_duyet'] = 1;
			/*
			if($this->request->data['Vanban']['tinhtrang_duyet'] == 0)
			{
				$this->request->data['Vanban']['nguoi_duyet'] = NULL;
				$this->request->data['Vanban']['noidung_duyet'] = NULL;
			}
			*/
			$this->request->data['Luuvanban'] = array();
			if(!empty($this->request->data['Vanban']['noi_luu']))	
				foreach($this->request->data['Vanban']['noi_luu'] as $k => $v)
				{
					array_push($this->request->data['Luuvanban'], array('phong_id' => $v));
				}
			unset($this->request->data['Vanban']['noi_luu']);
			//pr($this->request->data);die();
			if ($this->Vanban->saveAssociated($this->request->data)) 
			{
				// vanban files
				$old = str_replace("/", DS, Configure::read('File.tmp'));
				$old = substr($old, 1, strlen($old)-1);
				$new = str_replace("/", DS, Configure::read('VanBan.attach_path'));
				$new = substr($new, 1, strlen($new)-1);
				//pr($new);die();
				if(!empty($this->request->data['Filevanban']))
				{
					foreach($this->request->data['Filevanban'] as $item)
					{
						//pr(WWW_ROOT . $new . $item['ten_moi']);die();
						if(copy(WWW_ROOT . $old . $item['ten_moi'],  WWW_ROOT . $new . $item['ten_moi']))
							unlink(WWW_ROOT . $old . $item['ten_moi']);
					}
				}
				//$this->Session->setFlash('Văn bản đã gửi thành công.', 'flash_success');
				//$this->redirect('/vanban/add');
				die(json_encode(array('success' => true)));
				} else {
				//$this->Session->setFlash('Đã phát sinh lỗi trong khi gửi Văn bản. Vui lòng thử lại.', 'flash_error');
				//$this->redirect('/vanban/add');
				die(json_encode(array('success' => false, 'message' => 'Đã phát sinh lỗi trong khi gửi Văn bản. Vui lòng thử lại.')));
            }
        }else
		{
			//$so_hieu_den = $this->_get_max_sohieuden();
			$this->loadModel('Tinhchatvanban');
			//$tinhchat = $this->Tinhchatvanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
			$chieu_di = $this->Vanban->chieu_di;
			$this->loadModel('Loaivanban');
			$loaivanban = $this->Loaivanban->find('list', array('conditions' => array('enabled=1'), 'fields' => array('id', 'ten_loaivanban')));
			$this->loadModel('Phong');
			$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			$this->set(compact('chieu_di', 'loaivanban', 'phong'));
		}
	}

	function	edit($id = null)
	{
		if(!$this->check_permission('VanBan.sua'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Chỉnh sửa văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id'),
			)
		));
		if(!empty($this->request->data))
		{
			$this->request->data['Vanban']['nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
			$this->request->data['Vanban']['ngay_nhap'] = date("Y-m-d H:i:s");
			$this->request->data['Vanban']['ngay_phathanh'] = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_phathanh']);
			$this->request->data['Vanban']['ngay_nhan'] = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_nhan']);
			$nguoinhan = explode(",", $this->request->data['Vanban']['nv_selected']);
			$this->loadModel('Nhanvanban');
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}
			if($this->request->data['Vanban']['chieu_di'] == 0)
			{
				$this->request->data['Vanban']['so_hieu_den'] = NULL;
				$this->request->data['Vanban']['ngay_gui'] = $this->request->data['Vanban']['ngay_nhan'];
				$this->request->data['Vanban']['ngay_nhan'] = NULL;
			}
			$dataSource = $this->Vanban->getDataSource();
			$dataSource->begin();
            if ($this->Vanban->save($this->request->data)) 
			{
				$f = true;
				// save nguoi nhan
				$old = $this->Nhanvanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('nguoi_nhan_id')));
				$del = array_diff($old, $nguoinhan);
				$ins = array_diff($nguoinhan, $old);
				//insert
				if($f)
					foreach($ins as $k => $v)
					{
						$t['id'] = NULL;
						$t['vanban_id'] = $this->request->data['Vanban']['id'];
						$t['nguoi_nhan_id'] = $v;
						if(!$this->Nhanvanban->save($t))
						{
							$f = false;	break;
						}
					}
				//delete
				if($f)
					foreach($del as $k=>$v)
					{
						if(!$this->Nhanvanban->delete($k))
						{
							$f = false;	break;
						}
					}
				$this->loadModel('Luuvanban');
				// phòng lưu gốc
				$old = $this->Luuvanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('phong_id')));
				$new = $this->request->data['Vanban']['noi_luu'];	
				$del = array_diff($old, $new);
				$ins = array_diff($new, $old);
				if($f)
					foreach($ins as $k => $v)
					{
						$t['id'] = NULL;
						$t['vanban_id'] = $this->request->data['Vanban']['id'];
						$t['phong_id'] = $v;
						if(!$this->Luuvanban->save($t))
						{
							$f = false;	break;
						}
					}
				//delete
				if($f)
					foreach($del as $k=>$v)
					{
						if(!$this->Luuvanban->delete($k))
						{
							$f = false;	break;
						}
					}
				$this->loadModel('Filevanban');
				//pr($this->request->data); die();
				$old = $this->Filevanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
				//pr($old);die();
				$new = $this->request->data['Filevanban'];
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
						if(!$this->Filevanban->delete($v))
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
					$new = str_replace("/", DS, Configure::read('VanBan.attach_path'));
					$new = substr($new, 1, strlen($new)-1);
					if(!empty($ins))
						foreach($ins as $k => $v)
						{
							//pr($v); die();
							$t['id'] = NULL;
							$t['vanban_id'] = $this->request->data['Vanban']['id'];
							$t['path'] = $v['path'];
							$t['ten_cu'] = $v['ten_cu'];
							$t['ten_moi']= $v['ten_moi'];
							if(!$this->Filevanban->save($t))
							{
								$f = false;	break;
							}
							if(copy(WWW_ROOT . $old . $v['ten_moi'],  WWW_ROOT . $new .$v['ten_moi']))
								unlink(WWW_ROOT . $old . $v['ten_moi']);
						}
				}
				if($f)
				{
					// reset ngay xem
					//$this->Vanban->query('UPDATE vanban_nhan SET ngay_xem=NULL WHERE vanban_id=' . $this->data['Vanban']['id']);
					$dataSource->commit();
					$this->Session->setFlash('Văn bản đã gửi thành công.', 'flash_success');
					$this->redirect('/vanban/sua');
				}
				else
				{
					$dataSource->rollback();
					$this->Session->setFlash('Đã phát sinh lỗi trong khi gửi Văn bản. Vui lòng thử lại.', 'flash_error');
				}
            } else {
				$this->Session->setFlash('Đã phát sinh lỗi trong khi gửi Văn bản. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/vanban/add');
            }
		}else	// show edit form
		{
			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			if(empty($data))
				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');
			if(!$this->check_permission('VanBan.quanly')
					&& ($this->check_permission('VanBan.sua')
						&& $data['Vanban']['nguoi_nhap_id'] != $this->Auth->user('nhanvien_id'))
						)
				throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản này. Vui lòng thử lại.');	
			$this->loadModel('Phong');
			$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');
			/*$this->loadModel('Tinhchatvanban');
			$tinhchat = $this->Tinhchatvanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
			*/
			$chieu_di = $this->Vanban->chieu_di;
			$this->loadModel('Loaivanban');
			$loaivanban = $this->Loaivanban->find('list', array('conditions' => array('enabled=1'), 'fields' => array('id', 'ten_loaivanban')));
			$this->set(compact('phong', 'chieu_di', 'loaivanban'));
			$this->data = $data;
		}
	}

	public	function	delete()
	{
		if(!$this->check_permission('VanBan.quanly'))
			throw new InternalErrorException('Bạn không có quyền xóa văn bản này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$this->layout = null;
		if(!empty($this->data))
		{
			$success = 0;
			$ids = explode(",", $this->data['v_id']);
			foreach($ids as $k=>$v)
			{
				if($this->Vanban->delete($v))	$success++;
			}
			if($success > 0)
			{
				$this->Session->setFlash('Đã xóa thành công ' . $success . ' mục.', 'flash_success');
				$this->redirect("/vanban/search");
			}
		}
		$this->Session->setFlash('Đã phát sinh lỗi khi xóa dữ liệu.', 'flash_error');
		$this->redirect("/vanban/search");
	}

	public	function	view($id = null)
	{
		$nv = $this->Auth->user('nhanvien_id');
		//pr($nv);die();
		$this->loadModel('Nhanvanban');
		$check = false;
		if(!$this->check_permission('VanBan.quanly'))	// nếu ko có quyền quản lý văn bản
		{
			if($this->check_permission('VanBan.sua'))	// nếu có quyền sửa văn bản đã gửi
			{
				$check = $this->Vanban->find('first', 
					array('conditions' => array('Vanban.id' => $id, 'Vanban.nguoi_nhap_id' => $this->Auth->user('nhanvien_id')),
					  'fields' => 'Vanban.id'));	//
			}
			if(!$check && $this->check_permission('VanBan.doc'))	// chỉ đọc văn bản gửi cho mình
				$check = $this->Nhanvanban->find('first', 
					array('conditions' => array('Nhanvanban.vanban_id' => $id, 'Nhanvanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id')),
					  'fields' => 'Nhanvanban.id'));
		}else
			$check = true;
		if(!$check)
			throw new InternalErrorException('Bạn không được phép xem văn bản này. Vui lòng chọn văn bản khác.');
		$this->Vanban->bindModel(array(
			'belongsTo'	=>	array(
				'Loaivanban', 'Tinhchatvanban'
			),
			'hasOne'	=>	array('Theodoivanban' => array('foreignKey' => 'vanban_id')),
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Xulyvanban'	=>	array('foreignKey'	=>	'vanban_id'),
			)
		));
		$this->Vanban->recursive = 2;	
		$data = $this->Vanban->read(null, $id);
		//pr($data);die();
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng chọn văn bản khác.');
		$phong_nhan = $this->Nhanvanban->query("SELECT DISTINCT Phong.ten_phong as ten_phong FROM vanban_nhan A, nhansu_nhanvien B, nhansu_phong Phong WHERE A.nguoi_nhan_id = B.id AND B.phong_id = Phong.id AND A.vanban_id=" . $data['Vanban']['id'] . " ORDER BY Phong.lft ASC");
		if(is_numeric($id))
			$this->Vanban->query("UPDATE vanban_nhan SET ngay_xem='" . date("Y-m-d H:i:s") . "' WHERE vanban_id=" . $id . " AND nguoi_nhan_id=" . $this->Auth->user('nhanvien_id') . " AND ngay_xem IS NULL");
		else
			throw new InternalErrorException();
		$this->data = $data;
		$this->Nhanvanban->bindModel(array('belongsTo' => array('Nhanvien' => array('foreignKey' => 'nguoi_nhan_id', 'fields' => array('full_name', 'nguoi_quanly'), 'order' => array('ten' => 'ASC', 'ten_lot' => 'ASC', 'ho' => 'ASC')))));
		$nguoi_nhan = $this->Nhanvanban->find('all', array('conditions' => array('vanban_id' => $data['Vanban']['id'])));
		$vanban = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id, 'Vanban.nguoi_nhap_id' => $nv )));
		//pr($vanban);die();
		if(empty($vanban))
		{
			$id_nguoinhan = array();
			foreach($nguoi_nhan as $item):
				$t = $item['Nhanvanban']['nguoi_nhan_id'];
				array_push($id_nguoinhan, $t);
			endforeach;
			//pr($nv);die();
			//pr($id_nguoinhan);die();
			$f = in_array($nv,$id_nguoinhan);
			//pr($f);die();
			if($f == false)
				throw new InternalErrorException('Bạn không được phép xem văn bản này. Vui lòng chọn văn bản khác.');
		}
		$this->loadModel('Congviec');
		$check = $this->Congviec->find('first', array('conditions' => array('Congviec.vanban_id' => $id, 'Congviec.nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'))));
		//pr($check);die();
		
		$is_mobile = $this->RequestHandler->isMobile();
		$this->set(compact('nv','phong_nhan', 'nguoi_nhan', 'is_mobile','check'));
		$this->set('title_for_layout', 'Xem văn bản : ' . $data['Vanban']['trich_yeu']);
	}

 	function	get_files($id = null) 
	{
		$this->Vanban->bindModel(array(
			'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),
							'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')
							),
		), false);
		$nv = $this->Auth->user('nhanvien_id');
		$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id, 'Vanban.nguoi_nhap_id' => $nv )));
		if (empty($vanban))
			$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv )));			
		//pr($van_ban);die();
		if(empty($vanban))
			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
		$this->loadModel('Filevanban');
		//$data = $this->Filevanban->find('first', array('conditions' => array('Filevanban.id' => $id)));
		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
		$path = substr($path, 1, strlen($path)-1);
		$path = WWW_ROOT . $path;
		$file_moi = $vanban['Filevanban']['ten_moi'];
		$file_cu = $vanban['Filevanban']['ten_cu'];
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
	/*function	getfile_ggd11($id, $nv, $token, $timestamp = null) 
	{
		$s = Configure::read('Security.salt');
		$s = sha1($id.$nv.$s);
		//pr($s);die();
		if($s != $token)
			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
		else 
		{
			$this->Vanban->bindModel(array(
				'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),
								'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')
								),
			), false);
			$this->loadModel('User');
			$data = $this->User->find('first', array('conditions' => array('Nhanvien.id' => $nv)));
			if(empty($data))
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$last = strtotime($data['User']['last_action']);
			$duration =  time() - $last;
			if($duration > 120)
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$nv1 = $this->Auth->user('nhanvien_id');
			$nv_nhap = $this->Vanban->find('all', array('conditions' => array('Filevanban.id' => $id, 								
																'Vanban.nguoi_nhap_id' => $nv1 )));
			//pr($nv_nhap);die();
			if (!empty($nv_nhap))
				$van_ban = $nv_nhap;
			else
				$van_ban = $this->Vanban->find('all', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv )));			
			//$van_ban = $this->Vanban->find('all', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv1 )));
			pr($van_ban);die();
			if(empty($van_ban))
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
			$path = substr($path, 1, strlen($path)-1);
			$path = WWW_ROOT . $path;
			for ($i = 0; $i < count($van_ban); ++$i)
			{
				$file_moi = $van_ban[$i]['Filevanban']['ten_moi'];
				$file_cu = $van_ban[$i]['Filevanban']['ten_cu'];
				$file_contents = file_get_contents($path.$file_moi, true);
				$this->layout = null;
				Configure::write('debug',0);
				//gởi header đến cho browser
				header('Content-type: application/octet-stream');
				header('Content-disposition: attachment; filename="'.$file_cu.'"');
				//đọc file và trả dữ liệu về cho browser
				echo $file_contents;	 
				exit();
			}
		}
	}*/

function	getfile_ggd($id, $nv, $token, $timestamp = null) 
	{
		$s = Configure::read('Security.salt');
		$s = sha1($id.$nv.$s);
		//pr($s);die();
		if($s != $token)
			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
		else 
		{
			$this->Vanban->bindModel(array(
				'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),
								'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')
								),
			), false);
			$this->loadModel('User');
			$data = $this->User->find('first', array('conditions' => array('Nhanvien.id' => $nv)));
			if(empty($data))
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$last = strtotime($data['User']['last_action']);
			$duration =  time() - $last;
			if($duration > 120)
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$nv1 = $nv;
			$van_ban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv1 )));
			//pr($van_ban);die();
			if(empty($van_ban))
				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
			$path = substr($path, 1, strlen($path)-1);
			$path = WWW_ROOT . $path;
			$file_moi = $van_ban['Filevanban']['ten_moi'];
			$file_cu = $van_ban['Filevanban']['ten_cu'];
			/*
			for ($i = 0; $i < count($van_ban); ++$i)
			{
				$file_moi = $van_ban[$i]['Filevanban']['ten_moi'];
				$file_cu = $van_ban[$i]['Filevanban']['ten_cu'];
				$file_contents = file_get_contents($path.$file_moi, true);
				$this->layout = null;
				Configure::write('debug',0);
				//gởi header đến cho browser
				header('Content-type: application/octet-stream');
				header('Content-disposition: attachment; filename="'.$file_cu.'"');
				//đọc file và trả dữ liệu về cho browser
				echo $file_contents;	 
				exit();
			}
			*/
			if(!file_exists($path.$file_moi))
				die('Not found');
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

	public	function	attachfile()
	{
		if(empty($this->request->data['Vanban']))
			die(json_encode(array('success' => false, 'message' => 'Lỗi khi upload file')));
		//$arr = explode(".", $this->request->data['Vanban']['file']['name']);
		//pr($arr);die();
		//$ext = end($arr);
		//$filename = $this->Bin->slug(basename($this->request->data['Vanban']['file']['name'], $ext));
		$path =  $this->request->data['Vanban']['file']['name'];
		$ten_cu =  $path;
		$output = str_replace("/", DS, Configure::read('File.tmp'));
		//$ten_moi = md5(time());
		$ten_moi = md5(time() + rand());
		//$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $ten_moi. '.' . $ext;
		$output = WWW_ROOT . substr($output, 1, strlen($output)-1) . $ten_moi;
		//pr($output);die();
		if(move_uploaded_file($this->request->data['Vanban']['file']['tmp_name'], $output))
			//die(json_encode(array('success' => true, 'ten_cu' => $ten_cu, 'ten_moi' => $ten_moi . '.' . $ext )));
			die(json_encode(array('success' => true,'path' => $path, 'ten_cu' => $ten_cu, 'ten_moi' => $ten_moi )));
		else
			die(json_encode(array('success' => false, 'message' => 'Lỗi')));
	}

	public	function	remove_attach()	// xem lại cái này
	{
		if(!$this->check_permission('VanBan.gui'))
			throw new InternalErrorException('Bạn không được phép xóa văn bản này.');
		if(!empty($this->request->data['key']))
		{
			$path = str_replace("/", DS, Configure::read('File.tmp'));
			$path = WWW_ROOT . substr($path, 1, strlen($path)-1);
			@unlink($path . $this->request->data['key']);	
			die(json_encode(array('success' => true, 'id' => $this->request->data['key'])));
		}
		die(json_encode(array('success' => false)));
	}

	public	function	mark_read()
	{
		if(!$this->check_permission('VanBan.doc'))
			throw new InternalErrorException('Bạn không có quyền xem văn bản này. Vui lòng thử lại.');	
		$this->layout = null;
		$f = false;
		if(!empty($this->request->data))
		{
			$this->loadModel('Nhanvanban');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach($ids as $k=>$v)
			{
				if($this->Nhanvanban->updateAll(array('ngay_xem' => "'" . date('Y-m-d H:i:s') . "'"), array('Nhanvanban.id' => $v, 'Nhanvanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'Nhanvanban.ngay_xem' => NULL)))
					$success++;
			}
			if($success > 0)
				$f = true;
		}
		if($f)
			$this->Session->setFlash('Đã đánh dấu thành công ' . $success . ' mục.', 'flash_success');
		else
			$this->Session->setFlash('Đã phát sinh lỗi khi đánh dấu văn bản.', 'flash_error');
		$this->redirect('/vanban/' . $this->passedArgs['type']);
	}

	public	function	untrack()
	{
		if(!$this->check_permission('VanBan.theodoi'))
			throw new InternalErrorException('Bạn không có quyền theo dõi văn bản. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$f = false;
		if(!empty($this->request->data))
		{
			$this->loadModel('Theodoivanban');
			$success = 0;
			$ids = explode(",", $this->request->data['v_id']);
			foreach($ids as $k=>$v)
			{
				if($this->Theodoivanban->deleteAll(array('Theodoivanban.id' => $v, 'Theodoivanban.nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))))
					$success++;
			}
			if($success > 0)
				$f = true;
		}
		if($f)
			$this->Session->setFlash('Đã ngừng theo dõi ' . $success . ' văn bản.', 'flash_success');
		else
			$this->Session->setFlash('Đã phát sinh lỗi.', 'flash_error');
		$this->redirect('/vanban/theodoi');
	}

	public	function	sua()
	{
		if(!$this->check_permission('VanBan.quanly')
					&& !$this->check_permission('VanBan.sua'))
			throw new InternalErrorException('Bạn không có quyền sửa văn bản. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$conds = array();
		if(!$this->check_permission('VanBan.quanly'))	// nếu ko có quyền quản lý
		{
			if($this->check_permission('VanBan.sua'))	// có quyền sửa văn bản
				$conds['Vanban.nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');	// thì chỉ sửa văn bản mình đã gửi
			else
				throw new InternalErrorException();		// phát sinh lỗi
		}else
		{
			$conds['Vanban.nguoi_nhap_id'] = $this->Auth->user('nhanvien_id');
		}
		if(!empty($this->request->data))
		{
			$day = '';
			$month = '';
			$year = '';
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['ngay']))
			{
				if(!empty($this->request->data['Vanban']['ngay_phathanh']['day']) && !empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$date = sprintf("%s-%s-%s", $this->request->data['Vanban']['ngay_phathanh']['year']
											  , $this->request->data['Vanban']['ngay_phathanh']['month']
											  , $this->request->data['Vanban']['ngay_phathanh']['day']);
					$conds[$this->request->data['Vanban']['ngay']] = $date;
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['date'] = $date;
				}else if(!empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$month_year = $this->request->data['Vanban']['ngay_phathanh']['year'] . '-' . $this->request->data['Vanban']['ngay_phathanh']['month'];
					$conds[] = "DATE_FORMAT(" . $this->request->data['Vanban']['ngay'] . ", '%X-%m') = '" . $month_year . "'";
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['month_year'] = $month_year;
				}
			}
			if(!empty($this->request->data['Vanban']['tinhchatvanban_id']))
			{
				$this->passedArgs['tinhchatvanban_id'] = $this->data['Vanban']['tinhchatvanban_id'];
				$conds["Vanban.tinhchatvanban_id"] = $this->request->data['Vanban']['tinhchatvanban_id'];
			}
			if(!empty($this->request->data['Vanban']['loaivanban_id']))
			{
				$this->passedArgs['loaivanban_id'] = $this->data['Vanban']['loaivanban_id'];
				$conds["Vanban.loaivanban_id"] = $this->request->data['Vanban']['loaivanban_id'];
			}
			if(!empty($this->request->data['Vanban']['chieu_di']))
			{
				$this->passedArgs['chieu_di'] = $this->data['Vanban']['chieu_di'];
				$conds["Vanban.chieu_di"] = $this->request->data['Vanban']['chieu_di'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(isset($this->passedArgs['ngay']))
			{
				if(isset($this->passedArgs['date']))
				{
					$conds[$this->passedArgs['ngay']] = $this->passedArgs['date'];
				}else if(isset($this->passedArgs['month_year']))
					$conds[] = "DATE_FORMAT(" . $this->passedArgs['ngay'] . ", '%X-%m') = '" . $this->passedArgs['month_year'] . "'";
			}
			if(isset($this->passedArgs['tinhchatvanban_id']))
			{
				$conds["Vanban.tinhchatvanban_id"] = $this->passedArgs['tinhchatvanban_id'];
			}
			if(isset($this->passedArgs['loaivanban_id']))
			{
				$conds["Vanban.loaivanban_id"] = $this->passedArgs['loaivanban_id'];
			}
			if(isset($this->passedArgs['chieu_di']))
			{
				$conds["Vanban.chieu_di"] = $this->passedArgs['chieu_di'];
			}
		}
		$ds =  $this->paginate('Vanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không tìm thấy văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Tra cứu  và hiệu chỉnh văn bản đã gửi');
			$this->loadModel('Tinhchatvanban');
			$tinhchat = $this->Tinhchatvanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
			$chieu_di = $this->Vanban->chieu_di;
			$this->loadModel('Loaivanban');
			$loaivanban = $this->Loaivanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_loaivanban')));
			$this->set(compact('tinhchat', 'loaivanban', 'chieu_di'));
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('vanban_sua');
		}
	}
	
	public	function	search()
	{
		if(!$this->check_permission('VanBan.tracuu'))
			throw new InternalErrorException('Bạn không có quyền tra cứu văn bản. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		//$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Luuvanban', 'Theodoivanban', 'Filevanban', 'Xulyvanban')), false);
		$this->Vanban->bindModel(
				array(
					'hasOne'	=>	array('Nhanvanban' => array('foreignKey'	=>	'vanban_id',
										  						'className'	=>	'Nhanvanban'),
											'Theodoivanban' => array(
												'foreignKey' => 'vanban_id', 
												'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))
												)
											)
				), false
			);	
		$conds = array();	
		/*if($this->Auth->user('nhanvien_id')==338){
		}else{*/
			$conds['Nhanvanban.nguoi_nhan_id'] = $this->Auth->user('nhanvien_id');	
		//}
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['nguoi_ky']))
			{
				$this->passedArgs['nguoi_ky'] = $this->data['Vanban']['nguoi_ky'];
				$conds[] = array("Vanban.nguoi_ky LIKE"	=>	"%" . $this->request->data['Vanban']['nguoi_ky'] . "%");
			}
			if(!empty($this->request->data['Vanban']['loaivanban_id']))
			{
				$this->passedArgs['loaivanban_id'] = $this->data['Vanban']['loaivanban_id'];
				$conds["Vanban.loaivanban_id"] = $this->request->data['Vanban']['loaivanban_id'];
			}
			if(!empty($this->request->data['Vanban']['noi_gui']))
			{
				$this->passedArgs['noi_gui'] = $this->data['Vanban']['noi_gui'];
				$conds[] = array("Vanban.noi_gui LIKE"	=>	"%" . $this->request->data['Vanban']['noi_gui'] . "%");
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
						&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(isset($this->passedArgs['nguoi_ky']))
			{
				$conds[] = array("Vanban.nguoi_ky LIKE"	=>	"%" . $this->passedArgs['nguoi_ky'] . "%");
			}
			if(isset($this->passedArgs['loaivanban_id']))
			{
				$conds["Vanban.loaivanban_id"] = $this->passedArgs['loaivanban_id'];
			}
			if(isset($this->passedArgs['noi_gui']))
			{
				$conds[] = array("Vanban.noi_gui LIKE"	=>	"%" . $this->passedArgs['noi_gui'] . "%");
			}
			if( isset($this->passedArgs['ngay_batdau']) 
						&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc'] );
			}
		}
		$ds =  $this->paginate('Vanban', $conds);
		//
		//pr($ds); die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không tìm thấy văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
		if(!$this->RequestHandler->isAjax())
		{
			$this->set('title_for_layout', 'Tra cứu văn bản đã nhận');
			$this->loadModel('Tinhchatvanban');
			$tinhchat = $this->Tinhchatvanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_tinhchat')));
			$chieu_di = $this->Vanban->chieu_di;
			$this->loadModel('Loaivanban');
			$loaivanban = $this->Loaivanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_loaivanban')));
			$this->set(compact('tinhchat', 'loaivanban', 'chieu_di'));
		}else
		{
			$this->viewPath = 'Elements' . DS . 'Common';
			$this->render('vanban_search');
		}
	}

	function	excel_den()
	{
		$this->layout = null;
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1);
		if(!empty($this->request->data))
		{
			$day = '';
			$month = '';
			$year = '';
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['ngay']))
			{
				if(!empty($this->request->data['Vanban']['ngay_phathanh']['day']) && !empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$date = sprintf("%s-%s-%s", $this->request->data['Vanban']['ngay_phathanh']['year']
											  , $this->request->data['Vanban']['ngay_phathanh']['month']
											  , $this->request->data['Vanban']['ngay_phathanh']['day']);
					$conds[$this->request->data['Vanban']['ngay']] = $date;
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['date'] = $date;
				}else if(!empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$month_year = $this->request->data['Vanban']['ngay_phathanh']['year'] . '-' . $this->request->data['Vanban']['ngay_phathanh']['month'];
					$conds[] = "DATE_FORMAT(" . $this->request->data['Vanban']['ngay'] . ", '%X-%m') = '" . $month_year . "'";
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['month_year'] = $month_year;
				}
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(isset($this->passedArgs['ngay']))
			{
				if(isset($this->passedArgs['date']))
				{
					$conds[$this->passedArgs['ngay']] = $this->passedArgs['date'];
				}else if(isset($this->passedArgs['month_year']))
					$conds[] = "DATE_FORMAT(" . $this->passedArgs['ngay'] . ", '%X-%m') = '" . $this->passedArgs['month_year'] . "'";
			}
		}
		$ds =  $this->Nhanvanban->find('all', array('conditions' => $conds));
		//pr($ds); die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	excel_di()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$this->Nhanvanban->recursive = 2;
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	0);
		if(!empty($this->request->data))
		{
			$day = '';
			$month = '';
			$year = '';
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['ngay']))
			{
				if(!empty($this->request->data['Vanban']['ngay_phathanh']['day']) && !empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$date = sprintf("%s-%s-%s", $this->request->data['Vanban']['ngay_phathanh']['year']
											  , $this->request->data['Vanban']['ngay_phathanh']['month']
											  , $this->request->data['Vanban']['ngay_phathanh']['day']);
					$conds[$this->request->data['Vanban']['ngay']] = $date;
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['date'] = $date;
				}else if(!empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$month_year = $this->request->data['Vanban']['ngay_phathanh']['year'] . '-' . $this->request->data['Vanban']['ngay_phathanh']['month'];
					$conds[] = "DATE_FORMAT(" . $this->request->data['Vanban']['ngay'] . ", '%X-%m') = '" . $month_year . "'";
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['month_year'] = $month_year;
				}
			}
		}
		$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Theodoivanban', 'Xulyvanban', 'Filevanban')), false);
		$ds =  $this->Nhanvanban->find('all', array('conditions' => $conds));
		$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
		$this->PhpExcel->setActiveSheetIndex(0);
		$sheet = $this->PhpExcel->getActiveSheet();
		// style 
		$header_style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
			'font' =>	array(
					'bold'	=>	true,
					'size'	=>	16
				)
		);
		$table_header_style = array(
			'font' =>	array(
					'bold'	=>	true
				),
		);
		$sheet->mergeCells('A1:E1');
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);
		$sheet->setCellValue('A1', 'THỐNG KÊ VĂN BẢN ĐI');
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Số hiệu văn bản'), 'filter' => true),
			array('label' => __('Ngày phát hành'), 'filter' => true),
			array('label' => __('Nơi phát hành'), 'filter' => true),
			array('label' => __('Ngày gửi'), 'filter' => true),
			array('label' => __('Ngày nhập'), 'filter' => true),
			array('label' => __('Trích yếu'), 'filter' => true),
		);
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		// add data
		if(!empty($ds)):
			$stt = 1;
			foreach ($ds as $d):
				$this->PhpExcel->addTableRow(array(
					$stt++,
					$d['Vanban']['so_hieu'],
					$d['Vanban']['ngay_phathanh'],
					$d['Vanban']['noi_gui'],
					$d['Vanban']['ngay_gui'],
					$d['Vanban']['ngay_nhap'],
					$d['Vanban']['trich_yeu'],
				));
			endforeach;
		endif;
		// close table and output
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output('vanbandi_' . date('d_m_Y', time()) . '.xlsx');
	}

	public	function	excel_noibo()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	2);	// văn bản nội bộ
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$ds =  $this->paginate('Nhanvanban', $conds);
		$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
		$this->PhpExcel->setActiveSheetIndex(0);
		$sheet = $this->PhpExcel->getActiveSheet();
		// style 
		$header_style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
			'font' =>	array(
					'bold'	=>	true,
					'size'	=>	16
				)
		);
		$table_header_style = array(
			'font' =>	array(
					'bold'	=>	true
				),
		);
		$sheet->mergeCells('A1:E1');
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);
		$sheet->setCellValue('A1', 'THỐNG KÊ VĂN BẢN NỘI BỘ');
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Số hiệu văn bản'), 'filter' => true),
			array('label' => __('Ngày phát hành'), 'filter' => true),
			array('label' => __('Nơi phát hành'), 'filter' => true),
			array('label' => __('Ngày gửi'), 'filter' => true),
			array('label' => __('Ngày nhập'), 'filter' => true),
			array('label' => __('Trích yếu'), 'filter' => true),
		);
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		// add data
		if(!empty($ds)):
			$stt = 1;
			foreach ($ds as $d):
				$this->PhpExcel->addTableRow(array(
					$stt++,
					$d['Vanban']['so_hieu'],
					$d['Vanban']['ngay_phathanh'],
					$d['Vanban']['noi_gui'],
					$d['Vanban']['ngay_gui'],
					$d['Vanban']['ngay_nhap'],
					$d['Vanban']['trich_yeu'],
				));
			endforeach;
		endif;
		// close table and output
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output('vanbannoibo' . date('Y_m_d', time()) . '.xlsx');
	}

	public	function	excel_search()
	{
		$this->layout = null;
		$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Theodoivanban', 'Filevanban', 'Xulyvanban')), false);
		$this->Vanban->bindModel(
				array(
					'hasOne'	=>	array('Nhanvanban' => array('foreignKey'	=>	'vanban_id',
										  						'className'	=>	'Nhanvanban'))
				), false
			);
		$conds = array();
		$conds['Nhanvanban.nguoi_nhan_id'] = $this->Auth->user('nhanvien_id');
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['nguoi_ky']))
			{
				$this->passedArgs['nguoi_ky'] = $this->data['Vanban']['nguoi_ky'];
				$conds[] = array("Vanban.nguoi_ky LIKE"	=>	"%" . $this->request->data['Vanban']['nguoi_ky'] . "%");
				//$conds["Vanban.nguoi_ky"] = $this->request->data['Vanban']['nguoi_ky'];
			}
			if(!empty($this->request->data['Vanban']['loaivanban_id']))
			{
				$this->passedArgs['loaivanban_id'] = $this->data['Vanban']['loaivanban_id'];
				$conds["Vanban.loaivanban_id"] = $this->request->data['Vanban']['loaivanban_id'];
			}
			if(!empty($this->request->data['Vanban']['noi_gui']))
			{
				$this->passedArgs['noi_gui'] = $this->data['Vanban']['noi_gui'];
				$conds[] = array("Vanban.noi_gui LIKE"	=>	"%" . $this->request->data['Vanban']['noi_gui'] . "%");
	//			$conds["Vanban.noi_gui"] = $this->request->data['Vanban']['noi_gui'];
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
						&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(isset($this->passedArgs['loaivanban_id']))
			{
				$conds["Vanban.loaivanban_id"] = $this->passedArgs['loaivanban_id'];
			}
			if( isset($this->passedArgs['ngay_batdau']) 
						&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc'] );
			}
		}
		//pr($conds);die();
		$ds =  $this->Vanban->find('all', array('conditions' => $conds));
		//pr($ds);die();		
		$this->PhpExcel->createWorksheet()->setDefaultFont('Calibri', 12);
		$this->PhpExcel->setActiveSheetIndex(0);
		$sheet = $this->PhpExcel->getActiveSheet();
		// style 
		$header_style = array(
			'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
			'font' =>	array(
					'bold'	=>	true,
					'size'	=>	16
				)
		);
		$table_header_style = array(
			'font' =>	array(
					'bold'	=>	true
				),
		);
		$sheet->mergeCells('A1:E1');
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A1:A2')->applyFromArray($header_style);
		$sheet->setCellValue('A1', 'TÌM KIẾM VĂN BẢN');
		$this->PhpExcel->setRow(5);
		// define table cells
		$table = array(
			array('label' => __('STT'), 'filter' => false),
			array('label' => __('Số hiệu văn bản'), 'filter' => true),
			array('label' => __('Ngày phát hành'), 'filter' => true),
			array('label' => __('Nơi phát hành'), 'filter' => true),
			array('label' => __('Người ký'), 'filter' => true),			
			array('label' => __('Ngày gửi'), 'filter' => true),
			array('label' => __('Ngày nhập'), 'filter' => true),
			array('label' => __('Trích yếu'), 'filter' => true),
		);
		// add heading with different font and bold text
		$this->PhpExcel->addTableHeader($table, array('bold' => true));
		// add data
		if(!empty($ds)):
			$stt = 1;
			foreach ($ds as $d):
				$this->PhpExcel->addTableRow(array(
					$stt++,
					$d['Vanban']['so_hieu'],
					$d['Vanban']['ngay_phathanh'],
					$d['Vanban']['noi_gui'],
					$d['Vanban']['nguoi_ky'],
					$d['Vanban']['ngay_gui'],
					$d['Vanban']['ngay_nhap'],
					$d['Vanban']['trich_yeu'],
				));
			endforeach;
		endif;
		// close table and output
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output('dsvanban.xlsx');
	}

	public	function	excel_sua()
	{
		$this->layout = null;
		$conds = array();
		$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Theodoivanban', 'Filevanban', 'Xulyvanban')), false);
		if(!empty($this->request->data))
		{
			$day = '';
			$month = '';
			$year = '';
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(!empty($this->request->data['Vanban']['ngay']))
			{
				if(!empty($this->request->data['Vanban']['ngay_phathanh']['day']) && !empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$date = sprintf("%s-%s-%s", $this->request->data['Vanban']['ngay_phathanh']['year']
											  , $this->request->data['Vanban']['ngay_phathanh']['month']
											  , $this->request->data['Vanban']['ngay_phathanh']['day']);
					$conds[$this->request->data['Vanban']['ngay']] = $date;
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['date'] = $date;
				}else if(!empty($this->request->data['Vanban']['ngay_phathanh']['month']))
				{
					$month_year = $this->request->data['Vanban']['ngay_phathanh']['year'] . '-' . $this->request->data['Vanban']['ngay_phathanh']['month'];
					$conds[] = "DATE_FORMAT(" . $this->request->data['Vanban']['ngay'] . ", '%X-%m') = '" . $month_year . "'";
					$this->passedArgs['ngay'] = $this->request->data['Vanban']['ngay'];
					$this->passedArgs['month_year'] = $month_year;
				}
			}
			if(!empty($this->request->data['Vanban']['tinhchatvanban_id']))
			{
				$this->passedArgs['tinhchatvanban_id'] = $this->data['Vanban']['tinhchatvanban_id'];
				$conds["Vanban.tinhchatvanban_id"] = $this->request->data['Vanban']['tinhchatvanban_id'];
			}
			if(!empty($this->request->data['Vanban']['loaivanban_id']))
			{
				$this->passedArgs['loaivanban_id'] = $this->data['Vanban']['loaivanban_id'];
				$conds["Vanban.loaivanban_id"] = $this->request->data['Vanban']['loaivanban_id'];
			}
			if(!empty($this->request->data['Vanban']['chieu_di']))
			{
				$this->passedArgs['chieu_di'] = $this->data['Vanban']['chieu_di'];
				$conds["Vanban.chieu_di"] = $this->request->data['Vanban']['chieu_di'];
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu_den LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if(isset($this->passedArgs['ngay']))
			{
				if(isset($this->passedArgs['date']))
				{
					$conds[$this->passedArgs['ngay']] = $this->passedArgs['date'];
				}else if(isset($this->passedArgs['month_year']))
					$conds[] = "DATE_FORMAT(" . $this->passedArgs['ngay'] . ", '%X-%m') = '" . $this->passedArgs['month_year'] . "'";
			}
			if(isset($this->passedArgs['tinhchatvanban_id']))
			{
				$conds["Vanban.tinhchatvanban_id"] = $this->passedArgs['tinhchatvanban_id'];
			}
			if(isset($this->passedArgs['loaivanban_id']))
			{
				$conds["Vanban.loaivanban_id"] = $this->passedArgs['loaivanban_id'];
			}
			if(isset($this->passedArgs['chieu_di']))
			{
				$conds["Vanban.chieu_di"] = $this->passedArgs['chieu_di'];
			}
		}
		$ds =  $this->paginate('Vanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không tìm thấy văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function 	danhdau_theodoi()
	{
		$this->layout = null;
		$f = false;
		$this->loadModel('Theodoivanban');
		if(!empty($this->request->data))
		{
			$id  = $this->request->data['Vanban']['vanban_id'];
			$count = $this->Theodoivanban->find('count', array('conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'), 'vanban_id' => $id)));
			if($count <= 0)
			{
				if(	$this->Theodoivanban->save(array('nguoi_theodoi_id'	=>	$this->Auth->user('nhanvien_id'),
														 'vanban_id'		=>	$id,
														 'ghi_chu'	=>	$this->request->data['Vanban']['ghi_chu'],
														 'ngay_theodoi'		=>	date('Y-m-d H:i:s'))) )
					$f = true;
			}else
				$f = true;
			if($f)	
				$this->Session->setFlash('Văn bản đã được đưa vào mục theo dõi.', 'flash_success');
			else
				$this->Session->setFlash('Đã phát sinh lỗi khi đưa văn bản vào mục theo dõi. Vui lòng thử lại', 'flash_success');
			if($this->request->data['Vanban']['type'] == 'search')
				$this->redirect('/vanban/search');
			else
				$this->redirect('/baocao/vtdn_theodoi');
				//$this->redirect('/vanban/' . $this->request->data['Vanban']['type']);
		}else
		{
			$type = $this->passedArgs['type'];
			$id = $this->passedArgs['id'];
			$target = $this->passedArgs['target'];
			$this->set(compact(array('type', 'target', 'id')));
		}
	}

	public	function 	danhdau_theodoi_ajax()
	{
		$this->layout = null;
		$f = false;
		$this->loadModel('Theodoivanban');
		if(!empty($this->request->data))
		{
			$id  = $this->request->data['vanban_id'];
			$count = $this->Theodoivanban->find('count', array('conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'), 'vanban_id' => $id)));
			if($count <= 0)
			{
				if(	$this->Theodoivanban->save(array('nguoi_theodoi_id'	=>	$this->Auth->user('nhanvien_id'),
														 'vanban_id'		=>	$id,
														 'ngay_theodoi'		=>	date('Y-m-d H:i:s'))) )
					die(json_encode(array('success' => true)));
				else
					die(json_encode(array('success' => false)));
			}else
				die(json_encode(array('success' => true)));
		}
		die(json_encode(array('success' => false)));
	}

	public	function	donvinhan()
	{
		$this->loadModel('Phong');
		$ds = $this->Phong->getList();
		$phongnhan = array();
		$vanban_id = 0;
		if(!empty($this->request->data['vanban_id']))
		{
			$vanban_id = $this->request->data['vanban_id'];
			$nhan = $this->Vanban->query("SELECT DISTINCT phong_id as phong_id FROM vanban_nhan A, nhansu_nhanvien Nhanvien WHERE A.nguoi_nhan_id = Nhanvien.id AND A.vanban_id=" . $vanban_id);
			if(!empty($nhan))
				foreach($nhan as $item)
					array_push($phongnhan, $item['Nhanvien']['phong_id']);
		}
		$this->set(compact(array('ds', 'phongnhan', 'vanban_id')));
	}

	public	function	nhanviennhan()
	{
		$nhanviennhan = array();
		$vanban_id = 0;
		if(!empty($this->request->data['vanban_id']))
		{
			$vanban_id = $this->request->data['vanban_id'];
			$nhanviennhan = $this->Vanban->Nhanvanban->find('list', array('conditions' => array('vanban_id' => $vanban_id), 'fields' => 'nguoi_nhan_id'));
		}
		$this->loadModel('Nhanvien');
		$ds = $this->Nhanvien->listNhanvien();
		$this->set(compact(array('ds', 'nhanviennhan', 'vanban_id')));
	}

	public function		get_max_sohieuden()
	{
		$max_value = $this->Vanban->find('first', array('fields' => 'so_hieu_den', 'order' => 'so_hieu_den DESC'));
		if(!isset($max_value['Vanban']['so_hieu_den']))
			$max_value['Vanban']['so_hieu_den'] = 1;
		else
			$max_value['Vanban']['so_hieu_den']++;
		die(json_encode(array('success' => true, 'max' => $max_value['Vanban']['so_hieu_den'])));
		//return $max_value['Vanban']['so_hieu_den'];
	}

	public	function	get_loaivanban($type = 0)
	{
		$loaivanban = $this->Vanban->Loaivanban->find('list', array('conditions' => array('enabled=1', 'chieu_di' => $type), 'fields' => array('id', 'ten_loaivanban')));
		if(!empty($loaivanban))
		{
			$arr = array();
			foreach($loaivanban as $k=>$v)
				array_push($arr, array('id'	=>	$k, 'value' => $v));
			die(json_encode(array('success' => true, 'data' => $arr)));
		}
		else
			die(json_encode(array('success' => false)));
	}

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

	public	function	xuly_ajax($vanban_id)
	{
		$this->loadModel('Xulyvanban');
		$ds = $this->Xulyvanban->find('all', array('conditions' => array('vanban_id' => $vanban_id)));
		if(empty($ds))
		{
			$this->Session->setFlash('Hiện tại chưa có dữ liệu.', 'flash_attention');
		}
		$this->set('ds', $ds);
	}

	public	function	mobile_index()
	{
		$this->set('title_for_layout', 'BIN+ Mobile - Văn bản');
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$chuadoc = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'ngay_xem' => NULL)));
		$tatca = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'))));
		$di = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'chieu_di' => 0)));
		$den = $this->Nhanvanban->find('count', array('conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'), 'chieu_di' => 1)));
		$this->loadModel('Theodoivanban');
		$theodoi = $this->Theodoivanban->find('count', array('conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))));
		$this->set(compact('chuadoc', 'tatca', 'di', 'den', 'theodoi'));
	}

	public	function	mobile_chuadoc()
	{
		$this->set('title_for_layout', 'BIN+ Mobile - Văn bản chưa đọc');
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Nhanvanban.ngay_xem' 		=> NULL);
//		array_push($conds, 'Nhanvanban.ngay_xem IS NULL');
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
						&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
					  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
					  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
						&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc'] );
			}
		}
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	mobile_tatca()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'));
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
							  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
							  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		} elseif ( isset( $this->passedArgs ) )
		{
			if( !empty( $this->passedArgs['keyword']) )
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) 
							&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Nhanvanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$this->set(compact('ds'));
	}

	public	function	mobile_view($id)
	{
		$this->Vanban->unbindModel(array('hasMany' => array('Nhanvanban', 'Theodoivanban')), false);
		$data = $this->Vanban->read(null, $id);
		$this->Vanban->query("UPDATE vanban_nhan SET ngay_xem='" . date("Y-m-d H:i:s") . "' WHERE vanban_id=" . $id . " AND nguoi_nhan_id=" . $this->Auth->user('nhanvien_id') . " AND ngay_xem IS NULL");
		$data['file_path'] = 'http://' . env("HTTP_HOST") . Configure::read('VanBan.attach_path');
		die(json_encode($data));
	}

	public	function	mobile_di()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	0);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(
				!empty($this->request->data['Vanban']['ngay_batdau'])
				&& !empty($this->request->data['Vanban']['ngay_ketthuc'])){
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'], $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Nhanvanban', $conds);
		$this->set(compact('ds'));
	}

	public	function	mobile_den()
	{
		$this->loadModel('Nhanvanban');
		$this->Nhanvanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1);
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if( !empty($this->request->data['Vanban']['ngay_batdau'])
							&& !empty($this->request->data['Vanban']['ngay_ketthuc']))
			{
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Nhanvanban', $conds);
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	public	function	mobile_theodoi()
	{
		$this->loadModel('Theodoivanban');
		$this->Theodoivanban->bindModel(array(
								'belongsTo'	=>	array('Vanban')
										   ), false);
		$conds = array( 'Theodoivanban.nguoi_theodoi_id'	=>	$this->Auth->user('nhanvien_id'));
		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Vanban']['keyword']))
			{
				$this->passedArgs['keyword'] = $this->data['Vanban']['keyword'];
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->request->data['Vanban']['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->request->data['Vanban']['keyword'] . "%"
								  );
			}
			if(
				!empty($this->request->data['Vanban']['ngay_batdau'])
				&& !empty($this->request->data['Vanban']['ngay_ketthuc'])){
				$ngay_batdau = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_batdau']);
				$ngay_ketthuc = $this->Bin->vn2sql($this->request->data['Vanban']['ngay_ketthuc']);
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $ngay_batdau,  $ngay_ketthuc);
				$this->passedArgs['ngay_batdau'] = $ngay_batdau;
				$this->passedArgs['ngay_ketthuc'] = $ngay_ketthuc;
			}
		}elseif(isset($this->passedArgs))
		{
			if(!empty($this->passedArgs['keyword']))
				$conds["OR"]	= array(
								  "Vanban.trich_yeu LIKE"	=>	"%" . $this->passedArgs['keyword'] . "%",
								  "Vanban.so_hieu LIKE"		=>	"%" . $this->passedArgs['keyword'] . "%"
								  );
			if( isset($this->passedArgs['ngay_batdau']) && isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc']);
			}
		}
		$this->paginate['limit'] = 10;
		$ds =  $this->paginate('Theodoivanban', $conds);
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}
}





