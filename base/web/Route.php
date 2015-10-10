<?php
namespace base\web;
use base\ClassLoad;

class Route extends WebObject{

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
		self::$http = new Http();
	}

	/**
	 * [run 应用App 运行入口]
	 * new 控制层 的类对象，并执行相应的方法
	 * CV[0] 控制器类名(controller)， CV[1] 方法名(View)
	 */
	public function run()
	{
		$_CclassName = ucwords(self::$http->url->CV[0]);
		if(in_array($_CclassName, ClassLoad::$_classMap))
		{
			$_CclassName = CONTROLLER_DIR.'\\'.$_CclassName;
			$_C = new $_CclassName;
			$action = self::$http->url->CV[1];
			if(!is_null($action) && method_exists($_C,$action)){
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