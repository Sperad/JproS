<?php
namespace base\db;

class Mysql extends DB{

    private static $mysql;
 	//单例方法
    public static function getInstance()  
    {  
        /*if(!isset(static::$_dbInstance[$DB_NAME]) )  
        {  
            $DB_NAME = !is_null($DB_NAME) ? $DB_NAME : DB_NAME;
            static::$_dbInstance[$DB_NAME] = new self($DB_NAME);  
        }  
        return static::$_dbInstance[$DB_NAME]; */
    }      

 	/**
 	 * [insertOne 插入一条数据]
 	 * @param  [type] $sql [description]
 	 * @return [type]      [插入的一条数据的id]
 	 */
 	public function insertOne($sql,$bindParams = null)
 	{

       /* $_link = &$this->link;
 		if($this->link->query($sql) == true)
 		{
 			return $this->link->insert_id;
 		}
 		die($this->errorTpye['insert'].' : '.$sql);*/
 	}

 	public function updateOne($sql)
 	{
 		
 	}

 	public function deleteOne()
 	{
 		
 	}

 }