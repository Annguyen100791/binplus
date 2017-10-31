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
class Vanban extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Vanban';
	
	public $useTable = 'vanban_thongtin';

	public	$chieu_di = array(0	=>	'Đi', 1	=>	'Đến', 2 	=> 'Nội bộ');
	
	/*
	public	$hasMany = array(
				'Nhanvanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Theodoivanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Filevanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Xulyvanban'	=>	array('foreignKey'	=>	'vanban_id'),
				'Luuvanban'	=>	array('foreignKey' => 'vanban_id')
							 );
	
	
	public	$belongsTo = array(
			'Loaivanban'	=>	array('className'	=>	'Loaivanban'),
			'Tinhchatvanban'=>	array('className'	=>	'Tinhchatvanban'),
							   );
	*/
	public	function	delete($id = null, $cascade = true)
	{
		$this->Id = $id;
		App::import( 'Model', 'Filevanban' );
		$file = new Filevanban();
		$files = $file->find('all', array('conditions' => 'vanban_id=' . $id, 'fields' => 'path'));
		if(parent::delete($id, $cascade))
		{
			
			if(!empty($files))
			{
				$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
				$path = substr($path, 1, strlen($path)-1);
				$path = WWW_ROOT . $path;
				foreach($files as $item)
					@unlink($path . $item['Filevanban']['path']);
			}
			return true;
		}
		return false;
	}
	
	public	$progress = array(0	=>	'0%',
							   1	=>	'10%',
							   2	=>	'20%',
							   3	=>	'30%',
							   4	=>	'40%',
							   5	=>	'50%',
							   6	=>	'60%',
							   7	=>	'70%',
							   8	=>	'80%',
							   9	=>	'90%',
							   10	=>	'100%');
	
}
?>