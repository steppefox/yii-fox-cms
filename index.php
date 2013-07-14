<?php
ini_set('display_errors',1);
//date_default_timezone_set('Europe/Amsterdam'); // TODO: change this accordingly
// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/framework/yii.php'; // <- download yii framework 1.1.x and place it in this directory

$configMain= require dirname(__FILE__).'/protected/config/config.main.php';
$configLocal = array();
if(file_exists(dirname(__FILE__).'/protected/config/config.local.php')){
        $configLocal= require dirname(__FILE__).'/protected/config/config.local.php';
}


// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$config = CMap::mergeArray($configMain,$configLocal);
Yii::createWebApplication($config)->run();
