<?php
namespace controller;
use base\core\MysqlDB;
use base\core\Controller;

class User extends Controller{

	public function signUp()
	{
		//view层
		// $this->loadView('user/login');
		$this->loadView();
	}
}