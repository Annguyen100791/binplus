<?php
/**
 * Chinhsach
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
class Chinhsach extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Chinhsach';
	
	public $useTable = 'chinhsach_thongtin';
	
	public	$belongsTo = array(
							   'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
													  'foreignKey'	=>	'nguoigui_id',
													  'fields'		=>	array('full_name')));
	
	
}
?>