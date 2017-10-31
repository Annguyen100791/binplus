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
class PhongOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'PhongOld';
	
	public $useTable = 'Nhansu_phong';
	
	public 	$useDbConfig = 'old';
	
	public	$primaryKey = 'pID';
}
?>