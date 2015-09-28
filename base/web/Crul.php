<?php 

namespace base\web;
class Crul extends WebObject {

	public static function send2($http = null)
	{
		$ch = curl_init();
	    $url = 'http://apis.baidu.com/apistore/idservice/id?id=510184199302163416';
	    $header = array(
	        'apikey:3a95276337e99b128bad98dbc621f3da',
	    );
	    // 添加apikey到header
	    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    // 执行HTTP请求
	    curl_setopt($ch , CURLOPT_URL , $url);
	    $res = curl_exec($ch);
	    var_dump(json_decode($res));
	}	

	/**
	 * @return [type] [description]
	 * 接收移动端发过来json 数据(暂未开发)
	 */
	public static function receive()
	{

	}

}