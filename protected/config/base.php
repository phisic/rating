<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	// preloading 'log' component
	'preload'=>array('log'),

	'modules'=>array(
            'user'=>array(
                # encrypting method (php hash function)
                'hash' => 'md5',

                # send activation email
                'sendActivationMail' => true,

                # allow access for non-activated users
                'loginNotActiv' => false,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,

                # automatically login from registration
                'autoLogin' => true,

                # registration path
                'registrationUrl' => array('/user/registration'),

                # recovery password path
                'recoveryUrl' => array('/user/recovery'),

                # login form path
                'loginUrl' => array('/user/login'),

                # page after login
                'returnUrl' => array('/user/profile'),

                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            
                'class'=>'WebUser',
            ),
	),

	// application components
	'components'=>array(
        'helper'=>array(
            'class'=>'Helper',
        ),
        'urlManager' => array(
                    'urlFormat' => 'path',
                    'showScriptName' => 0,
            ),
        'search' => array(
            'class' => 'application.components.DGSphinxSearch.DGSphinxSearch',
            'server' => '127.0.0.1',
            'port' => 3313,
            'maxQueryTime' => 3000,
            'enableProfiling'=>0,
            'enableResultTrace'=>0,
            'fieldWeights' => array(
                'name' => 10000,
                'keywords' => 100,
            ),
        ),
       
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=rating',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
            'enableProfiling'=>true,
            'schemaCachingDuration' => 3600,
            'enableParamLogging'=>true,
		),
		
        'cache'=>array(
            'class'=>  'CFileCache',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'murod9@gmail.com',
        'robotEmail'=>'laptoptop7@laptoptop7.com',
        
	),
    
);