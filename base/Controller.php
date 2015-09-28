<?php
namespace base;
use base\web\HTTP;
use base\web\Route;
use base\web\Template;

class Controller extends HTTP{

	public function loadView($viewPath = null)
	{
		if(is_null($viewPath))
		{
			$view = Route::$http->url->CV[1];
			if(in_array($view,get_class_methods($this)))
			{
				$class = explode('\\',get_class($this));
				$viewPath = $class[count($class)-1].'/'.$view;
			}
		}
		new Template($viewPath);
	}

	public function getBD()
	{
		
	}

}