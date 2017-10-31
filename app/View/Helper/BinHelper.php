<?php
/**
 * Bin Helper
 *
 * PHP version 5
 *
 * @category Helper
 * @package  BIN
 * @version  1.0
 * @author   Thanh Nguyen <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
class BinHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    public $helpers = array(
        /*'Html',
        'Form',
        'Session',
        'Js',*/
    );
	
	public	function	hidetimelabel( $filename )
	{
		$pos = strpos($filename, '_');
		
		if($pos < 0)	return $filename;
		
		return substr( $filename, $pos + 1,  strlen( $filename ) - $pos - 1 );
	}
}
?>
