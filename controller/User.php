<?php
namespace controller;

use base\Controller;
use base\db\Mysql;
use base\helper\Json;

class User extends Controller{

	public function signUp()
	{
		if(!$this->isAjax && $this->method == 'POST')
		{
			//相数据库写数据
			$sql = " insert into dntk_chat_user (nickname,password,sex,birthday) value ".
						"(:a,:b,:c,:birthday );";
			$sqlParams = array('111','111','1','1443152918');
			$sqlParams2 = array('nickname'=>'我等我期待','password'=>'111','sex'=>1,'birthday'=>'1443152918');
			$user = $_POST;
			$user['password'] = md5($user['password']);
			$user['birthday'] =  strtotime($user['birthday']);
			unset($user['repassword']);
			$my = new Mysql();
			$userOnly = "select count(1) as ctn from dntk_chat_user where nickname = '$user[nickname]'";
			$cnt = $my->count($userOnly);//
			if(empty($cnt)){//未注册
				if($my->insert('dntk_chat_user',$user))
				{//注册成功,自动登陆
					$obj = self::$Session; 
					$obj->setMap('userId',$my->lastInsertId());
					$obj->setMap('userName',$user['nickname']);
					echo "<script>alert('注册成功');window.location.href='index.php?User_signUp'</script>";
				}
			}else{//已经注册
				echo "<script>alert('已经注册');window.location.href='index.php?User_signUp'</script>";
			}
		}

		if($this->method == 'GET' && !$this->isAjax)
		{
			$this->loadView(); //或者 $this->loadView('user/login');
		}
	}

	public function login()
	{
		// echo $obj->getMap('use');
		$obj->close();
		echo $obj->getMap('use');
		// var_dump($_SESSION);
	}

	public function Group()
	{
		//ajax 添加组名
		if($this->isAjax && $this->method == 'POST')
		{
			$obj = self::$Session; 
			$name = $obj->getMap('userId');
		}
	}

	public function panel()
	{
		// $obj = self::$Session; 
		// $name = $obj->getMap('userName');
		$name = 'sperad';
		$list = array('a'=>"b",'aa'=>'bb');
		$this->loadView('this',array($name,$list));
	}
}
//接口Json API
// $a =  Json::J2Arr($this->body);
// var_dump($a);