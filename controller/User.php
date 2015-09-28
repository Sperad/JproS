<?php
namespace controller;

use base\db\Mysql;
use base\Controller;
use base\helper\Json;

class User extends Controller{

	public function signUp()
	{
		if($this->method == 'POST')
		{
			//相数据库写数据
			$sql = " insert into dntk_chat_user (nickname,password,sex,birthday) value ".
						"(:a,:b,:c,:birthday );";
			$sqlParams = array('111','111','1','1443152918');
			$sqlParams2 = array('nickname'=>'我等我期待','password'=>'111','sex'=>1,'birthday'=>'1443152918');
			/*
				$my = new Mysql();
				$r = $my->insert('dntk_chat_user',$sqlParams2);
				echo $my->_sql;
			*/
			
			//接口Json API
			// $a =  Json::J2Arr($this->body);
			// var_dump($a);
		}
		if($this->method == 'GET')
		{
			//view层
			$this->loadView();
			// $this->loadView('user/login');
		}
	}
}