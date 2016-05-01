<?php
namespace controller;
use base\Controller;
use base\View;
use base\db\Mysql;
use base\helper\Json;
use base\web\Session;

class Index extends Controller
{
	public function default()
	{
		$my = new Mysql();
		$sql = "select u.id, u.nickname, i.sex, i.motto, i.address, i.birthday ,i.blood_type, i.school ".
					"from chat_user u  LEFT JOIN chat_user_info i ON u.id = i.user_id limit 0,9 ";
		$userList = $my->doSql($sql);
		return new View('Index/default', array('userList'=>$userList));
	}

	public function register()
	{
		if($this->method == 'POST') {
			$user = $_POST;  $user['password'] = md5($user['password']);
			$my = new Mysql();
			$cnt = $my->count("select count(1) as cnt from chat_user where nickname = '$user[nickname]'");
			if(!empty($cnt['cnt']))
				$this->goPage('Index_default','已经注册');
			$user['online'] = 1;
			if($my->insert('chat_user',$user)) { //注册成功,自动登陆
				//默认一个组(好友)
				$group['group_name'] = '好友';
				$group['create_by'] = $my->lastInsertId();
				$my->insert('chat_group',$group);
				$_user = $my->field("id,nickname,online")
							->where(array('nickname'=>$user['nickname']))
							->select('chat_user');
				Session::set('user',$_user[0]);
				$this->goPage('/User_center','注册成功');
			}
		}
	}

	public function login()
	{
		if($this->method == 'POST'){
			$user = $_POST;
			$user['password'] = md5($user['password']);
			$my = new Mysql();
			if($_user = $my->hasOne('chat_user',$user)) {
				unset($_user['password']);
				Session::set('user',$_user);
				$this->goPage('/User_center','登录成功');
			}else{
				$this->goPage('/','未注册');
			}
		}
	}
}