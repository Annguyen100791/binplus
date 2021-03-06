﻿<?php

/**

 * Vanban controller

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

class VanbanController extends AppController {

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

	

	public function 	index_vanbanden()

	{

		if(!$this->check_permission('VanBan.nhan'))

			throw new InternalErrorException('Bạn không có quyền nhận văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	

		$this->set('title_for_layout', 'Danh sách văn bản');

	}

	public	function	unread()
	{

		//pr($this->Auth->user());die();

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

//		//var_dump($this->check_permission('BaoCao.xem')); die();

		/*if($this->check_permission('BaoCao.xem')){ // có quyền xem báo cáo

			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

					   'Nhanvanban.ngay_xem' 		=> NULL,

					   'Vanban.phong_id ' => NULL,

					   'Vanban.tinhtrang_duyet' => 1);

		}

		else*/

		//{

			/*$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Nhanvanban.ngay_xem' 		=> NULL,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.phong_id' 		=> NULL);*/

		//}
		if($this->Auth->User('nhanvien_id') == 681) 
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Nhanvanban.ngay_xem' 		=> NULL,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1
					  );
		else
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Nhanvanban.ngay_xem' 		=> NULL,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL 
					  );
					   
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



	public	function	all()

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

		//var_dump($this->check_permission('BaoCao.xem')); die();

		/*if($this->check_permission('BaoCao.xem')){ // có quyền xem báo cáo

			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

							'Vanban.phong_id ' => NULL,

							'Vanban.tinhtrang_duyet' => 1

						);

		}*/

		//else

		//{

			/*$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.tinhtrang_duyet' => 1,
							'Vanban.phong_id' 		=> NULL);*/

		//}

		if($this->Auth->User('nhanvien_id') == 681) 
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1
							);
		else
			$conds = array('Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL
							);

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



	public	function	den()

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

		//var_dump($this->check_permission('BaoCao.xem')); die();

		/*if($this->check_permission('BaoCao.xem')){ // có quyền xem báo cáo

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	1,

						'Vanban.phong_id ' => NULL,

						'Vanban.tinhtrang_duyet' => 1);

		}

		else

		{

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	1,

						'Vanban.tinhtrang_duyet' => 1);

		}*/
		if($this->Auth->User('nhanvien_id') == 681) 
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1,
						'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL,
					   'Vanban.chuyen_bypass <>' 	=> 1
							);
		else
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1,
						'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.loaivanban_id <>' 		=> NULL);
		

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

		$this->Vanban->bindModel(array(

			'hasOne' => array(

				'Theodoivanban' => array('foreignKey' => 'vanban_id',

					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))

				)

			)

		));		

		//var_dump($this->check_permission('BaoCao.xem')); die();

		/*if($this->check_permission('BaoCao.xem')){ // có quyền xem báo cáo

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

 						'Vanban.chieu_di'			=>	0,

						'Vanban.phong_id ' => NULL,

						'Vanban.tinhtrang_duyet' => 1);

		}

		else

		{

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

 						'Vanban.chieu_di'			=>	0,

						'Vanban.tinhtrang_duyet' => 1);

		}*/
		if($this->Auth->User('nhanvien_id') == 681) 
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
 							'Vanban.chieu_di'			=>	0,
							'Vanban.tinhtrang_duyet' => 1,
					   		'Vanban.loaivanban_id <>' 		=> NULL
								);
		else
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
 							'Vanban.chieu_di'			=>	0,
							'Vanban.tinhtrang_duyet' => 1,
					   		'Vanban.loaivanban_id <>' 		=> NULL,
							'Vanban.chuyen_bypass <>' 		=> 1
								);
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

		$this->Vanban->bindModel(array(

			'hasOne' => array(

				'Theodoivanban' => array('foreignKey' => 'vanban_id',

					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))

				)

			)

		));		

		//var_dump($this->check_permission('BaoCao.xem')); die();

		/*if($this->check_permission('BaoCao.xem')){ // có quyền xem báo cáo

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	2,

						'Vanban.phong_id ' => NULL,

						'Vanban.tinhtrang_duyet' => 1);	// văn bản nội bộ

		}

		else

		{

			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	2,

						'Vanban.tinhtrang_duyet' => 1);	// văn bản nội bộ

		}*/
		if($this->Auth->User('nhanvien_id') == 681)
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.chieu_di'			=>	2,
							'Vanban.tinhtrang_duyet' => 1,
						    'Vanban.loaivanban_id <>' 		=> NULL,
							'Vanban.chuyen_bypass <>' 		=> 1);	// văn bản nội bộ
		else
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.chieu_di'			=>	2,
							'Vanban.tinhtrang_duyet' => 1,
						    'Vanban.loaivanban_id <>' 		=> NULL,);	// văn bản nội bộ
		
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



	public	function	theodoi()

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

		$ds =  $this->paginate('Theodoivanban', $conds);

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

	}
	
	public	function	vbden_gap()
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
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array('Ketquavanban' => array('foreignKey' => 'vanban_id',
									'conditions' => array('nguoi_capnhat_id' => $this->Auth->user('nhanvien_id')))
							)
		), false);
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.chieu_di'			=>	1,
						'Vanban.tinhtrang_duyet' => 1,
						'Vanban.vb_gap'			=> 1);
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
		//pr($ds);die(); 

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}
		$this->set(compact('ds'));

	}

	public	function	vb_giamdocchidao()
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
		
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						//'Nhanvanban.ngayxem_chidao' => NULL,
						'Vanban.gd_chidao <>'			=>	NULL);
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
		//pr($ds);die(); 

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}
		$this->set(compact('ds'));

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
			//pr($this->request->data['Vanban']['chieu_di']);die();
			if($this->request->data['Vanban']['chieu_di'] == 1)//văn bản đến
				$this->request->data['Vanban']['tinhtrang_duyet'] = 0;

			else
				$this->request->data['Vanban']['tinhtrang_duyet'] = 1;
			
			if($this->request->data['Vanban']['chieu_di'] != 1)	// không phải VB đến

			{

				$this->request->data['Vanban']['so_hieu_den'] = NULL;

				$this->request->data['Vanban']['ngay_gui'] = $this->request->data['Vanban']['ngay_nhan'];

				$this->request->data['Vanban']['ngay_nhan'] = NULL;

			}

			
			
				

			$this->request->data['Luuvanban'] = array();

			if(!empty($this->request->data['Vanban']['noi_luu']))	

				foreach($this->request->data['Vanban']['noi_luu'] as $k => $v)

				{

					array_push($this->request->data['Luuvanban'], array('phong_id' => $v));

				}

			unset($this->request->data['Vanban']['noi_luu']);

			//if($this->request->data['Vanban']['loaivanban_id'] == 4){

				//$this->request->data['Vanban']['loaivanban_id'] = $this->request->data['Vanban']['loaivanbanct_id'] ;

			//}

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

			$chieu_di = $this->Vanban->chieu_di;

			$this->loadModel('Loaivanban');

			$loaivanban = $this->Loaivanban->find('list', array('conditions' => array('enabled=1','id <' => 9, 'id<> 4' ), 'fields' => array('id', 'ten_loaivanban')));

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
			if($this->request->data['Vanban']['chieu_di'] == 1)//văn bản đến

				$this->request->data['Vanban']['tinhtrang_duyet'] = 0;
			else
				$this->request->data['Vanban']['tinhtrang_duyet'] = 1;

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



	public	function	view($id, $id_kqvb = null)

	{

		$nv = $this->Auth->user('nhanvien_id');

		//pr($nv);die();

		$this->loadModel('Nhanvanban');

		$check = false;

		$conds = array();

			$conds["OR"]	= array(

							  "Vanban.nguoi_trinh_id"	=>	$this->Auth->user('nhanvien_id') ,

								  "Vanban.nguoi_duyet_id"		=>	$this->Auth->user('nhanvien_id')
								  );	

		//pr($conds);die();

		if(!$this->check_permission('VanBan.quanly'))	// nếu ko có quyền quản lý văn bản

		{

			if($this->check_permission('VanBan.sua'))	// nếu có quyền sửa văn bản đã gửi

			{

				$check = $this->Vanban->find('first', 

					array('conditions' => array('Vanban.id' => $id, 'Vanban.nguoi_nhap_id' => $this->Auth->user('nhanvien_id')),

					  'fields' => 'Vanban.id'));	// người nhập văn bản

			}

			if(!$check && $this->check_permission('VanBan.doc'))	// chỉ đọc văn bản gửi cho mình

				$check = $this->Nhanvanban->find('first', 

					array('conditions' => array('Nhanvanban.vanban_id' => $id, 'Nhanvanban.nguoi_nhan_id' => $this->Auth->user('nhanvien_id')),

					  'fields' => 'Nhanvanban.id'));

			//người trình, người duyệt, người nhập

			$vanban_den = $this->Vanban->query("

												SELECT * 

												FROM vanban_thongtin Vanban

												WHERE Vanban.id = ".$id."

												AND (Vanban.nguoi_trinh_id = ".$this->Auth->user('nhanvien_id')."

														OR Vanban.nguoi_duyet_id = ".$this->Auth->user('nhanvien_id')."
														OR Vanban.nguoi_nhap_id = ".$this->Auth->user('nhanvien_id').")

												");

			if(!empty($vanban_den))

				$check = true;			

		}else

			$check = true;

		
		if(!$check)

			throw new InternalErrorException('Bạn không được phép xem văn bản này. Vui lòng chọn văn bản khác.');

		$this->Vanban->bindModel(array(

			'belongsTo'	=>	array(

				'Loaivanban', 'Tinhchatvanban'

			),

			'hasOne'	=>	array('Theodoivanban' => array('foreignKey' => 'vanban_id')//,

									//'Ketquavanban' => array(

										//		'foreignKey' => 'vanban_id', 

											//	'conditions' => array('Ketquavanban.id' => $id_kqvb))

												),

			'hasMany'	=>	array(

				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),

				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),

				'Xulyvanban'	=>	array('foreignKey'	=>	'vanban_id'),

				//'Ketquavanban'	=>	array('foreignKey'	=>	'vanban_id'),

			)

		));

		/*$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_capnhat_id'),
							),
		), false);*/
		$this->Vanban->recursive = 2;	

		$data = $this->Vanban->read(null, $id);
		//pr($data);die();
		$this->loadModel('Ketquavanban');
		$this->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_capnhat_id'),
							),
		), false);
		$kq_vanban  = $this->Ketquavanban->find('all', array('conditions' => array('Ketquavanban.vanban_id' => $id)));
		if(empty($data))
			throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng chọn văn bản khác.');
		$this->loadModel('Phong');
		$phong_chutri = $this->Phong->field('ten_phong', array('id' => $data['Vanban']['phongchutri_id']));
		$arr_dsphong = array();
		if ($data['Vanban']['phongphoihop_id'] <> '')
			$arr_phongph = explode(',',$data['Vanban']['phongphoihop_id']);
		if(!empty($arr_phongph)) 
			foreach($arr_phongph as $i_phongph)
			{
				$phong_phoihop = $this->Phong->field('ten_phong', array('id' => $i_phongph));
				array_push($arr_dsphong,$phong_phoihop);
			}
		$phong_nhan = $this->Nhanvanban->query("SELECT DISTINCT Phong.ten_phong as ten_phong FROM vanban_nhan A, nhansu_nhanvien B, nhansu_phong Phong WHERE A.nguoi_nhan_id = B.id AND B.phong_id = Phong.id AND A.vanban_id=" . $data['Vanban']['id'] . " ORDER BY Phong.lft ASC");

		if(is_numeric($id)){

			$this->Vanban->query("UPDATE vanban_nhan SET ngay_xem='" . date("Y-m-d H:i:s") . "' WHERE vanban_id=" . $id . " AND nguoi_nhan_id=" . $this->Auth->user('nhanvien_id') . " AND ngay_xem IS NULL");
			$this->Vanban->query("UPDATE vanban_nhan SET ngayxem_chidao='" . date("Y-m-d H:i:s") . "' WHERE vanban_id=" . $id . " AND nguoi_nhan_id=" . $this->Auth->user('nhanvien_id') . " AND ngayxem_chidao IS NULL");
			//Lưu log view
			$t = array();
			$t['id'] = NULL;
			$t['tinnhan_id'] = NULL;
			$t['vanban_id'] = $id;
			$t['action'] = 'view văn bản';
			$t['date'] = date('Y-m-d H:i:s', time());
			$t['user_name'] = $this->Auth->user('username');
			//pr($t['user_name']);die();
			$this->loadModel('Logview');
			if(!$this->Logview->save($t))
				{
					break;
				}
			}

		else

			throw new InternalErrorException();

		$this->data = $data;

		$this->Nhanvanban->bindModel(array('belongsTo' => array('Nhanvien' => array('foreignKey' => 'nguoi_nhan_id', 'fields' => array('full_name', 'nguoi_quanly'), 'order' => array('ten' => 'ASC', 'ten_lot' => 'ASC', 'ho' => 'ASC')))));

		$nguoi_nhan = $this->Nhanvanban->find('all', array('conditions' => array('vanban_id' => $data['Vanban']['id'])));

		$vanban = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id, 'Vanban.nguoi_nhap_id' => $nv )));

		//pr($vanban);die();

		//if(empty($vanban))

		//{

			$id_nguoinhan = array();

			foreach($nguoi_nhan as $item):

				$t = $item['Nhanvanban']['nguoi_nhan_id'];

				array_push($id_nguoinhan, $t);

				

			endforeach;

			//array_push($id_nguoinhan, '283');

			//pr($id_nguoinhan);die();

			$f = in_array($nv,$id_nguoinhan);

			//pr($f);die();

			//Xử lý những trường hợp văn bản đến

			//$conds = array();

			//$conds["OR"]	= array(

							 // "Vanban.nguoi_trinh_id"	=>	$this->Auth->user('nhanvien_id') ,

								//  "Vanban.nguoi_duyet_id"		=>	$this->Auth->user('nhanvien_id')

								  //);	

			//pr($conds);die();

			//$vanban_den = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id, 'Vanban.nguoi_nhap_id' => $nv )));

			$vanban_den = $this->Vanban->query("

												SELECT * 

												FROM vanban_thongtin Vanban, vanban_nhan Nhanvanban

												WHERE Vanban.id = ".$id."

												AND (Vanban.nguoi_trinh_id = ".$this->Auth->user('nhanvien_id')."
														OR Vanban.nguoi_duyet_id = ".$this->Auth->user('nhanvien_id')."
														OR Nhanvanban.nguoi_nhan_id = ".$this->Auth->user('nhanvien_id')."
														OR Vanban.nguoi_nhap_id = ".$this->Auth->user('nhanvien_id').")
												AND Vanban.id = Nhanvanban.vanban_id
												");

			//PR($vanban_den);die();

			if(!empty($vanban_den))

				$f = true;

			if($f == false)

				throw new InternalErrorException('Bạn không được phép xem văn bản này. Vui lòng chọn văn bản khác.');

		//}

		$this->loadModel('Congviec');
		$check = $this->Congviec->find('first', array('conditions' => array('Congviec.vanban_id' => $id, 'Congviec.nguoi_giaoviec_id' => $this->Auth->user('nhanvien_id'))));
	
		$is_mobile = $this->RequestHandler->isMobile();
		$this->loadModel('Nhanvien');
		if(!empty($vanban_den)) 
		{
			if(empty($vanban_den[0]['Vanban']['nguoi_duyet_id']))
				$thuoc_phong = $this->Nhanvien->query("
											select b.thuoc_phong 
											from nhansu_nhanvien a, nhansu_phong b 
											where a.id = ".$vanban_den[0]['Vanban']['nguoi_nhap_id']."
											and a.phong_id = b.id"); 	 
			else
				$thuoc_phong = $this->Nhanvien->query("
											select b.thuoc_phong 
											from nhansu_nhanvien a, nhansu_phong b 
											where a.id = ".$vanban_den[0]['Vanban']['nguoi_duyet_id']."
											and a.phong_id = b.id");
		}
		//pr($thuoc_phong);die();
		if(!empty($thuoc_phong[0]['b']['thuoc_phong'])) // VB đến VTĐN
			$nguoi_duyet = $this->Nhanvien->query("
										select a.id from nhansu_nhanvien a, nhansu_phong b where a.phong_id = b.id and a.chucdanh_id in (30,31) and a.tinh_trang = 1
									");
		else  	// VB đến Trung tâm
			$nguoi_duyet = $this->Nhanvien->query("
										select a.id from nhansu_nhanvien a, nhansu_phong b where a.phong_id = b.id and a.chucdanh_id in (2,3) and a.tinh_trang = 1
									");
		$arr_nguoiduyet = array();
		foreach($nguoi_duyet as $k => $v)
		{
			$nv = $v['a']['id'];
			array_push($arr_nguoiduyet,$nv); 
			
		}
		$this->set(compact('nv','phong_nhan', 'nguoi_nhan', 'is_mobile','check','phong_chutri','arr_dsphong','arr_nguoiduyet','kq_vanban'));
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

		//Lưu log view
		/*$t = array();
		$t['id'] = NULL;
		$t['vanban_id'] = $vanban['Vanban']['id'];
		$t['tinnhan_id'] = NULL;
		$t['action'] = 'view văn bản';
		$t['date'] = date('Y-m-d H:i:s', time());
		$t['user_name'] = $this->Auth->user('username');
		$this->loadModel('Logview');
		if(!$this->Logview->save($t))
			{
				break;
 			}*/
		

		
		$this->loadModel('Filevanban');

		//$data = $this->Filevanban->find('first', array('conditions' => array('Filevanban.id' => $id)));

		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));

		$path = substr($path, 1, strlen($path)-1);

		$path = WWW_ROOT . $path;

		$file_moi = $vanban['Filevanban']['ten_moi'];

		$file_cu = $vanban['Filevanban']['ten_cu'];
		////
		$arr = explode(".", $file_cu);
		$ext = end($arr);
		$ten_file = $this->Bin->slug($van_ban['Vanban']['so_hieu'].'-'.$van_ban['Vanban']['trich_yeu']).'.'.$ext;
		////

		$file_contents = file_get_contents($path.$file_moi, true);

		$this->layout = null;

		Configure::write('debug',0);

		if($this->Auth->User('nhanvien_id') == 285){ // Đỗ Trọng Cường
				header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $ten_file .'"');
		}
		else {
			header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $file_cu .'"');
		}

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

			if(empty($van_ban))

				throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');
			//Lưu log view
			/*$t = array();
			$t['id'] = NULL;
			$t['vanban_id'] = $van_ban['Vanban']['id'];
			$t['tinnhan_id'] = NULL;
			$t['action'] = 'view văn bản';
			$t['date'] = date('Y-m-d H:i:s', time());
			$t['user_name'] = $this->Session->read('Auth');
			echo var_dump($t['user_name']);die();
			$this->loadModel('Logview');
			if(!$this->Logview->save($t))
				{
					break;
				}*/
			
			$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));

			$path = substr($path, 1, strlen($path)-1);

			$path = WWW_ROOT . $path;

			$file_moi = $van_ban['Filevanban']['ten_moi'];

			$file_cu = $van_ban['Filevanban']['ten_cu'];
			
			///////////
			$arr = explode(".", $file_cu);
			$ext = end($arr);
			$ten_file = $this->Bin->slug($van_ban['Vanban']['so_hieu'].'-'.$van_ban['Vanban']['trich_yeu']).'.'.$ext;

			if(!file_exists($path.$file_moi))
				die('Not found');

			$this->layout = null;
			Configure::write('debug',0);
			//echo var_dump($this->Auth->User('nhanvien_id'));die();
			if($this->Auth->User('nhanvien_id') == 285){ // Đỗ Trọng Cường
				header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $ten_file .'"');
			}
			else {
				header('Content-Description: File Transfer');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'. $file_cu .'"');
			}
			
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

		

		//$conds = array();	

	

		//if ($this->Auth->user('nhanvien_id') <> 283)

		//{

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
			$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
  						    'Vanban.tinhtrang_duyet' => 1
							);
			//$conds['Nhanvanban.nguoi_nhan_id'] = $this->Auth->user('nhanvien_id');	

		//}

		//pr($conds);die();

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
			if(!empty($this->request->data['Vanban']['nguoi_duyet']))

			{

				$this->passedArgs['nguoi_duyet'] = $this->data['Vanban']['nguoi_duyet'];

				$conds[] = array("Vanban.nguoi_duyet LIKE"	=>	"%" . $this->request->data['Vanban']['nguoi_duyet'] . "%");

			}
			if(($this->request->data['Vanban']['uy_quyen']) != '')
			{
				$this->passedArgs['uy_quyen'] = $this->data['Vanban']['uy_quyen'];
				$conds["Vanban.uy_quyen"] = $this->request->data['Vanban']['uy_quyen'];
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

			if(isset($this->passedArgs['nguoi_duyet']))

			{

				$conds[] = array("Vanban.nguoi_duyet LIKE"	=>	"%" . $this->passedArgs['nguoi_duyet'] . "%");

			}

			/*if(isset($this->passedArgs['uy_quyen']))

			{

				$conds["Vanban.uy_quyen"] = $this->passedArgs['uy_quyen'];

			}*/
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

			$uy_quyen	= array("0"	=>	'Không ủy quyền',
								"1"	=>	'Ủy quyền');
			$this->set(compact('tinhchat', 'loaivanban', 'chieu_di','uy_quyen'));
			//$this->set(compact('tinhchat', 'loaivanban', 'chieu_di'));

		}else

		{

			$this->viewPath = 'Elements' . DS . 'Common';

			$this->render('vanban_search');

		}

	}
	
	public	function	search_kq()

	{
		if(!$this->check_permission('VanBan.tracuu_donvi'))

			throw new InternalErrorException('Bạn không có quyền tra cứu văn bản theo đơn vị chủ trì. Vui lòng liên hệ quản trị để biết thêm chi tiết.');	
		$this->loadModel('Nhanvanban');
		$this->Vanban->bindModel(
				array(
					'hasOne'	=>	array('Nhanvanban' => array('foreignKey'	=>	'vanban_id',
										  						'className'	=>	'Nhanvanban')
											)
				), false);
		$this->Vanban->bindModel(array(
			'belongsTo'	=>	array('Phong' => array('foreignKey' => 'phongchutri_id')
    								
							),
		), false);
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array('Phongphoihop' => array('foreignKey' => 'vanban_id')
			
							)
		), false);
		/*$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanviencapnhat' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_capnhat_id'),
    								'Nhanviennhan' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_nhan_id'),
									'Filebaocao' => array('className'	=>	'Filebaocao','foreignKey' => 'filesbc_id')
							),
		), false);*/
		$this->Vanban->Phongphoihop->bindModel(array(
			'belongsTo'	=>	array('DSPhong' => array('className'	=>	'Phong','foreignKey' => 'phongphoihop_id')
    								
							),
		), false);
		
		$conds = array();	
		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),
						'Vanban.tinhtrang_duyet' => 1,
						'Vanban.phongchutri_id <>' => NULL);
		if(!empty($this->request->data))
		{
			//pr($this->Auth->User());die();
			/*if($this->request->data['Vanban']['donviduyet_id'] != 14)//ko phải VTĐN
			{
				if($this->Auth->User('donvi_id') != '') // đơn vị
				{
					if ($this->Auth->User('donvi_id') <> $this->request->data['Vanban']['donviduyet_id'])
			  			throw new InternalErrorException('Bạn không có quyền tra cứu kết quả xử lý văn bản của đơn vị này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
				}
				else // phòng ban
				{
					if ($this->Auth->User('phong_id') <> $this->request->data['Vanban']['donviduyet_id'])
			  			throw new InternalErrorException('Bạn không có quyền tra cứu kết quả xử lý văn bản của đơn vị này. Vui lòng liên hệ quản trị để biết thêm chi tiết.');
				}
			}*/
			if(!empty($this->request->data['Vanban']['phongchutri_id']))
			{
					$this->passedArgs['phongchutri_id'] = $this->data['Vanban']['phongchutri_id'];
					$conds["Vanban.phongchutri_id"] = $this->request->data['Vanban']['phongchutri_id'];
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
			if(isset($this->passedArgs['phongchutri_id']))
			{
				$conds["Vanban.phongchutri_id"] = $this->passedArgs['phongchutri_id'];
			}
			if( isset($this->passedArgs['ngay_batdau']) 

						&& isset($this->passedArgs['ngay_ketthuc']) )
			{
				$conds['Vanban.ngay_phathanh BETWEEN ? AND ?'] = array( $this->passedArgs['ngay_batdau'],  $this->passedArgs['ngay_ketthuc'] );
			}
		}
		$this->Vanban->recursive = 2;
		//pr($conds);
		$ds =  $this->paginate('Vanban', $conds);
		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không tìm thấy văn bản nào.', 'flash_attention');
		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

		if(!$this->RequestHandler->isAjax())

		{

			$this->set('title_for_layout', 'Tra cứu văn bản theo đơn vị chủ trì');
			//$loaivanban = $this->Loaivanban->find('list', array('conditions' => 'enabled=1', 'fields' => array('id', 'ten_loaivanban')));
			$this->loadModel('Phong');
			$dsphong = $this->Phong->generateTreeList($conditions=array('Phong.thuoc_phong'=>NULL,'Phong.id <>' =>14), null, '{n}.Phong.ten_phong', '---');
			$this->set(compact('dsphong'));

		}else

		{

			$this->viewPath = 'Elements' . DS . 'Common';

			$this->render('kqvanban_search');

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
			if(!empty($this->request->data['Vanban']['nguoi_duyet']))

			{

				$this->passedArgs['nguoi_duyet'] = $this->data['Vanban']['nguoi_duyet'];

				$conds[] = array("Vanban.nguoi_duyet LIKE"	=>	"%" . $this->request->data['Vanban']['nguoi_duyet'] . "%");

			}
			if(($this->request->data['Vanban']['uy_quyen']) != '')
			{
				$this->passedArgs['uy_quyen'] = $this->data['Vanban']['uy_quyen'];
				$conds["Vanban.uy_quyen"] = $this->request->data['Vanban']['uy_quyen'];
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

			if(isset($this->passedArgs['nguoi_ky']))

			{

				$conds[] = array("Vanban.nguoi_ky LIKE"	=>	"%" . $this->passedArgs['nguoi_ky'] . "%");

			}
			if(isset($this->passedArgs['nguoi_duyet']))
			{
				$conds[] = array("Vanban.nguoi_duyet LIKE"	=>	"%" . $this->passedArgs['nguoi_duyet'] . "%");
			}
			if(isset($this->passedArgs['uy_quyen']))
			{
				$conds["Vanban.uy_quyen"] = $this->passedArgs['uy_quyen'];
			}
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
	////////////////////
	
	public	function	excel_search_sua()

	{
		$this->layout = null;
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
		$ds =  $this->Vanban->find('all', array('conditions' => $conds));
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
			array('label' => __('Số đến'), 'filter' => true),
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
					$d['Vanban']['so_hieu_den'],
					$d['Vanban']['nguoi_ky'],
					$d['Vanban']['ngay_gui'],
					$d['Vanban']['ngay_nhap'],
					$d['Vanban']['trich_yeu'],

				));

			endforeach;
		endif;
		// close table and output
		$this->PhpExcel->addTableFooter();
		$this->PhpExcel->output('dsvanban_sua.xlsx');
	}
	////////////////////


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

				$this->redirect('/vanban/' . $this->request->data['Vanban']['type']);

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

	

	/////////////

	function	download_files($id = null) 

	{

		$this->Vanban->bindModel(array(

			'hasOne'	=>	array('Filevanban' => array('foreignKey' => 'vanban_id'),

							'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')

							),

		), false);

		$vanban = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));

		/*if (empty($vanban))

			$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv )));	*/		

		//pr($vanban);die();

		/*if(empty($vanban))

			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');*/

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

	/////////////

	public	function	chotrinh()//văn bản đến chờ trình (Trưởng VPTH)

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



		$conds = array( 'Nhanvanban.nguoi_nhan_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	1,//văn bản đến

						'Vanban.tinhtrang_duyet'	=> 	0

						);

		//pr($conds);

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

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

	}

	

	public	function 	trinh_vanban($id = null)//trình văn bản đến (Trưởng VPTH)
	{
		if(!$this->check_permission('VanBan.trinh'))
			throw new InternalErrorException('Bạn không có quyền trình văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Trình văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id')
			)
		));
		$this->loadModel('Nhanvien');
		$this->Nhanvien->bindModel(array(
				'belongsTo' => array(
					'User' =>	array('fields' => array()),
					'Chucdanh' => array('fields' => array()),
					'Phong' => array('fields' => array())
				)
			), false);
		$this->Nhanvien->recursive = 0;
		if(!empty($this->request->data))
		{
			//pr($this->request->data);die();
			$noidung_trinh = $this->request->data['Vanban']['noidung_trinh'];
			$nguoi_duyet_id = $this->request->data['Vanban']['nguoi_duyet_id'];
			$nguoi_trinh_id = $this->Auth->user('nhanvien_id');
			$phongchutri_id = $this->request->data['Vanban']['phongchutri_id'];
			$chuyen_bypass = isset($this->request->data['Vanban']['chuyen_bypass']) ? 1 : 0;
			$uy_quyen = isset($this->request->data['Vanban']['uy_quyen']) ? 1 : 0;

			//Đính kèm file Trình văn bản(CVP->GĐ)

			if(!empty($this->request->data['Filevanban']))

			{

				$t['id'] = NULL;

				$t['vanban_id'] = $id;

				//files đính kèm khi trình văn bản

				$old = str_replace("/", DS, Configure::read('File.tmp'));

				$old = substr($old, 1, strlen($old)-1);

				$new = str_replace("/", DS, Configure::read('VanBan.attach_path'));

				$new = substr($new, 1, strlen($new)-1);

				//pr($new);die();

				foreach($this->request->data['Filevanban'] as $item)

				{

					$t['ten_cu'] = $item['ten_cu'];

					$t['ten_moi'] = $item['ten_moi'];

					//pr(WWW_ROOT . $new . $item['ten_moi']);die();

					if(copy(WWW_ROOT . $old . $item['ten_moi'],  WWW_ROOT . $new . $item['ten_moi']))

						unlink(WWW_ROOT . $old . $item['ten_moi']);

					$this->loadModel('Filevanbanduyet');

					if (!$this->Filevanbanduyet->save($t)) 

					{

						break;

					}

				}

			}

			if ($chuyen_bypass == 0) // ko phải văn bản mang tính chất thông báo 
			{
				$ds = $this->request->data['Vanban']['nv_selected'];
				$nguoinhan = explode(",", $ds);
				//Văn bản mạc định được gửi đến người duyệt
				$arr_nguoiduyet = array($nguoi_duyet_id);
				if(in_array($arr_nguoiduyet[0],$nguoinhan) == false	)
					array_push($nguoinhan,$arr_nguoiduyet[0]);
				$this->loadModel('Nhanvanban');
				$this->request->data['Nhanvanban'] = array();
				if(!empty($nguoinhan))
				{
					foreach($nguoinhan as $n)
					{
						array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
					}
				}
				///////////
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id NOT IN(14,19,52,53,69,70,71,77,83)'))); // BGĐ VT và các TT	
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '') // VT
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else // các đơn vị
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
							array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
							$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
				}
				//pr($phongchutri_id);
				//pr($phong_phoihop);
				//pr( ltrim($phong_phoihop_id,','));die();
				///////////
				
				$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_duyet_id),'fields' => array('User.fullname')));

				$nguoi_duyet = $nguoi_duyet['User']['fullname'];

				$dataSource = $this->Vanban->getDataSource();

				$dataSource->begin();

				if($this->Vanban->updateAll(

							array('tinhtrang_duyet' => 2, 'noidung_trinh' => "'" . $noidung_trinh . "'", 'nguoi_duyet_id' => $nguoi_duyet_id, 'nguoi_duyet' => "'" . $nguoi_duyet . "'",'nguoi_trinh_id' => "'" . $nguoi_trinh_id . "'",'chuyen_bypass' => 0,'uy_quyen' => $uy_quyen, 'phongchutri_id' => "'" . $phongchutri_id ."'",'phongphoihop_id' => "'" . ltrim($phong_phoihop_id,',') ."'" ), 

							array('Vanban.id' 	=> $id )

							)

						)

				{
					$f = true;
					// save vanban_phongphoihop
					$ds_phongphoihop = ltrim($phong_phoihop_id,',');
					//$arr_phongph = array();
					$arr_phongph = explode(",", $ds_phongphoihop);
					//pr($arr_phongph);die();
					$this->loadModel('Phongphoihop');
					foreach($arr_phongph as $k => $v)
						{
							$p['id'] = NULL;
							$p['vanban_id'] = $id;
							$p['phongphoihop_id'] = $v;
							if(!$this->Phongphoihop->save($p))
							{
								$f = false; break;
							}
						}
					// save nguoi nhan

					$old = $this->Nhanvanban->find('list', array('conditions' => 'vanban_id=' . $id, 'fields' => array('nguoi_nhan_id')));

					//insert

					if($f)

						foreach($nguoinhan as $k => $v)

						{

							$t['id'] = NULL;

							$t['vanban_id'] = $id;

							$t['nguoi_nhan_id'] = $v;

							if(!$this->Nhanvanban->save($t))

							{

								$f = false;	break;

							}

						}

					//delete

					if($f)

						foreach($old as $k=>$v)

						{

							if(!$this->Nhanvanban->delete($k))

							{

								$f = false;	break;

							}

						}

					if($f)

					{

						$dataSource->commit();

						$this->Session->setFlash('Văn bản đã trình thành công.', 'flash_success');

						$this->redirect('/vanban/index_vanbanden#vanban-chotrinh');

					}

					else

					{

						$dataSource->rollback();

						$this->Session->setFlash('Đã phát sinh lỗi trong khi trình Văn bản. Vui lòng thử lại.', 'flash_error');

					}

				} else {

					$this->Session->setFlash('Đã phát sinh lỗi trong khi trình Văn bản. Vui lòng thử lại.', 'flash_error');

					$this->redirect('/vanban/index_vanbanden#vanban-chotrinh');

				}

			}

			else // văn bản mang tính chất thông báo
			{
				$ds = $this->request->data['Vanban']['nv_selected'];
				$nguoinhan = explode(",", $ds);
				//Văn bản mạc định được gửi đến GĐ
				$lanh_dao = array('681');
				if(in_array($lanh_dao[0],$nguoinhan) == false)
					array_push($nguoinhan,$lanh_dao[0]);
				$this->loadModel('Nhanvanban');
				$this->request->data['Nhanvanban'] = array();
				if(!empty($nguoinhan))
				{
					foreach($nguoinhan as $n)
					{
						array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
					}
				}
				///////////
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//xem lại chổ ni thử chứ viết dài dòng quá!
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					//xem lại chổ này
					if($this->Auth->User('donvi_id') == '')//Viến thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
						}
				}
				///////////
				$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_trinh_id),'fields' => array('User.fullname')));

				$nguoi_duyet = $nguoi_duyet['User']['fullname'];
				$dataSource = $this->Vanban->getDataSource();

				$dataSource->begin();

				if($this->Vanban->updateAll(

							array('tinhtrang_duyet' => 1, 'noidung_trinh' => "'" . $noidung_trinh . "'", 'noidung_duyet' => "'" . $noidung_trinh . "'",'nguoi_trinh_id' => "'". $nguoi_trinh_id . "'",'nguoi_duyet_id' => "'". $nguoi_trinh_id . "'" ,'nguoi_duyet' => "'". $nguoi_duyet . "'" ,'chuyen_bypass' => 1,'phongchutri_id' => "'" . $phongchutri_id ."'",'phongphoihop_id' => "'" . ltrim($phong_phoihop_id,',') ."'" ), 
							array('Vanban.id' 	=> $id )
							)
						)
				{
					$f = true;

					// save nguoi nhan

					$old = $this->Nhanvanban->find('list', array('conditions' => 'vanban_id=' . $id, 'fields' => array('nguoi_nhan_id')));

					//pr($nguoinhan); die();

					//insert

					if($f)

						

						foreach($nguoinhan as $k => $v)

						{

							$t['id'] = NULL;

							$t['vanban_id'] = $id;

							$t['nguoi_nhan_id'] = $v;

							//pr($t['vanban_id']);die();

							if(!$this->Nhanvanban->save($t))

							{

								$f = false;	break;

							}

						}

					//delete

					if($f)

						foreach($old as $k=>$v)

						{

							if(!$this->Nhanvanban->delete($k))

							{

								$f = false;	break;

							}

						}

					if($f)

					{

						$dataSource->commit();

						$this->Session->setFlash('Văn bản đã trình thành công.', 'flash_success');

						$this->redirect('/vanban/index_vanbanden#vanban-chotrinh');

					}

					else

					{

						$dataSource->rollback();

						$this->Session->setFlash('Đã phát sinh lỗi trong khi trình Văn bản. Vui lòng thử lại.', 'flash_error');

					}

				} else 

				{

					$this->Session->setFlash('Đã phát sinh lỗi trong khi trình Văn bản. Vui lòng thử lại.', 'flash_error');

					$this->redirect('/vanban/index_vanbanden#vanban-chotrinh');

				}				

			}

			

        }else // show form
		{
			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			//pr($data);die();
			if(empty($data))
				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');
			
			/*if($data['Vanban']['nguoi_trinh_id'] != $this->Auth->user('nhanvien_id'))

				throw new InternalErrorException('Bạn không có quyền trình văn bản này. Vui lòng thử lại.');	
*/
			$this->data = $data;
			$this->loadModel('Phong');
			if($this->Auth->User('donvi_id') == '')
				$phong_chutri = $this->Phong->find('list', array('conditions' => array('enabled=1','thuoc_phong =' => NULL, 'id <>' => 14), 'fields' => array('id', 'ten_phong'),'order' => array('loai_donvi, id ASC')));
			else
				$phong_chutri = $this->Phong->find('list', array('conditions' => array('enabled=1','thuoc_phong =' => $this->Auth->User('donvi_id'), 'Phong.id NOT IN (14,19,52,53,69,70,71,77,83)'), 'fields' => array('id', 'ten_phong'),'order' => array('loai_donvi, id ASC')));
			$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id <> 784','nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.id ASC' ),));
			//pr($nhanviennhan);die();
			$nhanviennhan = Set::combine($nhanviennhan, '{n}.Nhanvien.id', '{n}.User.fullname');
			$is_mobile = $this->RequestHandler->isMobile();
			//files văn bản
			$this->loadModel('Filevanban');
			$files = $this->Filevanban->find('all', array('conditions' => array('Filevanban.vanban_id' => $id )));
			//pr($files);die();
			$this->set(compact('nhanviennhan','phong_chutri','files','is_mobile'));
			//$this->set(compact('nhanviennhan', 'phong_chutri'));

		} 

	}

	

	public	function	vbden_datrinh()//văn bản đến đã trình (Chánh VP)

	{
		if($this->Auth->User('nhanvien_id') == 683 || $this->Auth->User('nhanvien_id') == 744)//Chị Thu, Anh Dũng
		{
			$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_trinh_id','fields' => 'phong_id'),
											),
										), false);
										
			$this->Vanban->Nhanvien->unbindModel(array(
											'hasAndBelongsToMany' => array('Nhomquyen'),
											'belongsTo' => array('User')
											), false);																			
			$this->loadModel('Phong');
			//$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => 2, 'enabled' => 1), 'fields' => array('id', 'id')));
			$ds_phong = $this->Phong->find('list', array('conditions' => array('id' => 20, 'enabled' => 1), 'fields' => array('id', 'id')));	
			//pr($ds_phong);die();			 						
			$this->Vanban->recursive = 2;
			$conds = array( 'Nhanvien.phong_id'		=> $ds_phong,
							'Vanban.tinhtrang_duyet <>' => 0,
						    'Vanban.chieu_di'			=>	1//văn bản đến

						);
			//pr($conds);die();
			
		}
		else
		{
		
			$conds = array( 'Vanban.nguoi_trinh_id'	=>	$this->Auth->user('nhanvien_id'),
							'Vanban.tinhtrang_duyet <>' => 0,
							'Vanban.chieu_di'			=>	1//văn bản đến
	
							);
	
		}

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

		//$this->Nhanvanban->recursive = 2;

		$ds =  $this->paginate('Vanban', $conds);

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

	}

	

	function	edit_vanbantrinh($id = null)
	{
		if(!$this->check_permission('VanBan.suavbtrinh'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Chỉnh sửa văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Filevanbanduyet'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Ketquavanban'	=>	array('foreignKey' => 'vanban_id'),
			)
		));
		$this->loadModel('Nhanvien');
		$this->Nhanvien->bindModel(array(
				'belongsTo' => array(
					'User' =>	array('fields' => array()),
					'Chucdanh' => array('fields' => array()),
					'Phong' => array('fields' => array())
				)
			), false);
		$this->Nhanvien->recursive = 0;
		if(!empty($this->request->data))
		{
			$noidung_trinh = $this->request->data['Vanban']['noidung_trinh'];
			$nguoi_duyet_id = $this->request->data['Vanban']['nguoi_duyet_id'];
			$phongchutri_id = $this->request->data['Vanban']['phongchutri_id'];
			$chuyen_bypass = $this->request->data['Vanban']['chuyen_bypass'];
			$uy_quyen = $this->request->data['Vanban']['uy_quyen'];
			if($chuyen_bypass == 0)
			{
				$tinhtrang_duyet = 2;
				$noidung_duyet = "";
			}
			else // VB đến mang tính chất thông báo
			{	
				$tinhtrang_duyet = 1;
				$noidung_duyet = $noidung_trinh;
			}
			$ds = $this->request->data['Vanban']['nv_selected'];
			$nguoinhan = explode(",", $ds);
			$c = in_array($nguoi_duyet_id,$nguoinhan);
			//Văn bản mạc định được gửi đến người duyệt
			if(!$c)
				array_push($nguoinhan,$nguoi_duyet_id);
			$this->loadModel('Nhanvanban');
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}

			$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_duyet_id),'fields' => array('User.fullname')));
			//Danh sách phòng chủ trì
			///////////
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id NOT IN(14,19,52,53,69,70,71,77,83)'))); // BGĐ VT và các TT	
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '') // VT
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else // các đơn vị
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
							array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
							$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
				}
			///////////
			$dataSource = $this->Vanban->getDataSource();
			$dataSource->begin();
            if ($this->Vanban->save($this->request->data)) 
			{
				$f = true;
				$this->Vanban->updateAll(
									array('noidung_trinh' => '"' .$noidung_trinh . '"',
										  'nguoi_duyet_id' => $nguoi_duyet_id,
										  'phongchutri_id' => $phongchutri_id,
										  'chuyen_bypass'	=> $chuyen_bypass,
										  'uy_quyen'	=> $uy_quyen,
										  'tinhtrang_duyet'	=> $tinhtrang_duyet,
										  'noidung_duyet'	=> '"' .$noidung_duyet . '"',
										  'phongphoihop_id' => '"'. ltrim($phong_phoihop_id,',') . '"',
										  'nguoi_duyet'		=> '"' .$nguoi_duyet['User']['fullname'] . '"',
										  'importance'		=> 0,
										  'vb_gap'			=> 0,
										  'vbgap_ngayhoanthanh'	=> '""'
										   ), 
									array('id' 	=> $id ));
				/*if($chuyen_bypass == 1)
					$this->Vanban->updateAll(
									array('noidung_trinh' => '"' .$noidung_trinh . '"',
										  'nguoi_duyet_id' => $nguoi_duyet_id,
										  'phongchutri_id' => $phongchutri_id,
										  'chuyen_bypass'	=> $chuyen_bypass,
										  'tinhtrang_duyet'	=> $tinhtrang_duyet,
										  'noidung_duyet'	=> '"' .$noidung_trinh . '"',
										  'phongphoihop_id' => '"'. ltrim($phong_phoihop_id,',') . '"',
										  'nguoi_duyet'		=> '"' .$nguoi_duyet['User']['fullname'] . '"',
										  'importance'		=> 0,
										  'vb_gap'			=> 0,
										  'vbgap_ngayhoanthanh'	=> '""'
										   ), 
									array('id' 	=> $id ));
				else
					$this->Vanban->updateAll(
									array('noidung_trinh' => '"' .$noidung_trinh . '"',
										  'nguoi_duyet_id' => $nguoi_duyet_id,
										  'phongchutri_id' => $phongchutri_id,
										  'chuyen_bypass'	=> $chuyen_bypass,
										  'tinhtrang_duyet'	=> $tinhtrang_duyet,
										  'noidung_duyet'	=> '""',
										  'phongphoihop_id' => '"'. ltrim($phong_phoihop_id,',') . '"',
										  'nguoi_duyet'		=> '"' .$nguoi_duyet['User']['fullname'] . '"',
										  'importance'		=> 0,
										  'vb_gap'			=> 0,
										  'vbgap_ngayhoanthanh'	=> '""'
										   ), 
									array('id' 	=> $id ));*/

				$ds_nguoinhan = $this->Nhanvanban->find('all', array('conditions' => array('vanban_id' => $id)));
				if(!empty($ds_nguoinhan))
					{
						foreach($ds_nguoinhan as $k => $v)
						{
							$this->Nhanvanban->updateAll(
													array('ngay_xem' => NULL ), 
													array('Nhanvanban.vanban_id' 	=> $id ));
						}
					}
				if(!empty($this->request->data['Filevanbanduyet']))
				{
					$this->loadModel('Filevanbanduyet');
					$old = $this->Filevanbanduyet->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));

					//pr($old);die();

					$new = $this->request->data['Filevanbanduyet'];

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

							if(!$this->Filevanbanduyet->delete($v))

							{

								$f = false;	break;

							}

						}

					}

					if($f)

					{

						// files đính kèm CVP gửi cho GĐ

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

								$t['ten_cu'] = $v['ten_cu'];

								$t['ten_moi']= $v['ten_moi'];

								if(!$this->Filevanbanduyet->save($t))

								{

									$f = false;	break;

								}

								if(copy(WWW_ROOT . $old . $v['ten_moi'],  WWW_ROOT . $new .$v['ten_moi']))

									unlink(WWW_ROOT . $old . $v['ten_moi']);

							}

					}

				}

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
				/////
				//save phòng phối hợp 
				$this->loadModel('Phongphoihop');
				$old_phoihop = $this->Phongphoihop->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
				//ins
				if($f)
				{
					$ds_phongphoihop = ltrim($phong_phoihop_id,',');
					$arr_phongph = explode(",", $ds_phongphoihop);
					foreach($arr_phongph as $k => $v)
						{
							$p['id'] = NULL;
							$p['vanban_id'] = $id;
							$p['phongphoihop_id'] = $v;
							if(!$this->Phongphoihop->save($p))
							{
								$f = false; break;
							}
						}
				}
				//del
				if($f)
				{
					foreach($old_phoihop as $k => $v)
					{
						if(!$this->Phongphoihop->delete($k))
						{
							$f = false;	break;
						}
					}
				}
				/////
				// Xem xét lại vấn đề này
				// lấy ds kết quả đã báo cáo kết quả xử lý theo văn bản
				$this->loadModel('Ketquavanban');
				$result = $this->Ketquavanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
				if($f)
					foreach($result as $k=>$v)
					{
						if(!$this->Ketquavanban->delete($k))
						{
							$f = false;	break;
						}
					}
				if($f)

				{
					$dataSource->commit();

					$this->Session->setFlash('Văn bản đã chỉnh sửa thành công.', 'flash_success');

					$this->redirect('/vanban/index_vanbanden#vanban-datrinh');

				}

				else

				{

					$dataSource->rollback();

					$this->Session->setFlash('Đã phát sinh lỗi trong khi Chỉnh sửa Văn bản. Vui lòng thử lại.', 'flash_error');

				}

			}

			else {

				$this->Session->setFlash('Đã phát sinh lỗi trong khi Chỉnh sửa Văn bản. Vui lòng thử lại.', 'flash_error');

				$this->redirect('/vanban/edit_vanbantrinh');

            }

			



		}else	// show edit form

		{

			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));

			//pr($data);die();

			if(empty($data))

				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');

			if(!$this->check_permission('VanBan.quanly')

					&& ($this->check_permission('VanBan.VanBan.suavbtrinh')

						&& $data['Vanban']['nguoi_trinh_id'] != $this->Auth->user('nhanvien_id'))

						)

				throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản này. Vui lòng thử lại.');	

			$this->loadModel('Phong');

			if($this->Auth->User('donvi_id') == '')
				$phong_chutri = $this->Phong->find('list', array('conditions' => array('enabled=1','thuoc_phong =' => NULL, 'id <>' => 14), 'fields' => array('id', 'ten_phong'),'order' => array('loai_donvi, id ASC')));
			else
				$phong_chutri = $this->Phong->find('list', array('conditions' => array('enabled=1','thuoc_phong =' => $this->Auth->User('donvi_id'), 'Phong.id NOT IN (14,19,52,53,69,70,71,77,83)'), 'fields' => array('id', 'ten_phong'),'order' => array('loai_donvi, id ASC'))); 
			$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id <> 784','nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			//pr($nhanviennhan);die();

			$nhanviennhan = Set::combine($nhanviennhan, '{n}.Nhanvien.id', '{n}.User.fullname');
			$is_mobile = $this->RequestHandler->isMobile();
			//files văn bản
			$this->loadModel('Filevanban');
			$files = $this->Filevanban->find('all', array('conditions' => array('Filevanban.vanban_id' => $id )));
			$this->set(compact('nhanviennhan','phong_chutri','files','is_mobile'));

			//$this->set(compact('nhanviennhan', 'phong_chutri'));

			$this->data = $data;

		}

	}

	

	public	function	thongbao() // VB đến mang tính chất thông báo(chuyển bypass = 1)

	{

		if(!$this->check_permission('VanBan.xemvbthongbao'))

			throw new InternalErrorException('Bạn không có quyền xem văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	

		$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhap_id','fields' => 'phong_id'),
											),
										), false);
										
		$this->Vanban->Nhanvien->unbindModel(array(
										'hasAndBelongsToMany' => array('Nhomquyen'),
										'belongsTo' => array('User')
										
										), false);																			
		$this->Vanban->recursive = 2;
		$this->loadModel('Phong');
		if($this->Auth->User('donvi_id') == '')
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => 2, 'enabled' => 1), 'fields' => array('id', 'id')));										
		}
		else
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('thuoc_phong' => $this->Auth->User('donvi_id'), 'enabled' => 1), 'fields' => array('id', 'id')));										
		}
		$conds = array( 'Vanban.tinhtrang_duyet'	=>	1,
							'Vanban.chieu_di'			=>	1,//văn bản đến
							'Vanban.chuyen_bypass'		=> 	1,
							'Nhanvien.phong_id'		=> $ds_phong
							);
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

		//$this->Vanban->recursive = 2;

		$ds =  $this->paginate('Vanban', $conds);

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$this->set(compact('ds'));

	}

	

	public	function	choduyet() // VB đến chờ duyệt (Giám đốc/Phó giám đốc)

	{

		$conds = array( 'Vanban.nguoi_duyet_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	1,

						'Vanban.tinhtrang_duyet'	=> 	2

						);

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

		//$this->Vanban->recursive = 2;

		$ds =  $this->paginate('Vanban', $conds);

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

	}

	

	function	duyet_vanban($id = null)//duyệt văn bản đến (Giám đốc/Phó giám đốc)

	{

		if(!$this->check_permission('VanBan.duyet'))
			throw new InternalErrorException('Bạn không có quyền duyệt văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	

		$this->set('title_for_layout', 'Duyệt văn bản');

		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Filevanbanduyet'	=>	array('foreignKey'	=>	'vanban_id'),
				'Theodoivanban'	=>	array('foreignKey'	=>	'vanban_id')

			)

		));

		$this->loadModel('Nhanvien');

		$this->Nhanvien->bindModel(array(

				'belongsTo' => array(

					'User' =>	array('fields' => array()),

					'Chucdanh' => array('fields' => array()),

					'Phong' => array('fields' => array())

				)

			), false);

		$this->Nhanvien->recursive = 0;
		
		if(!empty($this->request->data))
		{
			$chuyen_nguoiduyet = $this->request->data['Vanban']['chuyen_nguoiduyet'];
			$importance = $this->request->data['Vanban']['importance'];
			$vb_gap = $this->request->data['Vanban']['vb_gap'];
			if($vb_gap == 1 && !empty($this->request->data['Vanban']['vbgap_ngayhoanthanh'])){
				$vbgap_ngayhoanthanh =  $this->Bin->vn2sql($this->request->data['Vanban']['vbgap_ngayhoanthanh']);				
			}else{
				$vbgap_ngayhoanthanh = NULL;
			}
			//pr($this->request->data);die();
			$ds = $this->request->data['Vanban']['nv_selected'];
			$nguoinhan = explode(",", $ds);
			$this->loadModel('Nhanvanban');
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}
			$dataSource = $this->Vanban->getDataSource();
			$dataSource->begin();
           	$noidung_duyet = $this->request->data['Vanban']['noidung_duyet'];
			
			///////////
			$phongchutri_id = $this->request->data['Vanban']['phongchutri_id'];
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id NOT IN(14,19,52,53,69,70,71,77,83)'))); // BGĐ VT và các TT	
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '')//Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else
						{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
				}
				///////////
			$phongphoihop_id = ltrim($phong_phoihop_id,',');
			if($chuyen_nguoiduyet == 0) //ko chuyển cho PGĐ duyệt	
			{
				if($this->Auth->User('donvi_id') <> '') // các đơn vị
					$donviduyet_id = $this->Auth->User('donvi_id');
				else // phòng ban/vtđn
					$donviduyet_id = $this->Auth->User('phong_id');
				$up_date = $this->Vanban->updateAll(
						array('tinhtrang_duyet' => 1, 'noidung_duyet' => "'" . $noidung_duyet . "'", 'phongphoihop_id' => "'" . $phongphoihop_id . "'", 'importance' => $importance,'vb_gap' => $vb_gap, 'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'", 'donviduyet_id' => $donviduyet_id), 
						array('Vanban.id' 	=> $id )
						);	
			} else //chuyển cho PGĐ duyệt 
			{
				//lấy người duyệt mới
				$nguoi_duyet_id = $this->request->data['Vanban']['nguoi_duyet_id'];
				$arr_nguoiduyet = array($this->request->data['Vanban']['nguoi_duyet_id']);
				if(in_array($arr_nguoiduyet[0],$nguoinhan) == false)
					array_push($nguoinhan,$arr_nguoiduyet[0]); 
				$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_duyet_id),'fields' => array('User.fullname')));
				$nguoi_duyet = $nguoi_duyet['User']['fullname'];
				$up_date = $this->Vanban->updateAll(
							array('nguoi_duyet_id' => "'" . $nguoi_duyet_id . "'" , 'nguoi_duyet' => "'" . $nguoi_duyet . "'", 'noidung_duyet' => "'" . $noidung_duyet . "'", 'phongphoihop_id' => "'" . $phongphoihop_id . "'", 'importance' => $importance, 'chuyen_nguoiduyet' => $chuyen_nguoiduyet, 'vb_gap' => $vb_gap, 'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'"), 
							array('Vanban.id' => $id )
								);
			}
			//var_dump($up_date);die();
			if($up_date)				
			{
				$f = true;
				// save nguoi nhan
				$old = $this->Nhanvanban->find('list', array('conditions' => 'vanban_id=' . $id, 'fields' => array('nguoi_nhan_id')));
				//delete
				if($f)
					foreach($old as $k=>$v)
					{
						if(!$this->Nhanvanban->delete($k))
						{
							$f = false;	break;
						}
					}
				if($f)
					foreach($nguoinhan as $k => $v)
					{
						$t['id'] = NULL;
						$t['vanban_id'] = $id; 
						$t['nguoi_nhan_id'] = $v;
						if(!$this->Nhanvanban->save($t))
						{
							$f = false;	break;
						}
					}
				/////
				//save phòng phối hợp 
				$this->loadModel('Phongphoihop');
				$old_phoihop = $this->Phongphoihop->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
				//ins
				if($f)
				{
					//$ds_phongphoihop = ltrim($phong_phoihop_id,',');
					$arr_phongph = explode(",", $phongphoihop_id);
					foreach($arr_phongph as $k => $v)
						{
							$p['id'] = NULL;
							$p['vanban_id'] = $id;
							$p['phongphoihop_id'] = $v;
							if(!$this->Phongphoihop->save($p))
							{
								$f = false; break;
							}
						}
				}
				//del
				if($f)
				{
					foreach($old_phoihop as $k => $v)
					{
						if(!$this->Phongphoihop->delete($k))
						{
							$f = false;	break;
						}
					}
				}
				/////
				if($f)
				{
					$dataSource->commit();
					$this->Session->setFlash('Văn bản đã duyệt thành công.', 'flash_success');
					$this->redirect('/vanban/index_vanbanden#vanban-choduyet');
				}
				else
				{
					$dataSource->rollback();
					$this->Session->setFlash('Đã phát sinh lỗi trong khi duyệt Văn bản. Vui lòng thử lại.', 'flash_error');
				}
			} else 
			{
				$this->Session->setFlash('Đã phát sinh lỗi trong khi duyệt Văn bản. Vui lòng thử lại.', 'flash_error');
				$this->redirect('/vanban/index_vanbanden#vanban-choduyet');
			}
		}else	// show edit form

		{

			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			if(empty($data))

				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');

			if($data['Vanban']['nguoi_duyet_id'] != $this->Auth->user('nhanvien_id'))

				throw new InternalErrorException('Bạn không có quyền duyệt văn bản này. Vui lòng thử lại.');	

			$this->data = $data;
			$phongchutri_id = $data['Vanban']['phongchutri_id'];
			//lấy phòng chủ trì mới
			$pctnew_id = $this->Vanban->field('Vanban.phongchutri_id', array('Vanban.id' => $id));
			$this->loadModel('Phong');
			$loai_donvi = $this->Phong->field('Phong.loai_donvi', array('Phong.id' => $pctnew_id));
			if ($loai_donvi == 2 )//BGĐ, PBCN
			{
				//lấy theo chức danh(GĐVT, Chánh VP, Trưởng PBCN, GĐTT)
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
										from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c
										where a.id = '".$id."' 
										and a.phongchutri_id = b.phong_id
										and b.chucdanh_id = c.id
										and c.id in (2,16,30,35)
								");
				//lấy theo quyền(Văn thư PBCN, Văn thư)
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.id=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)
								"); 
			
			}
			else //Đơn vị trực thuộc
			{
				//lấy theo chức danh
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c, nhansu_phong d
								where a.id = '".$id."' 
								and a.phongchutri_id = d.thuoc_phong
								and b.phong_id = d.id
								and b.chucdanh_id = c.id
								and c.id in (2,16,30,35)
								");
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.thuoc_phong=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)	");
			}
			$this->loadModel('Phong');
			$phong_chutri = $this->Phong->field('ten_phong', array('Phong.id' => $data['Vanban']['phongchutri_id']));
			$this->set(compact('phong_chutri'));
			//Phong phoi hop
			$phong_phoihop = array();
			//if($data['Vanban']['phongphoihop_id']==NULL){
				if(!empty($data['Nhanvanban'])){	
					$arr_nvnvb = array();
					foreach($data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}	
			//}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));
				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));
				//pr($dsphong);die();
				$phong_phoihop_id = '';
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '') // Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							// Lấy phòng cha
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							//pr($id_phong);die();
							////
							if(in_array($ten_phong, $phong_phoihop)==false  && ($phongchutri_id != $itphong['Phong']['thuoc_phong'] )){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $id_phong.','.$phong_phoihop_id;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
							}
						}
					else //Đơn vị trực thuộc
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
								//pr($phong_phoihop);
								//pr($phong_phoihop_id);die();
						}
						
				}	
			}
			//pr($phong_phoihop); die();
			$this->set(compact('phong_phoihop','phong_phoihop_id','nv_chucdanh','nv_quyen','phongchutri_id'));
			if ($this->Auth->User('donvi_id') == '')
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id not in (784,681)','nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			else
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id <>' => $this->Auth->User('nhanvien_id'),'nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			$nhanviennhan = Set::combine($nhanviennhan, '{n}.Nhanvien.id', '{n}.User.fullname');
			$is_mobile = $this->RequestHandler->isMobile();
			//files văn bản
			$this->loadModel('Filevanban');
			$files = $this->Filevanban->find('all', array('conditions' => array('Filevanban.vanban_id' => $id )));
			$this->set(compact('nhanviennhan','files','is_mobile'));

		}

	}

	public	function	vbden_daduyet()//văn bản đến đã duyệt (GĐ, PGĐ)

	{

		/*$this->loadModel('Nhanvanban');

		$this->Nhanvanban->bindModel(array(

								'belongsTo'	=>	array('Vanban')

										   ), false);

		$this->Vanban->bindModel(array(

			'hasOne' => array(

				'Theodoivanban' => array('foreignKey' => 'vanban_id',

					'conditions' => array('nguoi_theodoi_id' => $this->Auth->user('nhanvien_id'))

				)

			)

		));*/		

		$conds = array( 'Vanban.nguoi_duyet_id'	=>	$this->Auth->user('nhanvien_id'),

						'Vanban.chieu_di'			=>	1,//văn bản đến

						'Vanban.tinhtrang_duyet'			=>	1 // đã duyệt

						);

		//pr($conds);

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

		//$this->Nhanvanban->recursive = 2;

		$ds =  $this->paginate('Vanban', $conds);

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$chieu_di = $this->Vanban->chieu_di;

		$this->set(compact('ds', 'chieu_di'));

	}
	public	function	vbden_dachuyen()//văn bản đến đã chuyển PGĐ duyệt 
	{
		$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhap_id','fields' => 'phong_id'),
											),
										), false);
										
		$this->Vanban->Nhanvien->unbindModel(array(
										'hasAndBelongsToMany' => array('Nhomquyen'),
										'belongsTo' => array('User')
										
										), false);																			
		$this->Vanban->recursive = 2;
		$this->loadModel('Phong');
		if($this->Auth->User('donvi_id') == '')
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => 2, 'enabled' => 1), 'fields' => array('id', 'id')));										
		}
		else
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('thuoc_phong' => $this->Auth->User('donvi_id'), 'enabled' => 1), 'fields' => array('id', 'id')));										
		}
		$conds = array( 'Vanban.chuyen_nguoiduyet'	=>	1,
							'Vanban.chieu_di'			=>	1,//văn bản đến
							'Nhanvien.phong_id'		=> $ds_phong
							);
		
		//pr($conds);
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
		//$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Vanban', $conds);
		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}

	function	edit_vanbanduyet($id = null)
	{
		if(!$this->check_permission('VanBan.suavbduyet'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Chỉnh sửa văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Filevanbanduyet'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id'),
			)
		));
		$this->loadModel('Nhanvien');
		$this->Nhanvien->bindModel(array(
				'belongsTo' => array(
					'User' =>	array('fields' => array()),
					'Chucdanh' => array('fields' => array()),
					'Phong' => array('fields' => array())
				)
			), false);
		$this->Nhanvien->recursive = 0;
		if(!empty($this->request->data))
		{
			//pr($this->request->data);die();
			$chuyen_nguoiduyet = $this->request->data['Vanban']['chuyen_nguoiduyet'];
			$importance = $this->request->data['Vanban']['importance'];
			$vb_gap = $this->request->data['Vanban']['vb_gap'];
			if($vb_gap == 1 && !empty($this->request->data['Vanban']['vbgap_ngayhoanthanh'])){
				$vbgap_ngayhoanthanh =  $this->Bin->vn2sql($this->request->data['Vanban']['vbgap_ngayhoanthanh']);				
			}else{
				$vbgap_ngayhoanthanh = NULL;
			}
			//pr($this->request->data);die();
			$ds = $this->request->data['Vanban']['nv_selected'];
			$nguoinhan = explode(",", $ds);
			$this->loadModel('Nhanvanban');
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}
			$dataSource = $this->Vanban->getDataSource();
			$dataSource->begin();
           	$noidung_duyet = $this->request->data['Vanban']['noidung_duyet'];
			
			///////////
			$phongchutri_id = $this->request->data['Vanban']['phongchutri_id'];
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id NOT IN(14,19,52,53,69,70,71,77,83)'))); // BGĐ VT và các TT	
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '')//Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else
						{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
				}
				///////////
			$phongphoihop_id = ltrim($phong_phoihop_id,',');
			if($chuyen_nguoiduyet == 0) //ko chuyển cho PGĐ duyệt	
			{
				$up_date = $this->Vanban->updateAll(
										array('noidung_duyet' => "'" . $noidung_duyet . "'",
											  'phongphoihop_id' => "'" . $phongphoihop_id . "'",
											  'importance' => $importance,
											  'vb_gap' => $vb_gap, 
											  'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'"), 
										array('Vanban.id' 	=> $id )
										);
			} else //chuyển cho PGĐ duyệt 
			{
				//lấy người duyệt mới
				$nguoi_duyet_id = $this->request->data['Vanban']['nguoi_duyet_id'];
				$arr_nguoiduyet = array($this->request->data['Vanban']['nguoi_duyet_id']);
				if(in_array($arr_nguoiduyet[0],$nguoinhan) == false)
					array_push($nguoinhan,$arr_nguoiduyet[0]); 
				$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_duyet_id),'fields' => array('User.fullname')));
				$nguoi_duyet = $nguoi_duyet['User']['fullname'];
				$up_date = $this->Vanban->updateAll(
										array('tinhtrang_duyet' => 2, 
											  'nguoi_duyet_id' => "'" . $nguoi_duyet_id . "'" , 
											  'nguoi_duyet' => "'" . $nguoi_duyet . "'", 
											  'noidung_duyet' => "'" . $noidung_duyet . "'", 
											  'phongphoihop_id' => "'" . $phongphoihop_id . "'", 
											  'importance' => $importance, 
											  'chuyen_nguoiduyet' => $chuyen_nguoiduyet, 
											  'vb_gap' => $vb_gap, 
											  'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'"), 
										array('Vanban.id' => $id )
											);
			}
			$f = true;
			$ds_nguoinhan = $this->Nhanvanban->find('all', array('conditions' => array('vanban_id' => $id)));
				if(!empty($ds_nguoinhan))
					{
						foreach($ds_nguoinhan as $k => $v)
						{
							$this->Nhanvanban->updateAll(
													array('ngay_xem' => NULL ), 
													array('Nhanvanban.vanban_id' 	=> $id ));
						}
					}
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
			/////
			//save phòng phối hợp 
			$this->loadModel('Phongphoihop');
			$old_phoihop = $this->Phongphoihop->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
			//ins
			if($f)
			{
				$arr_phongph = explode(",", $phongphoihop_id);
				foreach($arr_phongph as $k => $v)
					{
						$p['id'] = NULL;
						$p['vanban_id'] = $id;
						$p['phongphoihop_id'] = $v;
						if(!$this->Phongphoihop->save($p))
						{
							$f = false; break;
						}
					}
			}
			//del
			if($f)
			{
				foreach($old_phoihop as $k => $v)
				{
					if(!$this->Phongphoihop->delete($k))
					{
						$f = false;	break;
					}
				}
			}
			/////
			// Xem xét lại vấn đề này
			// lấy ds kết quả đã báo cáo kết quả xử lý theo văn bản
			$this->loadModel('Ketquavanban');
			$result = $this->Ketquavanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
			if($f)
				foreach($result as $k=>$v)
				{
					if(!$this->Ketquavanban->delete($k))
					{
						$f = false;	break;
					}
				}			
			if($f)
				{
					$dataSource->commit();
					$this->Session->setFlash('Văn bản đã chỉnh sửa thành công.', 'flash_success');
					$this->redirect('/vanban/index_vanbanden#vanban-daduyet');
				}
				else
				{
					$dataSource->rollback();
					$this->Session->setFlash('Đã phát sinh lỗi trong khi Chỉnh sửa Văn bản. Vui lòng thử lại.', 'flash_error');
				}
			
		}else	// show edit form
		{
			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			if(empty($data))

				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');

			if($data['Vanban']['nguoi_duyet_id'] != $this->Auth->user('nhanvien_id'))

				throw new InternalErrorException('Bạn không có quyền duyệt văn bản này. Vui lòng thử lại.');	

			$this->data = $data;
			$phongchutri_id = $data['Vanban']['phongchutri_id'];
			//lấy phòng chủ trì mới
			$pctnew_id = $this->Vanban->field('Vanban.phongchutri_id', array('Vanban.id' => $id));
			$this->loadModel('Phong');
			$loai_donvi = $this->Phong->field('Phong.loai_donvi', array('Phong.id' => $pctnew_id));
			if ($loai_donvi == 2 )//BGĐ, PBCN
			{
				//lấy theo chức danh(GĐVT, Chánh VP, Trưởng PBCN, GĐTT)
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
										from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c
										where a.id = '".$id."' 
										and a.phongchutri_id = b.phong_id
										and b.chucdanh_id = c.id
										and c.id in (2,16,30,35)
								");
				//lấy theo quyền(Văn thư PBCN, Văn thư)
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.id=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)
								"); 
			
			}
			else //Đơn vị trực thuộc
			{
				//lấy theo chức danh
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c, nhansu_phong d
								where a.id = '".$id."' 
								and a.phongchutri_id = d.thuoc_phong
								and b.phong_id = d.id
								and b.chucdanh_id = c.id
								and c.id in (2,16,30,35)
								");
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.thuoc_phong=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)	");
			}
			$this->loadModel('Phong');
			$phong_chutri = $this->Phong->field('ten_phong', array('Phong.id' => $data['Vanban']['phongchutri_id']));
			$this->set(compact('phong_chutri'));
			//Phong phoi hop
			$phong_phoihop = array();
			//if($data['Vanban']['phongphoihop_id']==NULL){
				if(!empty($data['Nhanvanban'])){	
					$arr_nvnvb = array();
					foreach($data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}	
			//}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));
				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));
				//pr($dsphong);die();
				$phong_phoihop_id = '';
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '') // Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							// Lấy phòng cha
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							//pr($id_phong);die();
							////
							if(in_array($ten_phong, $phong_phoihop)==false  && ($phongchutri_id != $itphong['Phong']['thuoc_phong'] )){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $id_phong.','.$phong_phoihop_id;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
							}
						}
					else //Đơn vị trực thuộc
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
								//pr($phong_phoihop);
								//pr($phong_phoihop_id);die();
						}
						//pr($phong_phoihop_id); die();
				}	
			}
			
			$this->set(compact('phong_phoihop','phong_phoihop_id','nv_chucdanh','nv_quyen','phongchutri_id'));
			if ($this->Auth->User('donvi_id') == '')
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id not in (784,681)','nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			else
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id <>' => $this->Auth->User('nhanvien_id'),'nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			$nhanviennhan = Set::combine($nhanviennhan, '{n}.Nhanvien.id', '{n}.User.fullname');
			$is_mobile = $this->RequestHandler->isMobile();
			//files văn bản
			$this->loadModel('Filevanban');
			$files = $this->Filevanban->find('all', array('conditions' => array('Filevanban.vanban_id' => $id )));
			$this->set(compact('nhanviennhan','files','is_mobile'));
			//$this->set(compact('nhanviennhan'));

		}
	}
	function	edit_vanbanchuyen($id = null)
	{
		if(!$this->check_permission('VanBan.suavbdachuyen'))
			throw new InternalErrorException('Bạn không có quyền hiệu chỉnh văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	
		$this->set('title_for_layout', 'Chỉnh sửa văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Filevanbanduyet'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey' => 'vanban_id'),
			)
		));
		$this->loadModel('Nhanvien');
		$this->Nhanvien->bindModel(array(
				'belongsTo' => array(
					'User' =>	array('fields' => array()),
					'Chucdanh' => array('fields' => array()),
					'Phong' => array('fields' => array())
				)
			), false);
		$this->Nhanvien->recursive = 0;
		if(!empty($this->request->data))
		{
			//pr($this->request->data);die();
			$chuyen_nguoiduyet = $this->request->data['Vanban']['chuyen_nguoiduyet'];
			$importance = $this->request->data['Vanban']['importance'];
			$vb_gap = $this->request->data['Vanban']['vb_gap'];
			if($vb_gap == 1 && !empty($this->request->data['Vanban']['vbgap_ngayhoanthanh'])){
				$vbgap_ngayhoanthanh =  $this->Bin->vn2sql($this->request->data['Vanban']['vbgap_ngayhoanthanh']);				
			}else{
				$vbgap_ngayhoanthanh = NULL;
			}
			//pr($this->request->data);die();
			$ds = $this->request->data['Vanban']['nv_selected'];
			$nguoinhan = explode(",", $ds);
			$this->loadModel('Nhanvanban');
			$this->request->data['Nhanvanban'] = array();
			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Nhanvanban'], array('nguoi_nhan_id' => $n));
				}
			}
			$dataSource = $this->Vanban->getDataSource();
			$dataSource->begin();
           	$noidung_duyet = $this->request->data['Vanban']['noidung_duyet'];
			
			///////////
			$phongchutri_id = $this->request->data['Vanban']['phongchutri_id'];
				if(!empty($this->request->data['Nhanvanban'])){
					$arr_nvnvb = array();
					foreach($this->request->data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}
				}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));

				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				//$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb)));	
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id NOT IN(14,19,52,53,69,70,71,77,83)'))); // BGĐ VT và các TT	
				//pr($dsphong);die();
				$phong_phoihop = array();
				$phong_phoihop_id ='';
				$this->loadModel('Phong');
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '')//Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							if(in_array($ten_phong, $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['thuoc_phong']) ){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $phong_phoihop_id.','.$id_phong;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
					else
						{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && $phongchutri_id != $itphong['Phong']['id']){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $phong_phoihop_id.','.$itphong['Phong']['id'];			
							}
						}
				}
				///////////
			$phongphoihop_id = ltrim($phong_phoihop_id,',');
			if($chuyen_nguoiduyet == 0) //ko chuyển cho PGĐ duyệt	
			{
				$up_date = $this->Vanban->updateAll(
										array('noidung_duyet' => "'" . $noidung_duyet . "'",
											  'phongphoihop_id' => "'" . $phongphoihop_id . "'",
											  'importance' => $importance,
											  'vb_gap' => $vb_gap, 
											  'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'"), 
										array('Vanban.id' 	=> $id )
										);
			} else //chuyển cho PGĐ duyệt 
			{
				//lấy người duyệt mới
				$nguoi_duyet_id = $this->request->data['Vanban']['nguoi_duyet_id'];
				$arr_nguoiduyet = array($this->request->data['Vanban']['nguoi_duyet_id']);
				if(in_array($arr_nguoiduyet[0],$nguoinhan) == false)
					array_push($nguoinhan,$arr_nguoiduyet[0]); 
				$nguoi_duyet = $this->Nhanvien->find('first', array('conditions' => array('Nhanvien.user_id' => $nguoi_duyet_id),'fields' => array('User.fullname')));
				$nguoi_duyet = $nguoi_duyet['User']['fullname'];
				$up_date = $this->Vanban->updateAll(
										array('tinhtrang_duyet' => 2, 
											  'nguoi_duyet_id' => "'" . $nguoi_duyet_id . "'" , 
											  'nguoi_duyet' => "'" . $nguoi_duyet . "'", 
											  'noidung_duyet' => "'" . $noidung_duyet . "'", 
											  'phongphoihop_id' => "'" . $phongphoihop_id . "'", 
											  'importance' => $importance, 
											  'chuyen_nguoiduyet' => $chuyen_nguoiduyet, 
											  'vb_gap' => $vb_gap, 
											  'vbgap_ngayhoanthanh' => "'".$vbgap_ngayhoanthanh."'"), 
										array('Vanban.id' => $id )
											);
			}
			$f = true;
			$ds_nguoinhan = $this->Nhanvanban->find('all', array('conditions' => array('vanban_id' => $id)));
				if(!empty($ds_nguoinhan))
					{
						foreach($ds_nguoinhan as $k => $v)
						{
							$this->Nhanvanban->updateAll(
													array('ngay_xem' => NULL ), 
													array('Nhanvanban.vanban_id' 	=> $id ));
						}
					}
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
			/////
			//save phòng phối hợp 
			$this->loadModel('Phongphoihop');
			$old_phoihop = $this->Phongphoihop->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
			//ins
			if($f)
			{
				$arr_phongph = explode(",", $phongphoihop_id);
				foreach($arr_phongph as $k => $v)
					{
						$p['id'] = NULL;
						$p['vanban_id'] = $id;
						$p['phongphoihop_id'] = $v;
						if(!$this->Phongphoihop->save($p))
						{
							$f = false; break;
						}
					}
			}
			//del
			if($f)
			{
				foreach($old_phoihop as $k => $v)
				{
					if(!$this->Phongphoihop->delete($k))
					{
						$f = false;	break;
					}
				}
			}
			/////
			// Xem xét lại vấn đề này
			// lấy ds kết quả đã báo cáo kết quả xử lý theo văn bản
			$this->loadModel('Ketquavanban');
			$result = $this->Ketquavanban->find('list', array('conditions' => 'vanban_id=' . $this->data['Vanban']['id'], 'fields' => array('id')));
			if($f)
				foreach($result as $k=>$v)
				{
					if(!$this->Ketquavanban->delete($k))
					{
						$f = false;	break;
					}
				}			
			if($f)
				{
					$dataSource->commit();
					$this->Session->setFlash('Văn bản đã chỉnh sửa thành công.', 'flash_success');
					$this->redirect('/vanban/index_vanbanden#vanban-dachuyen');
				}
				else
				{
					$dataSource->rollback();
					$this->Session->setFlash('Đã phát sinh lỗi trong khi Chỉnh sửa Văn bản. Vui lòng thử lại.', 'flash_error');
				}
			
		}else	// show edit form
		{
			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			if(empty($data))

				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');

			//if($data['Vanban']['nguoi_duyet_id'] != $this->Auth->user('nhanvien_id'))

				//throw new InternalErrorException('Bạn không có quyền duyệt văn bản này. Vui lòng thử lại.');	

			$this->data = $data;
			$phongchutri_id = $data['Vanban']['phongchutri_id'];
			//lấy phòng chủ trì mới
			$pctnew_id = $this->Vanban->field('Vanban.phongchutri_id', array('Vanban.id' => $id));
			$this->loadModel('Phong');
			$loai_donvi = $this->Phong->field('Phong.loai_donvi', array('Phong.id' => $pctnew_id));
			if ($loai_donvi == 2 )//BGĐ, PBCN
			{
				//lấy theo chức danh(GĐVT, Chánh VP, Trưởng PBCN, GĐTT)
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
										from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c
										where a.id = '".$id."' 
										and a.phongchutri_id = b.phong_id
										and b.chucdanh_id = c.id
										and c.id in (2,16,30,35)
								");
				//lấy theo quyền(Văn thư PBCN, Văn thư)
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.id=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)
								"); 
			
			}
			else //Đơn vị trực thuộc
			{
				//lấy theo chức danh
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c, nhansu_phong d
								where a.id = '".$id."' 
								and a.phongchutri_id = d.thuoc_phong
								and b.phong_id = d.id
								and b.chucdanh_id = c.id
								and c.id in (2,16,30,35)
								");
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.thuoc_phong=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)	");
			}
			$this->loadModel('Phong');
			$phong_chutri = $this->Phong->field('ten_phong', array('Phong.id' => $data['Vanban']['phongchutri_id']));
			$this->set(compact('phong_chutri'));
			//Phong phoi hop
			$phong_phoihop = array();
			//if($data['Vanban']['phongphoihop_id']==NULL){
				if(!empty($data['Nhanvanban'])){	
					$arr_nvnvb = array();
					foreach($data['Nhanvanban'] as $nv_nvb){
						array_push($arr_nvnvb,$nv_nvb['nguoi_nhan_id']);
					}	
			//}
				$this->loadModel('Nhanvien');
				$this->Nhanvien->UnbindModel(array(
					'hasAndBelongsToMany'	=>	array('Nhomquyen'),
					'belongsTo'=>array('User')
	
				));
				$this->Nhanvien->bindModel(array(
					'belongsTo'=>array('Phong'=>array('fields'=>array('id','ten_phong','thuoc_phong')))
				));
				$dsphong = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id' => $arr_nvnvb,'Phong.id <>'=>14)));
				//pr($dsphong);die();
				$phong_phoihop_id = '';
				foreach($dsphong as $itphong){					
					if($this->Auth->User('donvi_id') == '') // Viễn thông
						if($itphong['Phong']['thuoc_phong']!=NULL){
							// Lấy phòng cha
							$ten_phong = $this->Phong->field('ten_phong', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							$id_phong = $this->Phong->field('id', array('Phong.id' => $itphong['Phong']['thuoc_phong']));
							//pr($id_phong);die();
							////
							if(in_array($ten_phong, $phong_phoihop)==false  && ($phongchutri_id != $itphong['Phong']['thuoc_phong'] )){
								array_push($phong_phoihop,$ten_phong);	
								$phong_phoihop_id = $id_phong.','.$phong_phoihop_id;			
							}
						}else{
							if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
							}
						}
					else //Đơn vị trực thuộc
						if(in_array($itphong['Phong']['ten_phong'], $phong_phoihop)==false && ($phongchutri_id != $itphong['Phong']['id'] )){
								array_push($phong_phoihop,$itphong['Phong']['ten_phong']);	
								$phong_phoihop_id = $itphong['Phong']['id'].','.$phong_phoihop_id;			
								//pr($phong_phoihop);
								//pr($phong_phoihop_id);die();
						}
						//pr($phong_phoihop_id); die();
				}	
			}
			
			$this->set(compact('phong_phoihop','phong_phoihop_id','nv_chucdanh','nv_quyen','phongchutri_id'));
			if ($this->Auth->User('donvi_id') == '')
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id not in (784,681)','nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			else
				$nhanviennhan = $this->Nhanvien->find('all', array('conditions' => array('Nhanvien.id <>' => $this->Auth->User('nhanvien_id'),'nguoi_quanly = 1', 'tinh_trang = 1', 'Phong.thuoc_phong' => $this->Session->read('Auth.User.donvi_id'), 'Chucdanh.nhomquyen_id in (14,15)'),'fields' => array('id','User.fullname'), 'order' => array('Chucdanh.nhomquyen_id ASC'),));
			$nhanviennhan = Set::combine($nhanviennhan, '{n}.Nhanvien.id', '{n}.User.fullname');
			$is_mobile = $this->RequestHandler->isMobile();
			//files văn bản
			$this->loadModel('Filevanban');
			$files = $this->Filevanban->find('all', array('conditions' => array('Filevanban.vanban_id' => $id )));
			$this->set(compact('nhanviennhan','files','is_mobile'));
			//$this->set(compact('nhanviennhan'));

		}
	}
	

	public	function	quantrong() // VB đến được đánh dấu quan trọng

	{

		if(!$this->check_permission('VanBan.xemvbquantrong'))

			throw new InternalErrorException('Bạn không có quyền xem văn bản. Vui lòng liên hệ quản trị để cấp quyền.');	

		$conds = array( 'Vanban.nguoi_duyet_id'			=>	$this->Auth->user('id'),

						'Vanban.chieu_di'			=>	1,

						'Vanban.tinhtrang_duyet'	=> 	1,

						'Vanban.importance'		=> 	1

						);

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

		//$this->Vanban->recursive = 2;

		$ds =  $this->paginate('Vanban', $conds);

		//pr($ds);die();

		if(empty($ds))

		{

			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');

		}

		$this->set(compact('ds'));

	}

	

	function	up_result($id = null)//cập nhật kết quả thực hiện công việc theo văn bản

	{
		$this->set('title_for_layout', 'Cập nhật kết quả xử lý văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array(
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Ketquavanban'	=>	array('foreignKey' => 'vanban_id')
			)
		));
		if(!empty($this->request->data))
		{
			//pr($this->request->data);die();

			$nguoi_capnhat_id = $this->Auth->user('nhanvien_id');

			$ngay_capnhat = date("Y-m-d H:i:s");

			$noidung_capnhat = $this->request->data['Vanban']['noidung_capnhat'];

			$nguoinhan = explode(",", $this->request->data['Vanban']['nv_selected']);

			$ds = $this->request->data['Vanban']['nv_selected'];

			$nguoinhan = explode(",", $ds);
			$this->loadModel('Ketquavanban');

			$this->request->data['Ketquavanban'] = array();

			if(!empty($nguoinhan))
			{
				foreach($nguoinhan as $n)
				{
					array_push($this->request->data['Ketquavanban'], array('nguoi_nhan_id' => $n));
				}
			}
			$f = true;
			foreach($nguoinhan as $k => $v)
				{
					$t['id'] = NULL;
					$t['vanban_id'] = $id;
					$t['nguoi_nhan_id'] = $v;
					$t['nguoi_capnhat_id'] = $nguoi_capnhat_id;
					$t['ngay_capnhat'] = $ngay_capnhat;
					$t['noidung_capnhat'] = $noidung_capnhat;
					if(!$this->Ketquavanban->save($t))
					{
						$f = false;	break;
					}
					//Đính kèm file cập nhật kết quả
					if(!empty($this->request->data['Filevanban']))
					{
						$f_bc['id'] = NULL;
						$f_bc['vanban_id'] = $id;
						$f_bc['nguoibaocao_id'] = $this->Auth->User('nhanvien_id');
						$f_bc['nguoinhan_id'] = $v;
						//$f_bc['kqbaocao_id'] = $t['id'];
						//pr($id);
						$kqbaocao_id = $this->Ketquavanban->find('all', array('conditions' => array('Ketquavanban.vanban_id' =>  $id,'Ketquavanban.nguoi_capnhat_id' => $this->Auth->User('nhanvien_id'))));
						//pr($kqbaocao_id);die();
						if(!empty($kqbaocao_id))
						{
							foreach($kqbaocao_id as $i)
							{
								$f_bc['kqbaocao_id'] = $i['Ketquavanban']['id'];
							}
						}
						$old = str_replace("/", DS, Configure::read('File.tmp'));
						$old = substr($old, 1, strlen($old)-1);
						$new = str_replace("/", DS, Configure::read('VanBan.attach_path'));
						$new = substr($new, 1, strlen($new)-1);
						//pr($new);die();
						foreach($this->request->data['Filevanban'] as $item)
						{
							$f_bc['ten_cu'] = $item['ten_cu'];
							$f_bc['ten_moi'] = $item['ten_moi'];
							//pr(WWW_ROOT . $new . $item['ten_moi']);die();
							if(copy(WWW_ROOT . $old . $f_bc['ten_moi'],  WWW_ROOT . $new . $f_bc['ten_moi']))
								unlink(WWW_ROOT . $old . $f_bc['ten_moi']);
							$this->loadModel('Filebaocao');
							if (!$this->Filebaocao->save($f_bc)) 
							{
								$f = false;break;
							}
						}
					} 
				}
			if($f)

				{

					$this->Session->setFlash('Văn bản đã cập nhật kết quả thành công.', 'flash_success');

					$this->redirect('/vanban/index#vanban-gap');

				}

				else

				{

					$dataSource->rollback();

					$this->Session->setFlash('Đã phát sinh lỗi trong khi duyệt Văn bản. Vui lòng thử lại.', 'flash_error');

				}	

		}else	// show edit form

		{

			$data = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));
			//pr($data);die();

			if(empty($data))

				throw new InternalErrorException('Không tìm thấy văn bản này. Vui lòng thử lại');

			$ds_nguoinhan = array();

			if(!empty($data))

					foreach($data['Nhanvanban'] as $k => $v)

					{

						$n = $v['nguoi_nhan_id'];

						array_push($ds_nguoinhan, $n);

					}

			$check = in_array($this->Auth->user('nhanvien_id'),$ds_nguoinhan);

			if(!$check)

						throw new InternalErrorException('Bạn không có quyền cập nhật kết quả thực hiện văn bản này. Vui lòng thử lại.');	

			$this->data = $data;

		}

	}

	public	function	ketquaxuly() // VB đến đã cập nhật kết quả xử lý

	{
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array('Ketquavanban' => array('foreignKey' => 'vanban_id')
			
							)
		), false);
		/*$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanviencapnhat' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_capnhat_id'),
    								'Nhanviennhan' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_nhan_id'),
									'Filebaocao' => array('className'	=>	'Filebaocao','foreignKey' => 'filesbc_id')
							),
		), false);*/
		$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanviencapnhat' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_capnhat_id'),
    								'Nhanviennhan' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_nhan_id')
							),
		), false);
		/*$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhan_id'),
							),
		), false);*/
		//pr($this->Auth->User('nhanvien_id'));die();
		if($this->Auth->User('chucdanh_id') == 2 || $this->Auth->User('nhanvien_id') == 683)//GĐ VTĐN, CVP
		{
			$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhap_id','fields' => 'phong_id'),
											),
										), false);
										
			$this->Vanban->Nhanvien->unbindModel(array(
											'hasAndBelongsToMany' => array('Nhomquyen'),
											'belongsTo' => array('User')
											
											), false);																			
			$this->loadModel('Phong');
			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => 2, 'enabled' => 1), 'fields' => array('id', 'id')));										
			$this->Vanban->recursive = 2;
			
			$conds = array('Vanban.vb_gap'	=>	1,
				   'Vanban.chieu_di'			=>	1,
				   'Vanban.tinhtrang_duyet' => 1,
				   'Nhanvien.phong_id'		=> $ds_phong
				    );
		}
		elseif ($this->Auth->User('chucdanh_id') == 30)//GĐ Trung tâm
		{
			$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhap_id','fields' => 'phong_id'),
											),
										), false);
										
			$this->Vanban->Nhanvien->unbindModel(array(
											'hasAndBelongsToMany' => array('Nhomquyen'),
											'belongsTo' => array('User')
											
											), false);																			
			$this->loadModel('Phong');
			$ds_phong = $this->Phong->find('list', array('conditions' => array('thuoc_phong' => $this->Auth->User('donvi_id'), 'enabled' => 1), 'fields' => array('id', 'id')));										
			$this->Vanban->recursive = 2;
			$conds = array('Vanban.vb_gap'	=>	1,
					   'Vanban.chieu_di'			=>	1,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Nhanvien.phong_id'		=> $ds_phong);
		}
		else //PGD Viễn thông, PGD Trung tâm (người nhận kết quả)
		{
			/*$this->Vanban->bindModel(array(
			'hasOne' => array(
				'Nhanvanban' => array('foreignKey' => 'vanban_id',
					'conditions' => array('nguoi_nhan_id' => $this->Auth->user('nhanvien_id'))
				)
			)
		));*/
			
			$conds = array('Vanban.vb_gap'	=>	1,
					   'Vanban.chieu_di'			=>	1,
					   'Vanban.tinhtrang_duyet' => 1,
					   'Vanban.nguoi_duyet_id'	=> $this->Auth->User('nhanvien_id'));				   
			/*$conds = array('Vanban.vb_gap'	=>	1,
					   'Vanban.chieu_di'			=>	1,
					   'Vanban.tinhtrang_duyet' => 1
					   );*/				   

		}
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

		$this->Vanban->recursive = 2;
		$ds =  $this->paginate('Vanban', $conds);
		//pr($ds);die();
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$this->set(compact('ds'));
	}

	

	public	function	view_result($id = null)

	{

		$this->set('title_for_layout', 'Kết quả xử lý văn bản');

		$this->loadModel('Ketquavanban');
            
		$this->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array(
				'Vanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Nhanvien'	=>	array('foreignKey'	=>	'nguoi_capnhat_id')
			)
		));
		$this->Ketquavanban->bindModel(array(

			'hasMany' => array(

				'Filebaocao' => array('foreignKey' => 'kqbaocao_id',

					'conditions' => array('Filebaocao.kqbaocao_id' => $id)

				)

			)

		));	
		$nv = $this->Auth->user('nhanvien_id');
		if($nv == 681 || $nv == 683 || $this->Auth->User('chucdanh_id') == 30)
			$data = $this->Ketquavanban->find('first',array('conditions' => array('Ketquavanban.id' => $id )));
		else
			$data = $this->Ketquavanban->find('first',array('conditions' => array('Ketquavanban.id' => $id,'Ketquavanban.nguoi_nhan_id' => $nv )));		
		if(empty($data))

			throw new InternalErrorException('Bạn không có quyền xem kết quả xử lý văn bản này. Vui lòng liên hệ quản trị để cấp quyền.');	

		$this->set(compact('data'));

		$this->set('title_for_layout', 'Kết quả xử lý văn bản');

	}
	
	public	function	view_ketquabc($id = null)

	{
		$this->set('title_for_layout', 'Kết quả báo cáo văn bản');
		$this->Vanban->bindModel(array(
			'hasMany'	=>	array('Ketquavanban' => array('foreignKey' => 'vanban_id')
			
							)
		), false);
		
		$this->Vanban->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array('Nhanviennhan' => array('className'	=>	'Nhanvien','foreignKey' => 'nguoi_nhan_id')
							),
		), false);
		$this->loadModel('Ketquavanban');
		$this->Ketquavanban->bindModel(array(
			'belongsTo'	=>	array(
				'Vanban'	=>	array('foreignKey'	=>	'vanban_id')
			)
		));
		$this->Ketquavanban->bindModel(array(

			'hasMany' => array(

				'Filebaocao' => array('foreignKey' => 'kqbaocao_id',

					'conditions' => array('Filebaocao.kqbaocao_id' => $id)

				)

			)

		));	
		
		$nv = $this->Auth->user('nhanvien_id');
		$data = $this->Ketquavanban->find('all',array('conditions' => array('Ketquavanban.vanban_id' => $id,'Ketquavanban.nguoi_capnhat_id' => $nv )));
		/*if($nv == 681 || $nv == 683 || $this->Auth->User('chucdanh_id') == 30)
			$data = $this->Ketquavanban->find('first',array('conditions' => array('Ketquavanban.id' => $id )));
		else
			$data = $this->Ketquavanban->find('first',array('conditions' => array('Ketquavanban.id' => $id,'Ketquavanban.nguoi_nhan_id' => $nv )));*/		
		//pr($data);die();
		if(empty($data))

			throw new InternalErrorException('Bạn không có quyền xem kết quả xử lý văn bản này. Vui lòng liên hệ quản trị để cấp quyền.');	

		$this->set(compact('data'));

		$this->set('title_for_layout', 'Kết quả xử lý văn bản');

	}
	/////////////

	

	public	function	add_baocao()

	{

		if(!$this->check_permission('BaoCao.gui'))

			throw new InternalErrorException('Bạn không có quyền gửi văn bản thuộc loại báo cáo. Vui lòng liên hệ quản trị để cấp quyền.');	

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

			//pr($this->Auth->user());die();

			$this->request->data['Vanban']['phong_id'] = $this->Auth->user('phong_id');

			$this->request->data['Vanban']['donvi_id'] = $this->Auth->user('donvi_id');

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

			//if($this->request->data['Vanban']['loaivanban_id'] == 4){

				//$this->request->data['Vanban']['loaivanban_id'] = $this->request->data['Vanban']['loaivanbanct_id'] ;

			//}

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

			$this->loadModel('Phong');

			$phong = $this->Phong->generateTreeList(null, null, '{n}.Phong.ten_phong', '---');

			$this->set(compact('chieu_di', 'loaivanban', 'phong','loaivanban_chitiet'));

		}

	}

	//////////////

	function	files_att($id = null) 

	{

		$this->Vanban->bindModel(array(

			'hasOne'	=>	array('Filevanbanduyet' => array('foreignKey' => 'vanban_id'),

							'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id')

							),

		), false);

		$this->loadModel('Filevanbanduyet');

		$vanban = $this->Filevanbanduyet->find('first', array('conditions' => array('Filevanbanduyet.id' => $id)));

		//pr($vanban);die();

		/*if (empty($vanban))

			$vanban = $this->Vanban->find('first', array('conditions' => array('Filevanban.id' => $id, 'Nhanvanban.nguoi_nhan_id' => $nv )));	*/		

		//pr($vanban);die();

		/*if(empty($vanban))

			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');*/

		$this->loadModel('Filevanbanduyet');

		//$data = $this->Filevanban->find('first', array('conditions' => array('Filevanban.id' => $id)));

		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));

		$path = substr($path, 1, strlen($path)-1);

		$path = WWW_ROOT . $path;

		$file_moi = $vanban['Filevanbanduyet']['ten_moi'];

		$file_cu = $vanban['Filevanbanduyet']['ten_cu'];

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
	function	files_attbaocao($id = null) 

	{
		$this->loadModel('Filebaocao');
		if($this->Auth->User('nhanvien_id') == 681 || $this->Auth->User('nhanvien_id') == 683 || $this->Auth->User('chucdanh_id') == 30)
			$vanban = $this->Filebaocao->find('first', array('conditions' => array('Filebaocao.id' => $id)));
		else
			$vanban = $this->Filebaocao->find('first', array('conditions' => array('Filebaocao.id' => $id, 'nguoinhan_id' => $this->Auth->User('nhanvien_id'))));
		if(empty($vanban))
			throw new InternalErrorException('Bạn không được phép download văn bản này. Vui lòng chọn văn bản khác.');			
		
		$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
		$path = substr($path, 1, strlen($path)-1);
		$path = WWW_ROOT . $path;
		$file_moi = $vanban['Filebaocao']['ten_moi'];
		$file_cu = $vanban['Filebaocao']['ten_cu'];
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
	//////17/03/2016
	public	function	vbden_pgdduyet()//văn bản đến PGĐ đã duyệt ; 
	{
		$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_nhap_id','fields' => 'phong_id'),
											),
										), false);
										
		$this->Vanban->Nhanvien->unbindModel(array(
										'hasAndBelongsToMany' => array('Nhomquyen'),
										'belongsTo' => array('User')
										
										), false);																			
		$this->Vanban->recursive = 2;
		$this->loadModel('Phong');
		if($this->Auth->User('donvi_id') == '')
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('loai_donvi' => 2, 'enabled' => 1), 'fields' => array('id', 'id')));
			$conds = array( 'Vanban.nguoi_duyet_id <>'	=>	681,
							'Vanban.chuyen_bypass <>'	=>	1,
								'Vanban.chieu_di'			=>	1,//văn bản đến
								'Nhanvien.phong_id'		=> $ds_phong
								
								);
		}
		else
		{
			$ds_phong = $this->Phong->find('list', array('conditions' => array('thuoc_phong' => $this->Auth->User('donvi_id'), 'enabled' => 1), 'fields' => array('id', 'id')));
			$this->Vanban->bindModel(array(
							'belongsTo'	=>	array('Nhanvien' => array('foreignKey' => 'nguoi_duyet_id','fields' => 'chucdanh_id'),
											),
										), false);
			$conds = array( 	'Vanban.chieu_di'			=>	1,//văn bản đến
							    'Vanban.chuyen_bypass <>'	=>	1,
								'Nhanvien.phong_id'		=> $ds_phong,
								'Nhanvien.chucdanh_id <>'		=> 30
								
								);
		}
			
		//pr($conds);
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
		//$this->Nhanvanban->recursive = 2;
		$ds =  $this->paginate('Vanban', $conds);
		if(empty($ds))
		{
			$this->Session->setFlash('Không có văn bản nào.', 'flash_attention');
		}
		$chieu_di = $this->Vanban->chieu_di;
		$this->set(compact('ds', 'chieu_di'));
	}
	
	public	function 	giamdoc_chidao()

	{
		$this->layout = null;
		//pr($this->request->data);die();
		$f = false;
		if(!empty($this->request->data))
		{
			//pr($this->request->data);die();
			$id  = $this->request->data['Vanban']['vanban_id'];
			$this->loadModel('Nhanvanban');
			$this->Nhanvanban->bindModel(array(

								'belongsTo'	=>	array('Vanban')

										   ), false);
			if($this->Vanban->updateAll(array('gd_chidao' => "'" . $this->request->data['Vanban']['gd_chidao'] . "'") , array('Vanban.id' => $id )))
				if($this->Nhanvanban->updateAll(array('ngayxem_chidao' => NULL ) , array('Vanban.id' => $id )))
						$f = true; 
			if($f)	

				//$this->Session->setFlash('Văn bản đã được chỉ đạo.', 'flash_success');
				die(json_encode(array('success' => true)));

			else

				//$this->Session->setFlash('Đã phát sinh lỗi khi giám đốc chỉ đạo. Vui lòng thử lại', 'flash_success');
				die(json_encode(array('success' => false)));

			//$this->redirect('/vanban/view/'.$id);

		}else

		{
			$id = $this->passedArgs['id'];
			$gd_chidao = $this->Vanban->field('gd_chidao',array('Vanban.id' => $id));
			$this->set(compact(array('id','gd_chidao')));

		}

	}
	
	///////////////////////Hà thêm //////////////////////////


function	duyet_vb_dvchutri_edit($id = null, $v_pct_id = null)//Đổi đơn vị chủ trì)
{	
	$this->loadModel('Phong');
	$vb = $this->Vanban->find('first', array('conditions' => array('Vanban.id' => $id)));	
	$this->vb = $vb;
	if(!empty($this->request->data))
	{	
		$sql1 = "Update vanban_thongtin Set phongchutri_id = ".$this->request->data['Vanban']['phongchutri_id']. " Where id = ".$id;		
		$this->Vanban->query($sql1);
		$nv_chucdanh = array();
		$nv_quyen = array();		
		//lấy phòng chủ trì mới
		$pctnew_id = $this->Vanban->field('Vanban.phongchutri_id', array('Vanban.id' => $id));
		//pr($pctnew_id);die();
		if($this->Auth->User('donvi_id') == '') // người duyệt là GĐ,PGĐ VTĐN
		{
			$loai_donvi = $this->Phong->field('Phong.loai_donvi', array('Phong.id' => $pctnew_id));
			if ($loai_donvi == 2 || $loai_donvi == 6)//BGĐ, PBCN, Tổ KSCL
			{
				//lấy theo chức danh(GĐVT, Chánh VP, Trưởng PBCN, GĐTT)
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
										from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c
										where a.id = '".$id."' 
										and a.phongchutri_id = b.phong_id
										and b.chucdanh_id = c.id
										and c.id in (2,16,30,35)
								");
				//pr($nv_chucdanh);die();
				//lấy theo quyền(Văn thư PBCN, Văn thư)
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.id=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)
								"); 
				//32,33,95
				
				if($pctnew_id == 32)
					$nv_chucdanh[0]['b'] = array('id'=>'688');
				if($pctnew_id == 33)
					$nv_chucdanh[0]['b'] = array('id'=>'690');
				if($pctnew_id == 95)
					$nv_chucdanh[0]['b'] = array('id'=>'804');
				}
			
			else //Đơn vị trực thuộc
			{
				//lấy theo chức danh(GĐVT, Chánh VP, Trưởng PBCN, GĐTT)
				$nv_chucdanh = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a, nhansu_nhanvien b, nhansu_chucdanh c, nhansu_phong d
								where a.id = '".$id."' 
								and a.phongchutri_id = d.thuoc_phong
								and b.phong_id = d.id
								and b.chucdanh_id = c.id
								and c.id in (2,16,30,35)
								");
				$nv_quyen = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a
								inner join nhansu_phong c on c.thuoc_phong=a.phongchutri_id
								inner join nhansu_nhanvien b on b.phong_id=c.id and b.tinh_trang=1
								inner join  sys_nhanvien_nhomquyen d on d.nhanvien_id=b.id
								inner join sys_quyen e on e.id=d.nhomquyen_id
								where a.id = '".$id."' 
								and e.id in (20,21)	");
			}
			$nv = array_merge($nv_chucdanh,$nv_quyen);
			//pr($nv);die();
		
		}
		else // người duyệt là GĐ,PGĐ TT
		{
			$nv = $this->Vanban->query(" 
								select  b.id
								from vanban_thongtin a, nhansu_nhanvien b, nhansu_phong d
								where a.id = '".$id."'
								and a.phongchutri_id = d.id
								and b.phong_id = d.id	");
		}
		echo json_encode($nv);die();
	}else{
		if($this->Auth->User('donvi_id') == '')
			$dsphong = $this->Phong->generateTreeList($conditions=array('Phong.thuoc_phong'=>NULL,'Phong.id <>' =>14), null, '{n}.Phong.ten_phong', '---');
			
		else
			$dsphong = $this->Phong->generateTreeList($conditions=array('Phong.thuoc_phong'=> $this->Auth->User('donvi_id') ,'Phong.id NOT IN (19,52,53,69,70,71,77,83)' ), null, '{n}.Phong.ten_phong', '---');
				//pr($dsphong);die();
		$phongchutri_id = $this->Vanban->field('Vanban.phongchutri_id', array('Vanban.id' => $id));
		$this->set(compact('dsphong','nv_chucdanh','nv_quyen','phongchutri_id'));
	}
}
///////////////////////////////////////////////////

	


}











