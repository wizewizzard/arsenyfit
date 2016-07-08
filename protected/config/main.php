<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Arseny fit',

	// preloading 'log' component
	'preload'=>array(
    'log',
    'config',
    
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('5.3.232.60','::1'),
		),
        'admin',
		
	),
    
	// application components
	'components'=>array(
        'cache' => array(
        'class' => 'system.caching.CDbCache',
        ),
		'user'=>array(
			'class' => 'WebUser',
			'allowAutoLogin'=>true,
		),
        'config'=>array(
            'class' => 'DConfig',
        ),
		"cart" =>array(
			"class"=>"application.components.Cart",
		),
        'session' => array (
            //'sessionName' => 'Site Session',
            'class'=>'CHttpSession',
            'autoStart'=>true,
            /*'cookieMode' => 'only',*/
            'timeout' => 10
        ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
				//'<shop>/<item>' => '<shop>/<item>',
				//'shop/<action:view|item>'=>'shop/<action>',
				/*'shop/view/<cid:\d+>/<sid:\d+>' => 'shop/view',
				'shop/view/<tid:\d+>' => 'shop/view',
				'shop/item/<iid:\d+>' => 'shop/item',
				'shop/view' => 'shop/view',*/

				//''=>'site/index',
				/*'gallery/<page:\d+>'=>'gallery/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/index',

				'<controller:\w+>'=>'<controller>/index',
				'<action:\w+>'=>'site/<action>',
				'<action:\w+>/<id:\d+>'=>'site/<action>',*/

				'<controller:\w+>/<id:\d+>'=>'<controller>/<index>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				//'<action:\w+>'=>'site/<action>',


				//'cart/<action:\w+>/<id:\d+>/<count:\d+>' => 'cart/<action>',
               // '<controller:\w+>/<action:\w+>/<id:\d+>/<count:\d+>' => '<controller>/<action>',
			),
             
		),
		

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),
    //'onBeginRequest' => array('SessionHelper', 'initSession'),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
