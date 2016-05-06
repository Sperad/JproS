<?php
namespace controller;
use base\Controller;
use base\View;
use base\db\Mysql;
use base\helper\Json;
use base\web\Session;

class Info extends Controller
{
	public function default()
	{
		if($this->isLogin()){
			$uid = $this->user['id'];
			$my = new Mysql();
			$sql = "select * from chat_user_info where user_id=$uid ";
			$info = $my->doSql($sql);
			if(empty($info)){
				$info = null;
			}else {
				if(empty($info['birthday'])){
					$info['birthday'] = time();
				}
			}
			return new View('/Info/default', array(
								'nickname'=> $this->user['nickname'], 
								'info' => $info[0]
					));
		}
	}

	public function upsert()
	{
		if($this->isLogin()){
			$uid = $this->user['id'];
			$_POST['user_id'] = $uid;
			$info = $this->dealData($_POST);
			$my = new Mysql();
			$sql = "select id from chat_user_info where user_id=$uid ";
			$tpmUser = $my->doSql($sql);
			if(empty($tpmUser)){
				$ok = $my->insert('chat_user_info',$info);
			}else{
				$ok = $my->where(array('user_id'=>$uid))->update('chat_user_info',$info);
			}
			if($ok)
				$this->goPage('/Info_default', '更新成功');
			$this->goPage('/Info_default', '更新失败');
		}
	}

	protected function dealData($post)
	{
		$post['birthday']   = strtotime($post['birthday']);
		foreach ($post as $field => $v) {
			if(empty($v)){
				unset($post[$field]);
			}
		}
		return $post;
	}
}