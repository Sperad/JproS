<?php
define('APP_DIR', __DIR__.'/');

require APP_DIR.'/base/ClassLoad.php';

$initParams = [
				'config'  	  =>  APP_DIR . 'conf',
				'core'  	  =>  APP_DIR . 'base/core',
				'controller'  =>  APP_DIR . 'controller',
			  ];

//加载所有文件,并自动注册
use base\ClassLoad;
ClassLoad::ClassAllLoad($initParams);

//路由分发
use base\core\Route;
// $r = Route::getInstance();
$r = Route::getInstance()->run();