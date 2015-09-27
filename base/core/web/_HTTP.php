<?php
namespace base\core;
class _HTTP extends Object{

	private $url;
	private $method;	

	public function __construct()
	{
		$this->url = new _URL();
		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	public function getUrl(){
		return $this->url;
	}

}