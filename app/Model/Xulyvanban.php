<?php
/**
 * Xulyvanban
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
class Xulyvanban extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Xulyvanban';
	
	//public $tablePrefix = 'vanban_';
	
	public	$useTable = 'vanban_xuly';
	
	public	$belongsTo = array(
						'Nguoixuly'	=>	array('className'	=>	'Nhanvien',
											  'foreignKey'	=>	'nguoi_xuly_id',
											  'fields'		=>	'full_name')
							   );

}
?>