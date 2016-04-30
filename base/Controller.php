<?php
namespace base;
use base\web\HTTP;
use base\web\Route;
use base\web\Template;
use base\web\Session;

class Controller extends HTTP{

	protected $user = array();
	public function __construct()
	{
		parent::__construct();
	}

	public function loadView($viewPath = 'this', $val = array())
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
		$tpl = new Template($viewPath,$val);
		$tpl->outPage();
	}

	public function goPage($url, $msg = '')
	{
		if($msg){
			echo "<script>alert('$msg');window.location.href='$url'</script>";
		}else{
			echo "<script>window.location.href='$url'</script>";
		}
		exit;
	}

	/*是否登录*/
	public function isLogin(){
		$this->user = Session::get('user');
		if(empty($this->user)){
			$this->goPage('/');
		}
		return true;
	}
}