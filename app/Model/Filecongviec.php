<?php
/**
 * Filecongviec
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
class Filecongviec extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Filecongviec';
	public	$useTable = 'congviec_files';
	public	function	delete($id = null, $cascade = true)
	{
		$this->Id = $id;
		$file = $this->field('path');
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