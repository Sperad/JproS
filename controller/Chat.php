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
		$chatWithId = intval($this->url->params['chatwithId']);
		$userId = Session::get("userId");
		Session::set('chatWithId',$chatWithId);
		$my = new Mysql();
		if($userId && $chatWithId)
		{
			//获取对方信息
			$sql = "select nickname from dntk_chat_user where id = $chatWithId";
			$chatWith = $my->doSql($sql);
			//获取未读取的信息
			$sql = "select * from dntk_chat_message m where  ((m.from_user_id = $userId and m.to_user_id = $chatWithId) or".
						"(m.from_user_id = $chatWithId and m.to_user_id = $userId)) and m.status=1 order by id asc";
			$chatHistory = $my->doSql($sql);
			//将信息标记为已读
			$my->where(array('from_user_id'=>$chatWithId,'to_user_id'=>$userId))
				->update('dntk_chat_message',array('status'=>3));
			$this->loadView('this',array('chatHistory'=>$chatHistory,
								'nickname'=>$chatWith[0]['nickname'],
								'chatWithId'=>$chatWithId) );
		}else{//游客
			$seionId = Session::$id;
			//判断是否存在该游客
			$visitorOnly = "select count(1) as cnt from dntk_chat_visitor where session_id = '$seionId' ";
			$cnt = $my->count($visitorOnly);//
			if(empty($cnt['cnt'])){//未注册
				$visitor['session_id']= $seionId;
				$visitor['nickname']  = rand(1000,9999).substr(md5($seionId),10,6);
				if($my->insert('dntk_chat_visitor',$visitor))
				{//注册成功,自动登陆
					$visitorId = $my->lastInsertId();
					//保存session
					Session::set('userId',$visitorId);
					Session::set('nickName',$visitor['nickname']);
				}
			}
		}
	}

	public function record()
	{
		$userId = Session::get("userId");
		$chatWithId = Session::get('chatWithId');
		$my = new Mysql();
		$sql = "select * from dntk_chat_message m where m.from_user_id = $chatWithId and m.to_user_id =$userId  and status=1";
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
		if( isset($this->url->params['times'])){
			$page = $this->url->params['times'];
			Session::set('moreTimes',$page);
		}else{
			$page = Session::get('moreTimes');
		}
		$pageSize = 2;
		$limit = 'limit '.$page*$pageSize.','.$pageSize;
		$sql = "select * from dntk_chat_message m ".
				" where ((m.from_user_id = $userId and m.to_user_id =$chatWithId) or ".
					" (m.from_user_id =$chatWithId and m.to_user_id = $userId))".
						"and m.status =3 order by id desc ".$limit;
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
