<?php
namespace base\db;

class Mysql extends DB{

 	private $errorTpye = array(
 					'insert' => "添加失败",
 					'update' => "更新失败",
 					'delete' => "删除失败",
 					'select' => "查询失败"
			 		);

 	public function __construct($DB_NAME)
 	{
 		parent::__construct($DB_NAME);
 	}

 	 //单例方法 + 工厂模式 
    public static function getInstance($DB_NAME = null)  
    {  
        if(!isset(static::$_dbInstance[$DB_NAME]) )  
        {  
            $DB_NAME = !is_null($DB_NAME) ? $DB_NAME : DB_NAME;
            static::$_dbInstance[$DB_NAME] = new self($DB_NAME);  
        }  
        return static::$_dbInstance[$DB_NAME]; 
    }      

 	/**
 	 * [insertOne 插入一条数据]
 	 * @param  [type] $sql [description]
 	 * @return [type]      [插入的一条数据的id]
 	 */
 	public function insertOne($sql)
 	{
 		if($this->link->query($sql) == true)
 		{
 			return $this->link->insert_id;
 		}
 		die($this->errorTpye['insert'].' : '.$sql);
 	}

 	/**
 	 * [updateOne 更新一条数据]
 	 * @param  [type] $sql [description]
 	 * @return [type]      [成功true]
 	 */
 	public function updateOne($sql)
 	{
 		if($this->link->query($sql) == true)
 		{
 			return $this->link->affected_rows == 1;
 		}
 		die($this->errorTpye['update'].' : '.$sql);
 	}

 	public function deleteOne()
 	{
 		if($this->link->query($sql) == true)
 		{
 			return $this->link->affected_rows == 1;
 		}
 		die($this->errorTpye['delete'].' : '.$sql);
 	}

 	public function select()
 	{
 		$result = $this->link->query($sql);
 		if($this->link->num_rows)
 		{
 			return $result->fetch_array();
 		}
 		die($this->errorTpye['select'].' : '.$sql);
 	}

 }