<?php
namespace base\db;

class DB extends DBObject{

	protected $link = null;
	public static $_dbInstance = array();
	
	public function __construct($DB_NAME)
	{
		$this->link = mysqli_connect ( DB_HOST , DB_USER ,DB_PSD, $DB_NAME);
		if(!$this->link)
		{
			die('Connect Error('.mysqli_connect_errno().')'.mysqli_connect_error());
		}
		$this->link->query("SET NAMES 'utf8'");
	}

    //阻止用户复制对象实例  
    public function __clone()  
    {  
        trigger_error('Clone is not allow' ,E_USER_ERROR);  
    } 
}
