<?php
/**
 * CongviecNhatky
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
class CongviecNhatky extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'CongviecNhatky';
	
	public $useTable = 'congviec_nhatky';
	
	public	$belongsTo = array(
		'NguoiCapnhat'	=>	array('className' 	=> 'Nhanvien', 'foreignKey' 	=> 'nguoi_capnhat', 'fields' => 'full_name'),
   );
}
?>