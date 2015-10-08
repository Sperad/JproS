<?php
namespace base\db;

class Mysql extends DB{

    private static $mysql;

 	public function insertOne($sql,$bindParams = null)
 	{

 	}

 	public function updateOne($sql)
 	{
 		
 	}

 	public function hasOne($tbName,$where)
 	{
 		$one = $this->where($where)->select($tbName);
        if(count($one) ==1)
            return $one[0];
        return false;
 	}

    public function count($sql)
    {
        return $this->doSql($sql)[0];
    }

 }