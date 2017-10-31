<?php
/**
 * BinRouter
 *
 * NOTE: Do not use this class as a substitute of Router class.
 * Use it only for CroogoRouter::connect()
 *
 * @package  BIN
 * @version  1.0
 * @author   Thạnh Nguyễn <dinhthanh79@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.ptc.com.vn
 */
class BinRouter {

	public static function connect($route, $default = array(), $params = array()) {
		Router::connect($route, $default, $params);
		if ($route == '/') {
			$route = '';
		}
	}


/**
 * Load plugin routes
 *
 * @return void
 */
	public static function plugins() {
		$pluginRoutes = Configure::read('Hook.routes');
		if (!$pluginRoutes || !is_array(Configure::read('Hook.routes'))) {
			return;
		}

		$plugins = Configure::read('Hook.routes');
		foreach ($plugins as $plugin) {
			$path = App::pluginPath($plugin) . 'Config' . DS . 'routes.php';
			if (file_exists($path)) {
				include $path;
			}
		}
	}
}
