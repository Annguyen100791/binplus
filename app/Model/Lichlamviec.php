<?php
/**
 * Lichlamviec
 *
 * PHP version 5
 *
 * @category Model
 * @package  CMS
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Lichlamviec extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Lichlamviec';
	
	public $useTable = 'lich_thongtin';
	
	public	$pham_vi	= array("0"	=>	'Toàn đơn vị',
								"1"	=>	'Phòng ban',
								"2"	=>	'Cá nhân',
								"3"	=>	'Theo nhóm');

}
?>