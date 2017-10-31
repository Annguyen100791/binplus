<?php
class LoaiVanbanOld extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public 	$name = 'LoaiVanbanOld';
	
	public 	$useTable = 'Vanban_loai';
	
	public 	$useDbConfig = 'old';
	
	public	$primaryKey = 'lvbID';
}
?>