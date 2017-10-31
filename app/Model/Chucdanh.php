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
class Chucdanh extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Chucdanh';
	
	public $tablePrefix = 'nhansu_';
	
	public $actsAs = array(
        'Ordered' => array(
            'field'		 	=> 'thu_tu',
            'foreign_key' 	=> false,
        )
    );

}
?>