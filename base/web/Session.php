<?php
namespace base\web;

class Session extends WebObject {

	public static $id = null;
	public static $name = '';
	public static $expire = '';
	public static function start()
    {
        if(is_null(self::$id))
        {
            session_start() or die('session 开启失败');
            self::$id = session_id();
            self::$name = session_name();
            self::$expire = session_cache_expire();
        }
    }

    public static function setName($name)
    {
    	self::$name = $name;
    }

    public static function getID($name)
    {
        return self::$id ;
    }

    public static function setExpire($expire)
    {
    	self::$expire = $expire;
    }

    /**
     * 设置当前项目的Session 值
     * 返回之前设置
     */
    public static function set($key, $value=null)
    {
    	if(is_null(self::$id))
    		return null;
        if(null !== $value)
    		$_SESSION[$key] = $value;
    }

    /**
     * 获取当前项目的Session值
     */
    public static function get($key)
    {
    	if(is_null(self::$id) || !array_key_exists($key,$_SESSION))
    		return null;
    	return $_SESSION[$key];
    }

    public static function close()
    {
    	if(!is_null(self::$id))
    	{
    		setcookie(self::$name,self::$id,time()-60*60);
    		$_SESSION=array();  unset($_SESSION);
    	}
    }

    public static function destroy()
    {
    	self::close();
    	session_destroy();
    }
}
Session::start();