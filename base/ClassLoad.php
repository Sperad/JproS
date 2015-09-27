<?php
namespace base;

class ClassLoad 
{
	private static $_classMap = array();
	public static function ClassAllLoad($initParams = null)
	{
		if(is_null($initParams))
			die('无系统初始化参数');
		$loader = new self;
		$loader->initMap($initParams);
		return $loader;
	}

	public function initMap($initParams)
	{
		if(array_key_exists('config',$initParams) /*必须有*/
			and  array_key_exists('core',$initParams) 
				and $this->autoDefineConfig($initParams['config'])
					and $this->autoSetClassMap($initParams['core'])
		){
			//添加控制类
			array_key_exists('controller',$initParams) and $this->autoSetClassMap($initParams['controller']);
			spl_autoload_register(array(__CLASS__,'autoRegister'));
		}else{
			die('系统初始化失败');
		}

	}

	public function autoDefineConfig($path)
	{
		autoDir($path,$file);
		array_walk($file,function($pathName,$index){
			$content = include $pathName;
			foreach ($content as $k => $v)
			{
				if(preg_match('/const/',$pathName))
					define($k, $v);
				if(preg_match('/global/',$pathName))
					$GLOBALS[$k] = $v;
			}
		});
		return true;
	}

	/**
	 * 通过回调函数 获取所有的类名称
	 */
	private function autoSetClassMap($path)
	{
		autoDir($path,$fileList);
		foreach ($fileList as &$f) {
			$classPath =  preg_replace('/'.addslashes( rtrim(APP_DIR,'/') ).'/','',$f);
			$classPath =  preg_replace('/\//','\\', $classPath);
			$className = basename($classPath,PHP_EXT);
			self::$_classMap[$className] = $f;
		}
		return true;
	}


	// $namespace =  self::$_classMap[$className].$className; use 不能在局部定义
	public static function autoRegister($classNamespace)
	{
		//自动注册类
		$className = basename($classNamespace);
		// echo $className.'<br />';
		if(array_key_exists($className, self::$_classMap))
			require self::$_classMap[$className];
	}
}

function autoDir($path, &$_dirMap)
{
	if(is_dir($path))
	{
		$dp = opendir($path);
		while( false != ($file = readdir($dp)) ) 
		{
			if($file == '.' || $file == '..') 
				continue;
			$configPath = $path.'/'.$file;
			if(is_dir($configPath)){
				autoDir($configPath,$_dirMap);
			}
			if(is_file($configPath))
			{
				$_dirMap[] = $configPath;
			}
		}
	}else{
		return false;
	}
}