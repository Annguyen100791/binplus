<?php

class	Phong		extends AppModel

{

	public		$name	= 	'Phong';

	public 		$tablePrefix 	= 	'nhansu_';

	

	public $actsAs = array(

        'Containable',

        'Tree' => array('parent' => 'thuoc_phong'),

    );

	

	/*

	const VIENTHONG = 1;

	const VIENTHONG_PHONGBAN = 2;

	const DONVI = 3;

	const DONVI_PHONGBAN = 4;

	

	public $loai_donvi = array(

		self::VIENTHONG 			=> 'Viễn thông Đà Nẵng',

		self::VIENTHONG_PHONGBAN	=> 'Phòng ban chức năng thuộc viễn thông',

		self::DONVI			=>	'Trung tâm thuộc viễn thông',

		self::DONVI_PHONGBAN	=>	'Phòng / đội / tổ thuộc Trung tâm'

	);

	*/

	

	

	public function getList($phong_id = null) 	

	{

		$this->recursive = -1;

		if(empty($phong_id))

			$results = $this->find('all', array('order' => 'lft ASC', 'fields' => array('id', 'ten_phong', 'thuoc_phong', 'lft', 'rght')));

		elseif(is_string($phong_id))

		{

			$results = $this->find('all', array('conditions' => array('id' => explode(",", $phong_id)), 'order' => 'lft ASC', 'fields' => array('id', 'ten_phong', 'thuoc_phong', 'lft', 'rght')));

		}

		else

		{

			$phong = $this->read(array('lft', 'rght'), $phong_id);

			$results = $this->find( 'all', array(

											'conditions'	=>	array(

												'lft  >=' 	=> 	$phong['Phong']['lft'],

												'rght <='	=>	$phong['Phong']['rght']),

											'order'			=>	'lft ASC',

											'fields' 		=> array('id', 'ten_phong', 'thuoc_phong', 'lft', 'rght')

											));

		}

		

		$stack = array();



		foreach ($results as $i => $result) 

		{

			while ($stack && ($stack[count($stack) - 1] < $result['Phong']['rght'])) {

				array_pop($stack);

			}

			$results[$i]['Phong']['tree_prefix'] = str_repeat('---', count($stack));

			$results[$i]['Phong']['padding']	 = 10*count($stack);

			$stack[] = $result['Phong']['rght'];

		}

		

		

		if (empty($results)) {

			return array();

		}

		return $results;

	}



	public	function	afterSave($created)

	{

		App::import( 'Model', 'Nhanvien' );

		$nhanvien = new Nhanvien();

		$nhanvien->deleteCachedConfig();

		$nhanvien->saveCachedConfig();

		

	}

	

	public	function afterDelete() 

	{

		App::import( 'Model', 'Nhanvien' );

		$nhanvien = new Nhanvien();

		$nhanvien->deleteCachedConfig();

    }

}

?>