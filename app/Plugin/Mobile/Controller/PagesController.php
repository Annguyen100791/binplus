<?php

/**
 * Pages Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class PagesController extends MobileAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Pages';

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array();


	public	function	home()
	{
		die('Mobile.Home');
	}

}
