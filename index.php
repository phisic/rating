<?php
ini_set('display_errors', 0);
// change the following paths if necessary
$yii='Yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

require_once('protected/components/WebApplication.php');
Yii::createApplication('WebApplication', $config)->run();
//test