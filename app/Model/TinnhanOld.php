<?php
/**
 * Tinnhan
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
class TinnhanOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'TinnhanOld';
	
	public $useTable = 'Thongtin';
	
	public 	$useDbConfig = 'old';
	
	public	$primaryKey = 'thongtinID';
	
	public	$belongsTo = array(
							   'NguoiguiOld'	=>	array('className'=>	'NhanvienOld', 
													  'foreignKey'	=>	'thongtinNguoigoiID',
													  'fields'		=>	array('id_new')),
								'NguoinhanOld'	=>	array('className'=>	'NhanvienOld', 
													  'foreignKey'	=>	'thongtinNguoinhanID',
													  'fields'		=>	array('id_new')),				  
													  
							);
	
}
?>