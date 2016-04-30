<?php
namespace controller;

use base\Controller;
use base\View;
use base\db\Mysql;
use base\helper\Json;
use base\web\Session;

class User extends Controller{

	/* 添加组 */
	public function Group()
	{
		if($this->isAjax && $this->method == 'POST' && $this->isLogin())
		{
			$group = $_POST;
			$my = new Mysql();
			$groupOnly = "select count(1) as cnt from chat_group where group_name = '$group[groupName]'";
			$cnt = $my->count($groupOnly);//
			if(empty($cnt['cnt'])){//未
				$group['group_name'] = $group['groupName'];
				$group['create_by'] = $this->user['id'];
				if($my->insert('chat_group',$group)){
					echo true;
				}else{echo false;}
			}else{
				echo false;
			}
		}
	}

	/*控制中心*/
	public function center()
	{
		if($this->isLogin()){
			//列出用户自建组
			$userId = $this->user['id'];
			$my = new Mysql();
			$groups = $my->field("id,group_name")
					->where(array('create_by'=>$userId))
					->select('chat_group');

			//获取各组下好友列表
			$users = array();
			if(!empty($groups)){
				$groupsIn = implode(',',array_get_by_key($groups,'id'));
				$sql = "select u.id,u.nickname,gu.group_id from chat_user u, chat_group_user gu where u.id = gu.user_id and gu.group_id in($groupsIn) ";
				$users = $my->doSql($sql);
			}

			//获取好友发来的消息数量
			$sql = "select count(1) as cnt,from_user_id from chat_message where to_user_id = $userId and status = 1 group by from_user_id";
			$recordCnt = $my->doSql($sql);
			//数据处理
			$list = getPanelList($groups,$users,$recordCnt);

			//获取好友请求消息 需要判断用户是否已经添加该好友
			$requestRecord = "select count(1) as cnt from chat_request_record ".
								"where to_user_id = $userId  and status=1 and from_user_id not in ( ". 
									" select gu.user_id from chat_group_user gu , chat_group g ".
							           "where gu.group_id = g.id and g.create_by =$userId)";
			$cnt = $my->count($requestRecord);
			//页面显示
			return new View('User/center',array('list'=>$list, 'requestRecord' => $cnt['cnt'],
												'nickname'=>$this->user['nickname']));
		}
	}
	/*退出*/
	public function logout(){
		Session::set('user', '');
		$this->goPage('/');
	}

	/* 搜索用户 */
	public function search()
	{
		if($this->isLogin()){
			$userId = $this->user['id'];
			$search = $this->url->params['search'];
			$page = isset($this->url->params['page']) ? $this->url->params['page'] : 0;
			$pageNo = isset($this->url->params['pageNo']) ? $this->url->params['pageNo'] : 9;
			$my = new Mysql();
			/*获取好友id列表*/
			$getIds_sql = "select gu.user_id from chat_group_user gu , chat_group g where gu.group_id = g.id and g.create_by =$userId";
			$ids = $my->doSql($getIds_sql);
			if(!empty($ids)){
				$ids=array_get_by_key($ids,'user_id');
				$strIds = implode(',',$ids);
			}else{
				$strIds = $userId;
			}
			//搜索陌生人
			$sql = "select u.id,u.nickname from chat_user u where u.id not in($strIds) and u.nickname like '%$search%' limit $page, $pageNo";
			$users = $my->doSql($sql);
			header('Content-type:text/json'); 
			echo Json::Arr2J($users);
		}
	}

	/**
	 * 添加好友
	 */
	public function friend()
	{
		if($this->isLogin() && $this->method == "POST"){
			$my = new Mysql();
			$userId = $this->user['id'];
			$_params['user_id'] = $toUserId=$_POST['friendId'];
			$_params['group_id'] = 			$_POST['groupId'];
			$_params['status'] = $_POST['status'];
			if($_params['status']==1){//发送请求
				if($my->insert('chat_group_user',$_params))
				{	//向好友发送请求
					$record = array('from_user_id'=>$userId,'to_user_id'=>$toUserId);
					$my->insert('chat_request_record',$record);
					echo true;
				} else {
					echo false;
				}
			}else{//接收者回复发送者
				//修改状态
				$my->where(array('from_user_id'=>$_params['user_id'],'to_user_id'=>$userId))
					->update('chat_request_record',array('status'=>$_params['status']));
				if($_params['status']==3){//如果添加
					if($my->insert('chat_group_user',$_params))
						echo true;
				}else{
					echo false;
				}
			}
		}
		//获取发送请求好友的列表
		if($this->isLogin() && $this->method == 'GET')
		{
			$my = new Mysql();
			$userId = $this->user['id'];
			$sql = "select u.id, u.nickname ".
					"from chat_user u,chat_request_record rr ".
					"where u.id = rr.from_user_id  and rr.status = 1 and rr.to_user_id = $userId ".
					"and rr.from_user_id not in( ".
					    "select gu.user_id from chat_group_user gu , chat_group g ".
				       	"where gu.group_id = g.id and g.create_by =$userId ".
					")" ;

			$users = $my->doSql($sql);
			header('Content-type:text/json'); 
			echo Json::Arr2J($users);
		}
	}

	public function getOne()
	{
		if($this->isLogin()){
			$my = new Mysql();
			$id = $this->url->params['uid'];
			if(!$id) {echo false; exit;}
			$sql = "select * from chat_user where id = $id; ";
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
			$my->where(array('user_id'=>$friendId,'group_id'=>$groupId))->delete('chat_group_user');
			return true;
		}
	}
	/**
	 * 删除好友
	 */
	public function delGroup()
	{
		if($this->method == 'POST' && $this->isLogin())
		{
			$userId = $this->user['id'];
			$groupId = $_POST['groupId'];
			$my = new Mysql();
			//首先判断该组是否还有好友
			$cntSql = "select count(1) as cnt from chat_group_user gu where gu.group_id = $groupId";
			$cnt = $my->count($cntSql);
			if(empty($cnt['cnt'])){
				$my->where(array('create_by'=>$userId,'id'=>$groupId))->delete('chat_group');
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
				->update('chat_group_user',array('group_id'=>$groupId));
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
		if(!empty($users) && is_array($users)){
			foreach ($users as $usertmp) {
				if( $group['id']== $usertmp['group_id']){
					$group['users'][]= $usertmp;
				}
			}
		}else{
			$group['users']= array();
		}
	}
	return $groups;
}