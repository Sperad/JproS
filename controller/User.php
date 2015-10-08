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

	/**
	 * 添加组
	 */
	public function Group()
	{
		//ajax 添加组名
		if($this->isAjax && $this->method == 'POST')
		{
			$group = $_POST;
			$my = new Mysql();
			$groupOnly = "select count(1) as ctn from dntk_chat_group where group_name = '$group[groupName]'";
			$cnt = $my->count($groupOnly);//
			if(empty($cnt['ctn'])){//未
				$group['group_name'] = $group['groupName'];
				$group['create_by'] = Session::get('userId');
				if($my->insert('dntk_chat_group',$group))
					echo $my->lastInsertId();
				echo 'false';				
			}else{//已经
				echo 'false';
			}
		}
	}

	public function panel()
	{
		$name = Session::get('nickName');
		$userId = Session::get('userId');
		//列出用户自建组
		$my = new Mysql();
		$groups = $my->field("id,group_name")->where(array('create_by'=>$userId))->select('dntk_chat_group');

		//获取各组下好友列表
		$groupsIn = implode(',',array_get_by_key($groups,'id'));
		$sql = "select u.id,u.nickname,u.realname,u.sex,gu.group_id from dntk_chat_user u, dntk_chat_group_user gu where u.id = gu.user_id and gu.group_id in($groupsIn) ";
		$users = $my->doSql($sql);
		//数据处理
		$list = getPanelList($groups,$users);
		//页面显示
		$this->loadView('this',array('name'=>$name,'groups'=>$groups,'list'=>$list));
	}

	/**
	 * 搜索用户
	 */
	public function search()
	{
		$sql = "select u.id,u.nickname from dntk_chat_user u where u.nickname like '%%' ";
		$my = new Mysql();
		$users = $my->doSql($sql);
		header('Content-type:text/json'); 
		echo Json::Arr2J($users);
	}

	public function friend()
	{
		$params = $this->url->params;
		$my = new Mysql();
		$params['user_id'] = $params['friendId'];
		$params['group_id'] = $params['groupId'];
		if($my->insert('dntk_chat_group_user',$params))
		{
			echo true;
		} else {
			echo false;
		}
	}
}
//接口Json API
// $a =  Json::J2Arr($this->body);
// var_dump($a);



/* 
author: yangyu@sina.cn 
description: 根据某一特定键(下标)取出一维或多维数组的所有值；
不用循环的理由是考虑大数组的效率，把数组序列化，然后根据序列化结构的特点提取需要的字符串
*/  
function array_get_by_key(array $array, $string){  
    if (!trim($string)) return false;  
    preg_match_all("/\"$string\";\w{1}:(?:\d+:|)(.*?);/", serialize($array), $res);  
    return $res[1];  
} 

function getPanelList($groups,$users)
{
	$list = array('contact'=>'联系客服');
	foreach ($groups as $index => &$group) {
		foreach ($users as $user) {
			if( $group['id']== $user['group_id']){
				unset($user['group_id']);
				$group['users'][]= $user;
			}
		}
	}
	$list =array_merge($list,$groups);
	return $list;
}