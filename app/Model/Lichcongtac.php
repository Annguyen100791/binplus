<?php
/**
 * Lichcongtac
 *
 * PHP version 5
 *
 * @category Model
 * @package  CMS
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Lichcongtac extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Lichcongtac';
	
	public $useTable = 'congtac_thongtin';
	
	public $hasAndBelongsToMany = array(
        'Nhanvien' =>
            array(
                'className'              => 'Nhanvien',
                'joinTable'              => 'congtac_nguoidi',
                'foreignKey'             => 'congtac_id',
                'associationForeignKey'  => 'nguoidi_id',
                'unique'                 => true,
                'conditions'             => '',
                'fields'                 => array('id', 'full_name'),
                'order'                  => '',
                'limit'                  => '',
                'offset'                 => '',
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            )
    );
	
}
?>