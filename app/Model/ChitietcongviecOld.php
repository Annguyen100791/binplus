<?php
/**
 * Chitietcongviec
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
class ChitietcongviecOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'ChitietcongviecOld';
	
	//public $tablePrefix = 'vanban_';
	
	public	$useTable = 'old_congviec_chitiet';
	
	public $actsAs = array(
        'Tree',
    );
	
	
	public function generateTree($congviec_id) 	
	{
		$this->recursive = 1;
		$results = $this->find('all', array('conditions' => array('congviec_id' => $congviec_id), 'order' => 'lft ASC'));
		
		$stack = array();

		foreach ($results as $i => $result) 
		{
			while ($stack && ($stack[count($stack) - 1] < $result['ChitietcongviecOld']['rght'])) {
				array_pop($stack);
			}
			$results[$i]['ChitietcongviecOld']['tree_prefix'] = str_repeat('---', count($stack));
			$results[$i]['ChitietcongviecOld']['padding']	 = 10*count($stack);
			$stack[] = $result['ChitietcongviecOld']['rght'];
		}
		
		
		if (empty($results)) {
			return array();
		}
		return $results;
	}

}
?>