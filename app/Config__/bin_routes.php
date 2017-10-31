<?php
	CakePlugin::routes();
	Router::parseExtensions('json', 'rss');

	// Basic
	BinRouter::connect('/', array('controller' => 'pages', 'action' => 'home'));

	Router::connectNamed(array('replyto', 'sendto', 'forwardto', 'resendto'));


	// Mobile
	BinRouter::connect('/mobile', array('controller' => 'pages', 'action' => 'home', 'mobile' => true));


	//API
	Router::connect('/api/auth',array('controller' => 'users', 'action' => 'apiauthenticate'));
