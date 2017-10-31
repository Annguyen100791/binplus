<?php
/**
 * Filetraodoi
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
class Filetraodoi extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Filetraodoi';
	
	//public $tablePrefix = 'vanban_';
	
	public	$useTable = 'vanban_filestraodoi';
	
	public	function	delete($id = null, $cascade = true)
	{
		$this->Id = $id;
		$file = $this->field('ten_cu');
		if(parent::delete($id, $cascade))
		{
			if(!empty($file))
			{
				$path = str_replace("/", DS, Configure::read('VanBan.attach_path'));
				$path = substr($path, 1, strlen($path)-1);
				$path = WWW_ROOT . $path . $file;
				@unlink($path);
			}
			return true;
		}
		return false;
	}
	

}
?>