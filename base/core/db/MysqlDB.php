<?php
 namespace base\core;
 use base\core\_DB;

 class MysqlDB extends Object{

 	private $db = null;
 	private $sql = null;
 	private $errorTpye = array(
 					'insert' => "添加失败",
 					'update' => "更新失败",
 					'delete' => "删除失败",
 					'select' => "查询失败"
			 		);

 	function __construct()
 	{
 		$_dbObj = _DB::getInstance();
 		$this->db  = $_dbObj->getConn();
 	}

 	/**
 	 * [insertOne 插入一条数据]
 	 * @param  [type] $sql [description]
 	 * @return [type]      [插入的一条数据的id]
 	 */
 	public function insertOne($sql)
 	{
 		if($this->db->query($sql) == true)
 		{
 			return $this->db->insert_id;
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
 		if($this->db->query($sql) == true)
 		{
 			return $this->db->affected_rows == 1;
 		}
 		die($this->errorTpye['update'].' : '.$sql);
 	}

 	public function deleteOne()
 	{
 		if($this->db->query($sql) == true)
 		{
 			return $this->db->affected_rows == 1;
 		}
 		die($this->errorTpye['delete'].' : '.$sql);
 	}

 	public function select()
 	{
 		$result = $this->db->query($sql);
 		if($this->db->num_rows)
 		{
 			return $result->fetch_array();
 		}
 		die($this->errorTpye['select'].' : '.$sql);
 	}

 }