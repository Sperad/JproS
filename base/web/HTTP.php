<?php
namespace base\web;

class HTTP extends WebObject{

	private $url;
	private $method;	

	public function __construct()
	{
		$this->url = new URL();
		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	public function getUrl(){
		return $this->url;
	}

}