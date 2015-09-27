<?php
namespace base\core;

use base\core\_HTTP;

class Route extends Object{

	public static $routeInstance = null;
	public static $http ;

	public static function getInstance()
	{
		if(!isset(self::$routeInstance) )  
        {  
            self::$routeInstance = new self;  
        }  
        return self::$routeInstance; 
	}

	function __construct()
	{
		self::$http = new _HTTP();
	}

	public function run()
	{
		$_CclassName = CONTROLLER_DIR.'\\'.ucwords(self::$http->url->CV[0]);
		if(file_exists(APP_DIR.$_CclassName.PHP_EXT)){
			$_C = new $_CclassName;
			$action = self::$http->url->CV[1];
			if(!is_null($action) || method_exists($_C,$action)){
				$_C->$action();
			}else{
				die('方法不存在');
			}
		}else{
			//默认首页
			die('控制器不存在_请查看文件或者query_String');
		}
	}
}