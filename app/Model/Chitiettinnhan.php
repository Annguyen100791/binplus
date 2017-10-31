<?php
/**
 * Chitiettinnhan
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
class Chitiettinnhan extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Chitiettinnhan';
	
	public $useTable = 'tinnhan_nguoinhan';
	
	public	$belongsTo = array(
						'Nguoinhan'	=>	array('className'	=>	'Nhanvien',
											  'foreignKey'	=>	'nguoinhan_id',
											  'fields'		=>	'full_name')
							   );
	
}
?>