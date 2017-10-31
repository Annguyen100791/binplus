<?php

/**

 * Nhanvien

 *

 * PHP version 5

 *

 * @category Model

 * @package  BIN

 * @version  1.0

 * @author   Thanh Nguyen <dinhthanh79@gmail.com>

 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License

 * @link     http://www.ptc.com.vn

 */

class Nhanvien extends AppModel {

/**

 * Model name

 *

 * @var string

 * @access public

 */

    public 	$name = 'Nhanvien';

	

	public 	$tablePrefix = 'nhansu_';

	

	public	$gioi_tinh		= array("0"	=>	"Nữ", "1"	=>	"Nam");

	public	$tinh_trang		= array("1"	=>	"Đang công tác", "0"	=>	"Đã nghỉ công tác");

	

	private $cacheKey 		= 	'myapp_nhanvien';

	

	

	public $hasAndBelongsToMany = array(

        'Nhomquyen' =>

            array(

                'className'              => 'Nhomquyen',

                'joinTable'              => 'sys_nhanvien_nhomquyen',

                'foreignKey'             => 'nhanvien_id',

                'associationForeignKey'  => 'nhomquyen_id',

                'unique'                 => true,

                'conditions'             => '',

                'fields'                 => '',

                'order'                  => '',

                'limit'                  => '',

                'offset'                 => '',

                'finderQuery'            => '',

                'deleteQuery'            => '',

                'insertQuery'            => ''

            )

    );

	

	

/**

 * Model associations: belongsTo

 *

 * @var array

 * @access public

 */

 	/*

    public $belongsTo = array('Chucdanh'=>	array('fields'	=>	'ten_chucdanh'), 

							  'Phong'	=>	array('fields'	=>	'ten_phong'));

	*/

	

	public	$belongsTo = array('User');

	

	

    function __construct($id = false, $table = null, $ds = null) {

    	parent::__construct($id, $table, $ds);

    	$this->virtualFields['full_name'] = sprintf('CONCAT(%s.ho, " ", %s.ten_lot, " ", %s.ten)', $this->alias, $this->alias, $this->alias);

    }



	

	public	function	dsQuyen($nhanvien_id)

	{

		$this->recursive = 3;

		$data = $this->read(null, $nhanvien_id);

		

		$ds = array();

		

		if(!empty($data['Nhomquyen']))

		{

			foreach($data['Nhomquyen'] as $nhomquyen)

			{

				if(!empty($nhomquyen['Quyen']))

					foreach($nhomquyen['Quyen'] as $quyen)

					{

						$name = substr($quyen['tu_khoa'], 0, strpos($quyen['tu_khoa'], '.'));

						$ds[$name][substr($quyen['tu_khoa'], strpos($quyen['tu_khoa'], '.') + 1, strlen($quyen['tu_khoa'])-1)] = $quyen['ten_quyen'];

					}

			}

		}

		

		return $ds;

	}

	

	public	function	delete($id = null, $cascade = true)

	{

		$user = $this->read('user_id', $id);

		

		if(parent::delete($id, $cascade))

		{

			if($this->User->delete($user['Nhanvien']['user_id']))

			return true;

		}

		return false;

	}

	

	

	public	function	listNhanvien2Combobox($phong_id = null)
	{
		$nv = array();

		if(is_null($phong_id))	return null;		

		if( empty( $phong_id ) ){
			$ds = $this->listNhanvien();
		//	pr($ds);die();

			if(empty($ds))
				return array();

			$stt = 1;

			foreach($ds as $item)

			{

				$n = array();

				if(!empty($item['Nhanvien']))
				{

					foreach($item['Nhanvien'] as $c)

						$n[$c['Nhanvien']['id']] = $c['Nhanvien']['full_name'].' - '.$c['User']['username'];

					$nv[$stt . '. ' . $item['Phong']['ten_phong']] = $n;

				}else

					$nv[$stt . '. ' . $item['Phong']['ten_phong']] = array();

				$stt++;

			}

		} else {

			App::import( 'Model', 'Phong' );

			$phong = new Phong();

			$ds = $phong->getList($phong_id);

			

			$this->bindModel(array('belongsTo' => array('Chucdanh')), false);

			$this->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen')), false);

			

			for($i = 0; $i < $size=count($ds); $i++)
			{

				

				$n = $this->find('list', array(

								'conditions' => array('phong_id' => $ds[$i]['Phong']['id'], 'Nhanvien.tinh_trang' => 1),

								'order' 	=> array('nguoi_quanly' => 'DESC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC'),

								'fields'	=>	array('id', 'full_name'),

								'recursive'	=>	1));

				

				$nv[$ds[$i]['Phong']['tree_prefix'] . $ds[$i]['Phong']['ten_phong']] = $n;

			}

		}

		

		return $nv;

	}

/*

	function	:	listNhanvien

	input		:	none

	output		:	danh sách nhân viên theo phòng

	output format

	Array

	(

		[0] => Array

			(

				[Phong] => Array

					(

						[id] => 1

						[ten_phong] => Ban Giám đốc

						[lft] => 1

						[rght] => 2

						[tree_prefix] => 

						[padding] => 0

					)

	

				[Nhanvien] => Array

					(

						[0] => Array

							(

								[Nhanvien] => Array

									(

										[id] => 26

										[nguoi_quanly] => 1

										[full_name] => Nguyễn Nho Túy

									)	

							)

						[1]	=>	Array(....)

					)

			)

		[1]	=>	Array(...)

	)

	

*/	

	public	function	listNhanvien()

	{

		$ds = Cache::read($this->cacheKey);

		//var_dump($ds);die();
		if ($ds == false)

		{

			App::import( 'Model', 'Phong' );

			$phong = new Phong();

			

			$ds = $phong->getList();
			$this->bindModel(array('belongsTo' 	=> array('Chucdanh')), false); 
			//$this->bindModel(array('hasOne' 	=> array('User')), false); 
			
			$this->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen')), false);

			

			for($i = 0; $i < $size=count($ds); $i++)

			{

				$nhanvien = $this->find('all', 

					array(

						'conditions' 	=> 	array('phong_id' => $ds[$i]['Phong']['id'], 'tinh_trang' => 1),

						'order' 		=> 	array('nguoi_quanly' => 'DESC', 'stt' => 'ASC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC'),

						'fields'		=>	array('id', 'full_name', 'nguoi_quanly','User.username')

						)

					);

				$ds[$i]['Nhanvien'] = $nhanvien;

				

			}

			

			Cache::write($this->cacheKey, $ds);

			return $ds;

		}else

		{

			return $ds;

		}
		//pr($ds);die();
		

	}

	

	public 	function 	deleteCachedConfig()

	{

		Cache::delete($this->cacheKey);

		Cache::delete($this->cacheKey . '_tree');

	}

	

	public	function	saveCachedConfig()

	{

		App::import( 'Model', 'Phong' );

		$phong = new Phong();

		

		$ds = $phong->getList();

		

		$this->bindModel(array('belongsTo' 	=> array('Chucdanh')), false); 

		$this->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen')), false);

		

		for($i = 0; $i < $size=count($ds); $i++)

		{

			$nhanvien = $this->find('all', 

				array(

					'conditions' 	=> 	array('phong_id' => $ds[$i]['Phong']['id'], 'tinh_trang' => 1),

					'order' 		=> 	array('nguoi_quanly' => 'DESC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC'),

					'fields'		=>	array('id', 'full_name', 'nguoi_quanly'),

					'recursive'		=>	1

					)

				);

			$ds[$i]['Nhanvien'] = $nhanvien;

			

		}

		

		Cache::write($this->cacheKey, $ds);

	}

	public	function	afterSave($created)

	{

		$this->deleteCachedConfig();
		//$this->saveCachedConfig();

		

	}
	

	public	function afterDelete() 

	{

		$this->deleteCachedConfig();

    }

	

	public	function	getTree($phong_id = null)	// for dynatree

	{

		$tree = null;
		//pr($phong_id);die();
		if(empty($phong_id))

		{

			$tree = Cache::read($this->cacheKey . '_tree');

		}

		

		if(!$tree)

		{

			App::import( 'Model', 'Phong' );

			$phong = new Phong();

			

			$ds_phong = $phong->getList($phong_id);

			

			$this->bindModel(array('belongsTo' 	=> array('Chucdanh')), false); 

			$this->unbindModel(array('hasAndBelongsToMany' => array('Nhomquyen')), false);
			
	
			

			$data = array();

			$tree = array();

			

			//pr($ds_phong);die();

			if(!empty($ds_phong))

				foreach($ds_phong as $item)

				{

					//$selected = in_array($item['Phong']['id'], $cats);

					$nhanvien = $this->find('all', 

						array(

							'conditions' 	=> 	array('phong_id' => $item['Phong']['id'], 'tinh_trang' => 1),

							'order' 		=> 	array('nguoi_quanly' => 'DESC', 'stt' => 'ASC', 'Chucdanh.thu_tu' => 'ASC', 'ten' => 'ASC'),

							'fields'		=>	array('id', 'full_name', 'nguoi_quanly', 'gioi_tinh', 'Chucdanh.ten_chucdanh', 'email', 'dien_thoai', 'dien_thoai_noi_bo','so_meg','chucdanh_id'),

							'recursive'		=> 	1

							)

						);
					
					//var_dump($nhanvien); die();

					$data[$item['Phong']['id']] = array(

														'key'	=>	$item['Phong']['id'],

														'title'	=>	$item['Phong']['ten_phong'],

														'parent_id'	=>	$item['Phong']['thuoc_phong'],

														'select'	=>	false,

														'expand'	=>	false,

														'isFolder'	=>	true,

														'children'	=>	array()

													);
					if(!empty($nhanvien))

						foreach($nhanvien as $n)

						{
							
							$nv_id = $n['Nhanvien']['id'];
							//pr($nv_id);die();
							$query = $this->query("Select 1 from sys_nhanvien_nhomquyen a where a.nhanvien_id = '".$nv_id."' and a.nhomquyen_id in (20,21)");
							$nhan = -1;
							
							if(in_array($n['Nhanvien']['chucdanh_id'],array(16,35,30)) ||
								!empty($query) || in_array($n['Nhanvien']['id'],array(688,690,804)) )
							{
								$nhan = 1;
							}
							//Thêm vào để lấy đơn vị
							App::uses('CakeSession', 'Model/Datasource');
							$donvi_id = CakeSession::read('Auth.User.donvi_id');
							$top = false;
							
							if(empty($donvi_id) || is_null($donvi_id))
								$top = true;
							//
							$child = array(

								'key'		=>	'nv-' . $n['Nhanvien']['id'],

								'title'		=>	$n['Nhanvien']['full_name'],

								'parent_id'	=>	$item['Phong']['id'],

								'nguoi_quanly'	=>	$n['Nhanvien']['nguoi_quanly'],

								'ten_chucdanh'	=>	$n['Chucdanh']['ten_chucdanh'],

								'email'			=>	$n['Nhanvien']['email'],

								'dien_thoai'	=>	$n['Nhanvien']['dien_thoai'],

								'dien_thoai_noi_bo'	=>	$n['Nhanvien']['dien_thoai_noi_bo'],
								'so_meg'	=>	$n['Nhanvien']['so_meg'],

								'isFolder'	=>	false,

								'tooltip'	=>	'Click để chọn/ bỏ chọn',

								'icon'		=>	($n['Nhanvien']['gioi_tinh'] == 1) ? 'male.png' : 'female.png',

								'children'	=>	null,
								
								'nhan' => $nhan,
								'top' => $top

							);

							

							array_push($data[$item['Phong']['id']]['children'], $child);

						}

				}

			foreach($data as $item)

			{

				if(!empty($item['parent_id']))

				{

					//$data[$item['thuoc_phong']]['isFolder'] = true;

					if(isset($data[$item['parent_id']]['children']))

					array_push($data[$item['parent_id']]['children'], $item);

				}

			}

			for($i = count($ds_phong)-1; $i >=0; $i--)

			{

				

				$parent_id = $ds_phong[$i]['Phong']['thuoc_phong'];

				$id = $ds_phong[$i]['Phong']['id'];

				

				

				if(empty($data[$id]['children']))

				{

					if(!empty($parent_id))

					{

						unset($data[$id]);

					}

				}else

				{

					

					if(!empty($parent_id))

					{

						if(isset($data[$parent_id]))

						for($j = 0; $j < count($data[$parent_id]['children']); $j++)

						{

							if($data[$parent_id]['children'][$j]['key'] == $id)

							{

								$data[$parent_id]['children'][$j] = $data[$id];

								//array_push($data[$parent_id]['children'], $data[$id]);

								unset($data[$id]);

							}

						}

					}

				}

			}

			//pr($data); die();

			$tree = array_values($data);

			if(empty($phong_id))

				Cache::write($this->cacheKey . '_tree', $tree);

		}

			

		return $tree;

	}

}

?>