<?php
namespace base\web;

class Session extends WebObject {

	private $map = array();
	private $id = null;
	private $name = '';
	private $expire = '';

	public static $session = null;

	public static function getInstance()
	{
		if(!isset(self::$session))
        {
            $_this = new self;
	        $_this->start();
            self::$session = $_this;
        }
        return self::$session;
	}

	private function start()
    {
        if(is_null($this->id))
        {
            session_start() or die('session 开启失败');
            $this->map = $_SESSION;
            $this->id = session_id();
            $this->name = session_name();
            $this->expire = session_cache_expire();
        }
    }

    public function setName($name)
    {
    	$this->name = $name;
    }

    public function setExpire($expire)
    {
    	$this->expire = $expire;
    }

    /**
     * 设置当前项目的Session 值
     * 返回之前设置
     */
    public function setMap($key, $value=null)
    {
    	if(is_null($this->id))
    		return null;
        if(null !== $value)
    		$this->map[$key] = $value;
    }

    /**
     * 获取当前项目的Session值
     */
    public function getMap($key)
    {
    	if(is_null($this->id) || !array_key_exists($key,$this->map))
    		return null;
    	return $this->map[$key];
    }

    public function close()
    {
    	if(!is_null($this->id))
    	{
    		setcookie($this->name,$this->id,time()-60*60);
    		$this->map=array();  unset($_SESSION);
    	}
    }

    public function destroy()
    {
    	self::close();
    	session_destroy();
    }

}