<?php
$backend=dirname(dirname(__FILE__));
$frontend=dirname($backend);
Yii::setPathOfAlias('backend', $backend);

return CMap::mergeArray(
    // наследуемся от main.php
    require($frontend.'/config/main.php'),
	array(
	    'basePath'=>$frontend,
	    //'homeUrl'=>array('site/contact'),
	    'name'=>'Administrator',
            'theme' => 'backend',
	    // Настраиваем пути до основных компонентов нашего backend
	    'controllerPath' => $backend.'/controllers',
	    'viewPath' => $backend.'/views',
	    'runtimePath' => $backend.'/runtime',
	    //'modulePath'=>$backend.'/modules',


	    // autoloading model and component classes
	    'import'=>array(
	        'application.models.*',
	        'application.components.*',
	        'backend.models.*',
	        'backend.components.*',
	    ),

	    //'defaultController'=>'post',
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
                'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
            ),
	    // application components
	    'components'=>array(
	        /*'user'=>array(
	            // enable cookie-based authentication
	            'allowAutoLogin'=>true,
	            'loginUrl' => array('/user/login'),
	        ),*/
	        'errorHandler'=>array(
	            // use 'site/error' action to display errors
	            'errorAction'=>'site/error',
	        ),
	        'urlManager'=>array(
	            'urlFormat'=>'path',
	            'rules'=>array(
	                // Убираем расширение .php из заголовка.(настройка файла .htaccess далее)
	                'admin'=>'site/index',
	                'admin/<_c>'=>'<_c>',
	                'admin/<_c>/<_a>'=>'<_c>/<_a>',
	            ),
	        ),
	    ),
	)
);