<?php
/**
 * Congviec
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Congviec extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Congviec';
	
	public $useTable = 'congviec_thongtin';
	
	public $actsAs = array(
        'Tree',
    );
	
	
	public	$belongsTo = array(
		'NguoiNhanviec'	=>	array('className' 	=> 'Nhanvien', 'foreignKey' 	=> 'nguoinhan_id', 'fields' => 'full_name'),
		'NguoiGiaoviec'	=>	array('className' 	=> 'Nhanvien', 'foreignKey' => 'nguoi_giaoviec_id', 'fields' => 'full_name')
		//'Filedinhkem'	=>	array('className' 	=> 'Filecongviec', 'foreignKey' => 'congviec_id')
							   );
	public	$progress = array(0	=>	'0%',
							   1	=>	'10%',
							   2	=>	'20%',
							   3	=>	'30%',
							   4	=>	'40%',
							   5	=>	'50%',
							   6	=>	'60%',
							   7	=>	'70%',
							   8	=>	'80%',
							   9	=>	'90%',
							   10	=>	'100%');

	
	/*
	public	$hasMany = array(
				'Chitietcongviec'		=>	array(
									'foreignKey'	=>	'congviec_id',
									'order'			=>	'lft ASC'),
				//'Theodoivanban'	=>	array('foreignKey'	=>	'vanban_id'),
							 );
	
	
	public	$belongsTo = array(
			'Vanban'			=>	array('className'	=>	'Vanban', 'fields' => 'trich_yeu'),
			'Tinhchatcongviec'	=>	array('className'	=>	'Tinhchatcongviec', 'foreignKey' => 'tinhchat_id')
							   );
	*/
	
	public function generateTree($congviec_id) 	
	{
		$congviec = $this->read(array('id', 'lft', 'rght'), $congviec_id);
		$this->recursive = 1;
		$results = $this->find('all', array('conditions' => array(
					'Congviec.lft >='  => $congviec['Congviec']['lft'],
					'Congviec.rght <=' => $congviec['Congviec']['rght']
					), 'order' => 'lft ASC'));
		
		$stack = array();

		foreach ($results as $i => $result) 
		{
			while ($stack && ($stack[count($stack) - 1] < $result['Congviec']['rght'])) {
				array_pop($stack);
			}
			$results[$i]['Congviec']['tree_prefix'] = str_repeat('---', count($stack));
			$results[$i]['Congviec']['padding']	 = 10*count($stack);
			$stack[] = $result['Congviec']['rght'];
		}
		
		
		if (empty($results)) {
			return array();
		}
		return $results;
	}
	
}
?>