<?php
namespace controller;
use base\Controller;
use base\helper\Json;
use base\web\Session;
use base\View;
use base\db\Mysql;

class Chat extends Controller {


	/*聊天页面*/
	public function dialog()
	{
		if(!$this->isLogin())
			$this->goPage('/');
		
		$withId = intval($this->url->params['with']);
		if(!$withId) return false;
		Session::set('withId',$withId);

		$userId = $this->user['id'];
		$my = new Mysql();
		//获取对方信息
		$sql = "select nickname from chat_user where id = $withId";
		$chatWith = $my->doSql($sql);
		$chatwithName = strlen($chatWith[0]['nickname'])<9 ? $chatWith[0]['nickname']:substr($chatWith[0]['nickname'],0,6).'...';
		//获取未读取的信息
		$sql = "select * from chat_message m where  ((m.from_user_id = $userId and m.to_user_id = $withId) or".
					"(m.from_user_id = $withId and m.to_user_id = $userId)) and m.status=1 order by id asc";
		$chatHistory = $my->doSql($sql);
		//将信息标记为已读
		$my->where(array('from_user_id'=>$withId,'to_user_id'=>$userId))
			->update('chat_message',array('status'=>3));
		return new View('Chat/dialog',array('chatHistory'=>$chatHistory,
							'withNickname'=>$chatwithName,
							'nickname'=>$this->user['nickname'],
							'withId'=>$withId) );
	}

	public function record()
	{
		if($this->isLogin()){
			$userId = $this->user['id'];
			$withId = Session::get('withId');
			$my = new Mysql();
			$sql = "select * from chat_message m where m.from_user_id = $withId ".
					 "and m.to_user_id =$userId  and status=1 ";
			$newRecord = $my->doSql($sql);
			if(empty($newRecord)){
				echo false; exit;
			}else{
				header('Content-type:text/json;charset=utf-8');
				//修改信息记录的状态
				$my->where(array('from_user_id'=>$withId,'to_user_id'=>$userId,'status'=>1))
								->update('chat_message',array('status'=>3));
				echo Json::Arr2J($newRecord);
			}
		}
	}
	public function sendMsg()
	{
		if($this->method == 'POST' && $this->isLogin())
		{
			//获取消息内容
			$withId = Session::get('withId');
			$a =  Json::J2Arr($this->body);
			$message['create_time'] = date("Y-m-d H:i:s");
			$message['from_user_id'] = $this->user['id'];
			$message['to_user_id'] = $withId;
			$message['content'] = $a['content'];
			//插入数据库
			$my = new Mysql();
			if($my->insert('chat_message',$message)){
				$message['id'] = $my->lastInsertId();
				header('Content-type:text/json;charset=utf-8'); 
				echo Json::Arr2J($message);
			}
		}
	}

	public function historyRecord()
	{
		if($this->isLogin()){
			$userId = $this->user['id'];
			$withId = Session::get('withId');
			$pageSize = 2; 
			if(isset($this->url->params['times'])){
				$times = $this->url->params['times'];
				Session::set('moreTimes',$times);
			}else{
				$times = Session::get('moreTimes');
			}
			$limit = 'limit '.$times.','.$pageSize;
			
			$sql = "select * from chat_message m ".
					" where ((m.from_user_id = $userId and m.to_user_id =$withId) or ".
						" (m.from_user_id =$withId and m.to_user_id = $userId)) ".
							"and m.status =3 order by id desc ".$limit;
			$times = floor($times / $pageSize +1) * $pageSize + ($times % $pageSize);
			Session::set('moreTimes', $times);
			$my = new Mysql();
			$historyRecord = $my->doSql($sql);
			header('Content-type:text/json;charset=utf-8'); 
			if(empty($historyRecord)){
				echo false; exit;
			}
			// echo Json::Arr2J(array_reverse($historyRecord));
			echo Json::Arr2J($historyRecord);
		}
	}
}
