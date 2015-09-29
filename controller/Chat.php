<?php
namespace controller;
use base\Controller;
use base\helper\Json;

class Chat extends Controller {

	/*聊天页面*/
	public function panel()
	{
		if($this->method == "GET")
			$this->loadView();
	}

	public function Record()
	{
		if($this->method == 'GET')
		{
			$d = array('status'=>true,'times'=>time(),'msg'=>'你好');
			echo Json::Arr2J($d);
		}
	}
	public function sendMsg()
	{
		if($this->method == 'POST')
		{
			// $a =  Json::J2Arr($this->body);
			$d = array('status'=>true,'times'=>time(),'msg'=>'你好');
			echo Json::Arr2J($d);
		}
	}

}
