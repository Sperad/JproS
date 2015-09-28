<?php

namespace controller;
use base\db\Mysql;
use base\Controller;

class User extends Controller{

	public function signUp()
	{
		if($this->method == 'POST')
		{
			//相数据库写数据
			$sql = "insert into dntk_chat_user (nickname,password,sex,birthday) value ('111','111',1,'1443152918' );";
			// $db = Mysql::getInstance()->insertOne($sql);
			// $db2 = Mysql::getInstance('jpros')->insertOne($sql);
		}
		if($this->method == 'GET')
		{
			//view层
			$this->loadView();
			// $this->loadView('user/login');
		}
	}
}