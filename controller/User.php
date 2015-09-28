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
			$db = Mysql::getInstance()->insertOne($sql,$sqlParams);
			// $db2 = Mysql::getInstance('jpros')->insertOne($sql);
			
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