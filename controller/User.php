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
			$userOnly = "select count(1) as cnt from dntk_chat_user where nickname = '$user[nickname]'";
			$cnt = $my->count($userOnly);//
			if(empty($cnt['cnt'])){//未注册
				if($my->insert('dntk_chat_user',$user))
				{//注册成功,自动登陆
					//默认一个组(好友)
					$userId = $my->lastInsertId();
					$group['group_name'] = '好友';
					$group['create_by'] = $userId;
					$my->insert('dntk_chat_group',$group);
					//保存session
					Session::set('userId',$userId);
					Session::set('nickName',$user['nickname']);
					echo "<script>alert('注册成功');window.location.href='index.php?User_panel'</script>";
				}
			}else{//已经注册
				echo "<script>alert('已经注册');window.location.href='index.php?User_signUp'</script>";
			}
		}

		if($this->method == 'GET' && !$this->isAjax)
		{
			$this->loadView('this');
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
			Session::destroy();
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
			$groupOnly = "select count(1) as cnt from dntk_chat_group where group_name = '$group[groupName]'";
			$cnt = $my->count($groupOnly);//
			if(empty($cnt['cnt'])){//未
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
		//判断是否登录
		if(Session::get('userId')){
			$name = Session::get('nickName');
			$userId = Session::get('userId');
			//列出用户自建组
			$my = new Mysql();
			$groups = $my->field("id,group_name")->where(array('create_by'=>$userId))->select('dntk_chat_group');

			//获取各组下好友列表
			$users = array();
			if(!empty($groups)){
				$groupsIn = implode(',',array_get_by_key($groups,'id'));
				$sql = "select u.id,u.nickname,u.realname,u.sex,gu.group_id from dntk_chat_user u, dntk_chat_group_user gu where u.id = gu.user_id and gu.group_id in($groupsIn) ";
				$users = $my->doSql($sql);
			}

			//获取好友发来的消息数量
			$sql = "select count(1) as cnt,from_user_id from dntk_chat_message where to_user_id = $userId and status = 1 and from_role=6 group by from_user_id";
			$recordCnt = $my->doSql($sql);

			//获取游客发来的消息数量
			$sql = "select count(1) as cnt from dntk_chat_message where to_user_id = $userId and status = 1 and from_role=7 ";
			$visitorCnt = $my->doSql($sql);
			//数据处理
			$list = getPanelList($groups,$users,$recordCnt);

			//获取好友请求消息 需要判断用户是否已经添加该好友
			$requestRecord = "select count(1) as cnt from dntk_chat_request_record ".
								"where to_user_id = $userId  and status=1 and from_user_id not in ( ". 
									" select gu.user_id from dntk_chat_group_user gu , dntk_chat_group g ".
							           "where gu.group_id = g.id and g.create_by =$userId)";
			$cnt = $my->count($requestRecord);

			//页面显示
			$this->loadView('this',array('name'=>$name,'requestRecord'=>$cnt['cnt'],
									'groups'=>$groups,'list'=>$list,'visitorCnt'=>$visitorCnt[0]['cnt']));
		}
	}

	/**
	 * 搜索用户
	 */
	public function search()
	{
		$search = $this->url->params['search'];
		$userId = Session::get('userId');
		/*搜索获取已有好友id列表*/
		$my = new Mysql();
		$getIds_sql = "select gu.user_id from dntk_chat_group_user gu , dntk_chat_group g where gu.group_id = g.id and g.create_by =$userId";
		$ids = $my->doSql($getIds_sql);
		$ids=array_get_by_key($ids,'user_id');
		$ids[] = $userId;
		$strIds = implode(',',$ids);
		//搜索结果
		$sql = "select u.id,u.nickname from dntk_chat_user u where u.id not in($strIds) and u.nickname like '%$search%' ";
		$users = $my->doSql($sql);
		header('Content-type:text/json'); 
		echo Json::Arr2J($users);
	}

	/**
	 * 添加好友
	 */
	public function friend()
	{
		$my = new Mysql();
		$userId = Session::get('userId');
		//添加好友
		if($this->method == "POST")
		{
			$params = $this->url->params;
			$_params['user_id'] = $toUserId=$params['friendId'];
			$_params['group_id'] = $params['groupId'];
			if($params['status']==1){//发送请求
				if($my->insert('dntk_chat_group_user',$_params))
				{	//向好友发送请求
					$record = array('from_user_id'=>$userId,'to_user_id'=>$toUserId);
					$my->insert('dntk_chat_request_record',$record);
					echo true;
				} else {
					echo false;
				}
			}else{//接收者回复发送者
				//修改状态
				$my->where(array('from_user_id'=>$params['user_id'],'to_user_id'=>$userId))->update('dntk_chat_request_record',array('status'=>$params['status']));

				if($params['status']==3){//如果添加
					if($my->insert('dntk_chat_group_user',$params))
						echo true;
				}else{
					echo false;
				}
			}
		}
		//获取发送请求好友的列表
		if($this->method == 'GET')
		{
			$sql = "select u.id, u.nickname,u.realname,u.sex,u.birthday ".
					"from dntk_chat_user u,dntk_chat_request_record rr ".
					"where u.id = rr.from_user_id  and rr.status = 1 and rr.to_user_id = $userId ".
					"and rr.from_user_id not in( ".
					    "select gu.user_id from dntk_chat_group_user gu , dntk_chat_group g ".
				       	"where gu.group_id = g.id and g.create_by =$userId ".
					")" ;

			$users = $my->doSql($sql);
			header('Content-type:text/json'); 
			echo Json::Arr2J($users);
		}
	}

	/**
	 * 删除好友
	 */
	public function delFriend()
	{
		if($this->method == 'POST')
		{
			$friendId = $_POST['friendId'];
			$groupId = $_POST['groupId'];
			$my = new Mysql();
			$my->where(array('user_id'=>$friendId,'group_id'=>$groupId))->delete('dntk_chat_group_user');
			return true;
		}
	}
	/**
	 * 删除好友
	 */
	public function delGroup()
	{
		if($this->method == 'POST')
		{
			$userId = Session::get('userId');
			$groupId = $_POST['groupId'];
			$my = new Mysql();
			//首先判断该组是否还有好友
			$cntSql = "select count(1) as cnt from dntk_chat_group_user gu where gu.group_id = $groupId";
			$cnt = $my->count($cntSql);
			if(empty($cnt['cnt'])){
				$my->where(array('create_by'=>$userId,'id'=>$groupId))->delete('dntk_chat_group');
				echo true;
			}else{
				echo false;
			}
		}
	}

	/**
	 * 移动好友
	 */
	public function movFriend()
	{
		$friendId = $_POST['friendId'];
		$groupId  = $_POST['groupId'];
		$oldGroupId  = $_POST['oldGroupId'];
		$my = new Mysql();
		$my->where(array('group_id'=>$oldGroupId,'user_id'=>$friendId))
				->update('dntk_chat_group_user',array('group_id'=>$groupId));
		echo true;
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

function getPanelList($groups,$users,$recordCnt)
{
	foreach ($users as &$user) {
		if(!empty($recordCnt)){
			foreach ($recordCnt as $record) {
				if($user['id'] == $record['from_user_id'])
					$user['recordCnt'] = $record['cnt'];
			}
		}
	}
	
	foreach ($groups as &$group) {
		foreach ($users as $usertmp) {
			if( $group['id']== $usertmp['group_id']){
				$group['users'][]= $usertmp;
			}
		}
	}
	return $groups;
}