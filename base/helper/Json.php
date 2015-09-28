<?php
namespace base\helper;

class Json {

	/**************************************************************
	 *
	 * 将对象 转换为数组
	 * @param Obj 要转换的Object对象
	 * @return ary  转换得到的数组
	 * @access public
	 * 注意:  这里的json为: $json = json_decode(StringJson);
	 *
	 *************************************************************/
 	private function Object2Array($Obj)
 	{
 		$ary = (array)$Obj;

 		//$k 不能为 纯数字类型的 字符串 如:'1357';
 		foreach($ary as $k => $v)
 		{
 			if(is_resource($v) )
 				return '';
 			if(is_object($v) || is_array($v) )
				$ary[$k] = (array)$this->Object2Array($v);
 		}
 		return $ary;
 	}

 	/**************************************************************
 	 *
 	 * 将数组 转换为对象
 	 * @param ary 要转换的ary 数组
 	 * @return obj  转换得到的对象
 	 * @access public
 	 *
 	 *************************************************************/
 	private function array2Object($ary)
 	{
	    if(!is_array($ary) )
	    	return null;

	    foreach($ary as $k=>$v)
	    {
	    	if(is_array($v) || is_object($v))
	           $ary[$k] = (object)array_2_Object($v);
	    }

	    return (object)$ary;
	}

 	/**************************************************************
	 *
	 * 使用特定function对数组中所有元素做处理
	 * @param string $array  要处理的数组
	 * @param string $function 要执行的函数
	 * @return boolean $apply_to_keys_also  是否也应用到key上
	 * @access public
	 * 后期: 待优化，使用&$array
	 *
	 *************************************************************/
 	private function arrayMutant($array,$function,$apply_to_keys_also)
 	{
 		if(is_array($array) && function_exists($function) )
 		{
	 		foreach($array as $k => $v)
	 		{
 				if(is_array($v) )
 				{
 					$array[$k]= $this->arrayMutant($array[$k],$function,$apply_to_keys_also);
 				}
 				if(is_string($v) )
 				{
 					$array[$k] = $function($v);
				}

				if($apply_to_keys_also && is_string($k) )
				{
					$new_k = $function($k);
					if($new_k !== $k)
					{
						$array[$new_k] = $array[$k];
						unset($array[$k]);
					}
				}
 			}
 		}

 		return $array;
 	}

 	/**************************************************************
	 *
	 * 将数组转换为JSON字符串（兼容中文）
	 * @param array $array  要转换的数组
	 * @return string  转换得到的json字符串
	 * @access public
	 *
	 *************************************************************/
 	public function array2Json($ary)
 	{
 		if(is_array($ary)){
 			$ary = $this->arrayMutant($ary, 'urlencode', true);
 			$json = json_encode($ary);
	 		return urldecode($json);
	 	}
 		return '';
 	}

 	/**************************************************************
	 *
	 * 将json转换为数组
	 * @param json  要转换的json 字符串
	 * @return ary  转换得到的 数组
	 * @access public
	 *
	 *************************************************************/
 	public function json2array($json)
 	{
 		return $this->Object2Array(json_decode($json));
 	}

 	public  static function Arr2J($arr)
 	{
 		$self = new self;
 		return $self->array2Json($arr);
 	}

 	public static function J2Arr($json)
 	{
 		$self = new self;
 		return $self->json2array($json);
 	}
}