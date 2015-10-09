<?php
namespace controller;
use base\Controller;
use base\helper\Json;
use base\web\Session;
use base\db\Mysql;

class Chat extends Controller {

	/*聊天页面*/
	public function panel()
	{
		$chatWith = intval($this->url->params['chatwith']);
		$userId = Session::get("userId");
		Session::set('chatWith',$chatWith);
		$my = new Mysql();
		//获取上次聊天记录5条
		$sql = "select * from dntk_chat_message m where ((m.from_user_id = $userId and m.to_user_id = $chatWith) ".
					"or (m.from_user_id = $chatWith and m.to_user_id = $userId)) and m.status=3 limit 2";
		$chatHistory = $my->doSql($sql);
		$this->loadView('this',array('chatHistory'=>$chatHistory));
	}

	public function Record()
	{
		$userId = Session::get("userId");
		$chatWith = Session::get('chatWith');
		$my = new Mysql();
		$sql = "select * from dntk_chat_message m where m.from_user_id = $chatWith and m.to_user_id =$userId and status=1";
		$newRecord = $my->doSql($sql);
		if(empty($newRecord)){
			echo false; exit;
		}else{
			header('Content-type:text/json;charset=utf-8');
			//修改信息记录的状态
			$my->where(array('from_user_id'=>$chatWith,'to_user_id'=>$userId,'status'=>1))
							->update('dntk_chat_message',array('status'=>3));
			echo Json::Arr2J($newRecord);
		}
	}
	public function sendMsg()
	{
		$chatWith = Session::get('chatWith');
		if($this->method == 'POST')
		{
			//获取消息内容
			$a =  Json::J2Arr($this->body);
			$message['create_time'] = date("Y-m-d H:i:s");
			$message['from_user_id'] = Session::get('userId');
			$message['to_user_id'] = $chatWith;
			$message['content'] = $a['content'];

			//插入数据库
			$my = new Mysql();
			if($my->insert('dntk_chat_message',$message)){
				header('Content-type:text/json;charset=utf-8'); 
				echo Json::Arr2J($message);
			}
		}
	}

}
