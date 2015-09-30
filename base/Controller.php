<?php
namespace base;
use base\web\HTTP;
use base\web\Session;
use base\web\Route;
use base\web\Template;

class Controller extends HTTP{

	public static $Session;

	public function __construct()
	{
		parent::__construct();
		static::$Session = Session::getInstance();
	}

	public function loadView($viewPath = null, $val = null)
	{
		if($viewPath == 'this' )
		{
			$view = Route::$http->url->CV[1];
			if(in_array($view,get_class_methods($this)))
			{
				$class = explode('\\',get_class($this));
				$viewPath = $class[count($class)-1].'/'.$view;
			}
		}
		new Template($viewPath,$val);
	}
}