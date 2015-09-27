<?php
namespace base\core;

class _DB {

	private $conn = null;
	private static $_dbInstance = null;
	
	private function __construct()
	{
		$this->conn = mysqli_connect ( DB_HOST , DB_USER ,DB_PSD, DB_NAME );
		if(!$this->conn)
		{
			die('Connect Error('.mysqli_connect_errno().')'.mysqli_connect_error());
		}
		$this->conn->query("SET NAMES 'utf8'");
	}

	 //单例方法  
    public static function getInstance()  
    {  
        if(!isset(self::$_dbInstance) )  
        {  
            self::$_dbInstance = new self;  
        }  
        return self::$_dbInstance; 
    }      
      
    //阻止用户复制对象实例  
    public function __clone()  
    {  
        trigger_error('Clone is not allow' ,E_USER_ERROR);  
    } 

    public function getConn(){
    	return $this->conn;
    }

}
