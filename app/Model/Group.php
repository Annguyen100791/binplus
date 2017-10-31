<?php
/**
 * GROUP
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo
 * @version  1.0
 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Group extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Group';
	
	public $tablePrefix = 'nhansu_';
	
	public	$hasMany = array(
				'GroupNhanvien'	=>	array('foreignKey'	=>	'group_id')
							 );
	
	public $actsAs = array(
        'Ordered' => array(
            'field'		  => 'thu_tu',
            'foreign_key' 	=> 'user_id',
        )
    );

}
?>