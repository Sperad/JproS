<?php
define('APP_DIR', __DIR__.'/');

require APP_DIR.'/base/ClassLoad.php';

$initParams = [
				'config'  	  =>  APP_DIR . 'conf',
				'core'  	  =>  APP_DIR . 'base',
				'controller'  =>  APP_DIR . 'controller',
			  ];

//加载所有文件,并自动注册
use base\ClassLoad;
$loader = ClassLoad::ClassAllLoad($initParams);

$loader->run();

// use base\web\Crul;
// Crul::send2();