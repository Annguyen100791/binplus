<?php
/**
 * CongviecComment
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
class CongviecComment extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'CongviecComment';
	
	public $useTable = 'congviec_comments';
	
	public	$belongsTo = array(
		'NguoiBinhluan'	=>	array('className' 	=> 'Nhanvien', 'foreignKey' 	=> 'nguoibinhluan_id', 'fields' => 'full_name'),
   );
	
}
?>