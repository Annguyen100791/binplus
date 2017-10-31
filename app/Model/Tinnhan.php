<?php
/**
 * Tinnhan
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
class Tinnhan extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Tinnhan';
	
	public $useTable = 'tinnhan_thongtin';
	
	public	$belongsTo = array(
							   'Nguoigui'	=>	array('className'	=>	'Nhanvien', 
													  'foreignKey'	=>	'nguoigui_id',
													  'fields'		=>	array('full_name')));
	
	public	$hasMany = array(
							 'FileTinnhan'	=>	array('foreignKey'	=>	'tinnhan_id',
									  		  	     'className'	=>	'Filetinnhan'),
							 'Chitiettinnhan'=>	array('foreignKey'	=>	'tinnhan_id',
									  		  	     'className'	=>	'Chitiettinnhan'),
							 );
	
	
	
	public	function	delete($id = null, $cascade = true)
	{
		$files = $this->FileTinnhan->find('list', array('conditions' => array('tinnhan_id' => $id), 'fields' => array('id', 'file_path')));
		
		if(parent::delete($id, $cascade))
		{
			if(!empty($files))
				foreach($files as $k=>$v)
				{
					$file = str_replace("/", DS, Configure::read('TinNhan.attach_path'));
					$file = substr($file, 1, strlen($file)-1);
					$file = WWW_ROOT . $file;
					
					@unlink($file . $v);
				}
			return true;
		}
		return false;
	}
	
	
	
}
?>