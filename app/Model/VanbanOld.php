<?php
/**
 * Vanban
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
class VanbanOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'VanbanOld';
	
	public $useTable = 'Vanban_thongtin';
	
	public 	$useDbConfig = 'old';
	
	public	$primaryKey = 'vbID';

	
	public	$hasMany = array(
				'NhanvanbanOld'	=>	array('foreignKey'	=>	'nvbVanbanID'),
				'FilevanbanOld'	=>	array('foreignKey'	=>	'fileVanbanID'),
				'XulyvanbanOld'	=>	array('foreignKey'	=>	'xlvbVanbanID'),
							 );
	
	
	public	$belongsTo = array(
			//'LoaiVanbanOld'	=>	array('className'	=>	'LoaiVanbanOld', 'foreignKey' => 'vbLoaiID'),
			//'TinhchatVanbanOld'=>	array('className'	=>	'TinhchatVanbanOld', 'foreignKey' => 'vbTinhchatID'),
			'NguoinhapID'	=>	array('className'	=>	'NhanvienOld', 'foreignKey' => 'vbNguoinhapID', 'fields' => 'id_new'),
			'NoiluuOld'		=>	array('className'	=>	'PhongOld', 'foreignKey' => 'vbLuugoc', 'fields' => array('id_new'))
							   );
	

}
?>