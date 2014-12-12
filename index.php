<?php
//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 force cache refresh
//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // date in the past
// change the following paths if necessary
ini_set('include_path',dirname(__FILE__).'/pear');
//var_dump($_SERVER['DOCUMENT_ROOT'].'/framework/yii.php');exit();
$yii=$_SERVER['DOCUMENT_ROOT'].'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
//var_dump(Yii::getPathOfAlias("ext.bootstrap"));exit();
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',2);

require_once ('pear/Spreadsheet/Excel/Writer.php');
require_once($yii);
Yii::createWebApplication($config)->run();
