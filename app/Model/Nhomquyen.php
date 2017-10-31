<?php
/**
 * Chucdanh
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
class Nhomquyen extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Nhomquyen';
	
	public $tablePrefix = 'sys_';
	
	public $hasAndBelongsToMany = array(
        'Quyen' =>
            array(
                'className'              => 'Quyen',
                'joinTable'              => 'sys_quyen_nhomquyen',
                'foreignKey'             => 'nhomquyen_id',
                'associationForeignKey'  => 'quyen_id',
                'unique'                 => true,
                'conditions'             => '',
//                'fields'                 => '',
                'order'                  => 'Quyen.tu_khoa ASC'
            )
    );
	
	public	$pham_vi = array(
							'0'	=>	'Toàn đơn vị',
							'1'	=>	'Phòng đang công tác',
							'2'	=>	'Cá nhân');
	
	public	function	dsQuyen($nhomquyen_id)
	{
		$data = $this->read(null, $nhomquyen_id);
		
		$ds = array();
		
		if(!empty($data['Quyen']))
			foreach($data['Quyen'] as $quyen)
			{
				$name = substr($quyen['tu_khoa'], 0, strpos($quyen['tu_khoa'], '.'));
				$ds[$name][substr($quyen['tu_khoa'], strpos($quyen['tu_khoa'], '.') + 1, strlen($quyen['tu_khoa'])-1)] = $quyen['ten_quyen'];
			}		
		return $ds;
	}
	
	public	function	dsQuyen2Array($data)
	{
		if(!empty($data['Quyen']))
			foreach($data['Quyen'] as $quyen)
			{
				$name = substr($quyen['tu_khoa'], 0, strpos($quyen['tu_khoa'], '.'));
				$ds[$name][substr($quyen['tu_khoa'], strpos($quyen['tu_khoa'], '.') + 1, strlen($quyen['tu_khoa'])-1)] = $quyen['ten_quyen'];
			}		
		return $ds;
	}
	
	

}
?>