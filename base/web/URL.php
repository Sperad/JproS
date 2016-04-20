<?php
namespace base\web;

class URL extends WebObject{
	
	/* User_signUp/a=123&b=124 */
	private $query_String;

	/* array = [a=>123,b=>124] */
	private $params;

	/* array = [0=>User,1=>View] */
	private $CV;

	public function __construct()
	{
		$this->query_String = $_SERVER["QUERY_STRING"];
		$this->parse();
	}

	/* user/singup@a=123&b=124
	*	/ 分割路由和参数
	*	_ 分割 c/m
	*/
	function parse()
	{
		/*解析 User_signUp/a=123&b=124 */
		if(!empty($this->query_String)){
			$tmpAry =  explode('/',$this->query_String);
			$this->CV = explode('_',$tmpAry[0]);
			if(count($this->CV)==1) $this->CV []=null;//没有VIEW的时候自动补充NULL
			if(count($tmpAry) == 2)
				parse_str($tmpAry[1],$this->params);// 必须是url地址且以&分割
		}
	}

	public function getCV(){
		return $this->CV;
	}

	public function getParams(){
		return $this->params;
	}
}