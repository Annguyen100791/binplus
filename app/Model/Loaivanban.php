<?php
/**
 * Loaivanban
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
class Loaivanban extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Loaivanban';
	
	public $tablePrefix = 'vanban_';

	public	$chieu_di = array(0	=>	'Đi', 1	=>	'Đến');
}
?>