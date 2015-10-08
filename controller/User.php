<?php
namespace controller;

use base\Controller;
use base\db\Mysql;
use base\helper\Json;
use base\web\Session;

class User extends Controller{

	public function signUp()
	{
		if(!$this->isAjax && $this->method == 'POST')
		{
			//相数据库写数据
			$user = $_POST;
			$user['password'] = md5($user['password']);
			$user['birthday'] = strtotime($user['birthday']);
			unset($user['repassword']);
			$my = new Mysql();
			$userOnly = "select count(1) as ctn from dntk_chat_user where nickname = '$user[nickname]'";
			$cnt = $my->count($userOnly);//
			if(empty($cnt['ctn'])){//未注册
				if($my->insert('dntk_chat_user',$user))
				{//注册成功,自动登陆
					Session::set('userId',$my->lastInsertId());
					Session::set('nickName',$user['nickname']);
					echo "<script>alert('注册成功');window.location.href='index.php?User_panel'</script>";
				}
			}else{//已经注册
				echo "<script>alert('已经注册');window.location.href='index.php?User_signUp'</script>";
			}
		}

		if($this->method == 'GET' && !$this->isAjax)
		{
			$this->loadView('this'); //或者 $this->loadView('user/login');
		}
	}

	public function login()
	{
		// echo $obj->getMap('use');
		// $obj->close();
		// echo $obj->getMap('use');
		// var_dump($_SESSION);
		if(!$this->isAjax && $this->method == 'POST')
		{
			$user = $_POST;
			$user['password'] = md5($user['password']);
			$my = new Mysql();
			if($_user = $my->hasOne('dntk_chat_user',$user))
			{
				Session::set('userId',$_user['id']);
				Session::set('nickName',$user['nickname']);
				echo "<script>alert('登录成功');window.location.href='index.php?User_panel'</script>";
			}
		}
		if($this->method == 'GET' && !$this->isAjax)
		{
			$this->loadView('this'); //或者 $this->loadView('user/login');
		}
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
		$name = Session::get('nickName');
		$list = array('contact'=>'联系客服',
					  '组名称_1'=>array('成员11'=>'id','成员12'=>'id','成员13'=>'id','成员14'=>'id'),
					  '组名称_2'=>array('成员21'=>'id','成员22'=>'id','成员23'=>'id','成员24'=>'id'),
					  // '组名称_3'=>array('成员31'=>'id','成员32'=>'id','成员33'=>'id','成员34'=>'id'),
					  // '组名称_4'=>array('成员41'=>'id','成员42'=>'id','成员43'=>'id','成员44'=>'id'),
					  // '组名称_5'=>array('成员51'=>'id','成员52'=>'id','成员53'=>'id','成员54'=>'id'),
						);
		$this->loadView('this',array('name'=>$name,'list'=>$list));
	}
}
//接口Json API
// $a =  Json::J2Arr($this->body);
// var_dump($a);