<?php
namespace controller;
use base\Controller;
use base\helper\Json;
use base\web\Session;
use base\db\Mysql;

class Chat extends Controller {

	/*聊天页面*/
	public function dialog()
	{
		$this->visitorSignUp();
		$userId = Session::get("userId");
		//是否存在chatWith
		$chatWithId = intval($this->url->params['chatwithId']);
		if(!$chatWithId) return false;
		Session::set('chatWithId',$chatWithId);
		//是否存在role
		$params = $this->url->params;
		if(!isset($params['role'])) return false;
		Session::set('role','friend');
		Session::set('fromRole',7);
		$role = $params['role'];//自己的身份

		//用户-游客
		$tableUser = 'dntk_chat_visitor';
		
		//游客--用户
		if($role =='visitor') //--自己为游客
		{
			$tableUser = 'dntk_chat_user';
			Session::set('role','visitor');
		}
		//用户与用户
		if(isset($params['fromRole']) && $params['fromRole']== 'friend'){
				Session::set('fromRole',6);
				$tableUser = 'dntk_chat_user';
		}
		$my = new Mysql();
		//获取对方信息
		$sql = "select nickname from $tableUser where id = $chatWithId";
		$chatWith = $my->doSql($sql);
		$chatwithName = strlen($chatWith[0]['nickname'])<9 ? $chatWith[0]['nickname']:substr($chatWith[0]['nickname'],0,6).'...';
		//获取未读取的信息
		$sql = "select * from dntk_chat_message m where  ((m.from_user_id = $userId and m.to_user_id = $chatWithId) or".
					"(m.from_user_id = $chatWithId and m.to_user_id = $userId)) and m.status=1 order by id asc";
		$chatHistory = $my->doSql($sql);
		//将信息标记为已读
		$my->where(array('from_user_id'=>$chatWithId,'to_user_id'=>$userId))
			->update('dntk_chat_message',array('status'=>3));
		$this->loadView('this',array('chatHistory'=>$chatHistory,
							'nickname'=>$chatwithName,
							'chatWithId'=>$chatWithId) );
	}

	private function visitorSignUp()
	{
		$userId = Session::get("userId");
		$my = new Mysql();
		if(!$userId){//游客--未注册/登录
			$seionId =  Session::$id;
			if(isset($_COOKIE['visitor']))
			{//更新游客session
				$visitorName= $_COOKIE['visitor'];
				$sql = "select count(1) as cnt,id from dntk_chat_visitor where nickname = '$visitorName' ";
				$cnt = $my->doSql($sql);
				if(!empty($cnt[0]['cnt'])){//更新session_id
					$my->where(array('nickname'=>$visitorName))
						->update('dntk_chat_visitor',array('session_id'=>$seionId));
					$visitorId = $cnt[0]['id'];
				}
			}else{//(注册游客)
				$visitor['session_id']= $seionId;
				$visitor['nickname']  = $visitorName = rand(1000,9999).substr(md5($seionId),2,16);
				if($my->insert('dntk_chat_visitor',$visitor))
					$visitorId = $my->lastInsertId();
			}
			//保存session
			Session::set('userId',$visitorId);
			Session::set('nickName',$visitorName);
			//设置cookie
			setcookie('visitor',$visitorName,time()+3600);
		}
	}
	public function record()
	{
		$userId = Session::get("userId");
		$chatWithId = Session::get('chatWithId');
		$my = new Mysql();
		$role = Session::get('role');
		$fromRole = $role=='friend' ? 7 : 6;
		$sql = "select * from dntk_chat_message m where m.from_user_id = $chatWithId ".
				 "and m.to_user_id =$userId  and status=1 and from_role=$fromRole";
		$newRecord = $my->doSql($sql);
		if(empty($newRecord)){
			echo false; exit;
		}else{
			header('Content-type:text/json;charset=utf-8');
			//修改信息记录的状态
			$my->where(array('from_user_id'=>$chatWithId,'to_user_id'=>$userId,'status'=>1))
							->update('dntk_chat_message',array('status'=>3));
			echo Json::Arr2J($newRecord);
		}
	}
	public function sendMsg()
	{
		$chatWithId = Session::get('chatWithId');
		if($this->method == 'POST')
		{
			//获取消息内容
			$a =  Json::J2Arr($this->body);
			$message['create_time'] = date("Y-m-d H:i:s");
			$message['from_user_id'] = Session::get('userId');
			$message['to_user_id'] = $chatWithId;
			$message['content'] = $a['content'];
			$role = Session::get('role');
			$message['from_role'] = Session::get('fromRole');;

			//插入数据库
			$my = new Mysql();
			if($my->insert('dntk_chat_message',$message)){
				$message['id'] = $my->lastInsertId();
				header('Content-type:text/json;charset=utf-8'); 
				echo Json::Arr2J($message);
			}
		}
	}

	public function historyRecord()
	{
		$chatWithId = Session::get('chatWithId');
		$userId = Session::get('userId');
		if(isset($this->url->params['times'])){
			$page = $this->url->params['times'];
			Session::set('moreTimes',$page);
		}else{
			$page = Session::get('moreTimes');
		}
		$pageSize = 2;
		$limit = 'limit '.$page*$pageSize.','.$pageSize;
		$fromRole = Session::get('fromRole');

		$sql = "select * from dntk_chat_message m ".
				" where ((m.from_user_id = $userId and m.to_user_id =$chatWithId) or ".
					" (m.from_user_id =$chatWithId and m.to_user_id = $userId)) ".
						"and m.status =3 and m.from_role = $fromRole order by id desc ".$limit;
		Session::set('moreTimes',++$page);
		$my = new Mysql();
		$historyRecord = $my->doSql($sql);
		header('Content-type:text/json;charset=utf-8'); 
		if(empty($historyRecord)){
			echo false;exit;
		}
		// echo Json::Arr2J(array_reverse($historyRecord));
		echo Json::Arr2J($historyRecord);
	}

}
