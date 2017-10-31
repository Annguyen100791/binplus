<?php



if (file_exists(APP . 'Config' . DS . 'bin.php')) {

	require APP . 'Config' . DS . 'bin.php';

} else {

	if (!defined('LOG_ERROR')) {

		define('LOG_ERROR', 0);

	}



	Configure::write('Error', array(

		'handler' => 'ErrorHandler::handleError',

		'level' => E_ALL & ~E_DEPRECATED,

		'trace' => true

		));



	Configure::write('Exception', array(

		'handler' => 'ErrorHandler::handleException',

		'renderer' => 'ExceptionRenderer',

		'log' => true

		));

}

