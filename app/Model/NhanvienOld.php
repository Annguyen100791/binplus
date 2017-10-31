<?php
/**
 * Nhanvien
 *
 * PHP version 5
 *
 * @category Model
 * @package  BIN
 * @version  1.0
 * @author   Thanh Nguyen <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
class NhanvienOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public 	$name = 'NhanvienOld';
	
	public 	$useTable = 'Nhansu_nhanvien';
	
	public 	$useDbConfig = 'old';
	
	public	$primaryKey = 'nvID';
	

}
?>