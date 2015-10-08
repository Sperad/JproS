<?php
namespace base\db;
use PDO;
use Exception;
/**
* 只是负责连接数据库
**/
class DB extends DBObject {

	protected $_sql   = false; //最后一条sql语句
    protected $_where = '';
    protected $_order = '';
    protected $_limit = '';
    protected $_field = '*';
    protected $_clear = 0; //状态，0表示查询条件干净，1表示查询条件污染
    protected $_trans = 0; //事务指令数

	protected static $link = null;

	public function __construct()
	{
		$dsn = DB_TPYE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME;
        $options = P_CONNECT ? array(PDO::ATTR_PERSISTENT=>true) : array();
        try { 
            $dbh = new PDO($dsn, DB_USER, DB_PSD, $options);
            //设置如果sql语句执行错误则抛出异常，事务会自动回滚
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
            //禁用prepared statements的仿真效果(防SQL注入)
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) { 
            die('Connection failed: ' . $e->getMessage());
        }
        $dbh->exec('SET NAMES utf8');
        static::$link = $dbh;
	}

	/** 
	* 取得数据表的字段信息 
	* @param string $tbName 表名
	* @return array 
	**/
    private function _tbFields($tbName)
    {
        $sql = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="'.$tbName.'" AND TABLE_SCHEMA="'.DB_NAME.'"';
        $stmt = static::$link->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach ($result as $key=>$value) {
            $ret[$value['COLUMN_NAME']] = 1;
        }
        return $ret;
    }

    /** 
    * 过滤并格式化数据表字段
    * @param string $tbName 数据表名 
    * @param array $data POST提交数据 
    * @return array $newdata 
    */
    public function _dataFormat($tbName,$data)
    {
        if (!is_array($data)) return array();
        $fields = $this->_tbFields($tbName);
        $ret=array();
        foreach ($data as $field => $val) {
            if (!is_scalar($val)) continue; //值不是标量则跳过
            if (array_key_exists($field,$fields)) {
                $field = $this->_addChar($field);
                if (is_int($val)) { 
                    $val = intval($val); 
                } elseif (is_float($val)) { 
                    $val = floatval($val); 
                } elseif (preg_match('/^\(\w*(\+|\-|\*|\/)?\w*\)$/i', $val)) {
                    // 支持在字段的值里面直接使用其它字段 ,例如 (score+1) (name) 必须包含括号
                    $val = $val;
                } elseif (is_string($val)) { 
                    $val = '"'.addslashes($val).'"';
                }
                $ret[$field] = $val;
            }
        }
        return $ret;
    }

    /** 
    * 字段和表名添加 ` 符号
    * 保证指令中使用关键字不出错 (针对mysql)
    * @param string $value 
    * @return string 
    */
    private function _addChar($value)
    { 
        if ('*'==$value || false!==strpos($value,'(') || false!==strpos($value,'.') || false!==strpos($value,'`')) { 
            //如果包含 * 或者 使用了sql方法 则不作处理 
        } elseif (false === strpos($value,'`') ) { 
            $value = '`'.trim($value).'`';
        } 
        return $value; 
    }

    /**
	 * 执行查询 主要针对 SELECT, SHOW 等指令
     * @param string $sql sql指令 
     * @return mixed 
	 */
	private function _Query($sql =null)
	{
        $this->_sql = is_null($sql) ? $this->_sql : $sql;
        $pdostmt = static::$link->prepare($this->_sql); //prepare或者query 返回一个PDOStatement
        $pdostmt->execute();
        return  $pdostmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/** 
    * 执行语句 针对 INSERT, UPDATE 以及DELETE,exec结果返回受影响的行数
    * @param string $sql sql指令 
    * @return integer 
    */
    private function _Exec($sql = null)
    {
        $this->_sql = is_null($sql) ? $this->_sql : $sql;
        return static::$link->exec($this->_sql);
    }

    /** 
    * 执行sql语句，自动判断进行查询或者执行操作 
    * @param string $sql SQL指令 
    * @return mixed 
    */
    public function doSql($sql = '')
    {
        $queryIps = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK'; 
        if(preg_match('/^\s*"?(' . $queryIps . ')\s+/i', $sql))
        { 
            return $this->_Exec($sql);
        } else { //查询操作
            return $this->_Query($sql);
        }
    }
	
    /**
     * 插入方法
     * @param string $tbName 操作的数据表名
     * @param array $data 字段-值的一维数组
     * @return int 受影响的行数
     */
    public function insert($tbName,array $data)
    {
        $data = $this->_dataFormat($tbName,$data);
        if (!$data) return;
        $this->_sql = "insert into ".$tbName."(".implode(',',array_keys($data)).") values(".implode(',',array_values($data)).")";
        return $this->_Exec();
    }
 
    /**
     * 删除方法
     * @param string $tbName 操作的数据表名
     * @return int 受影响的行数
     */
    public function delete($tbName) 
    {
        //安全考虑,阻止全表删除
        if (!trim($this->_where)) return false;
	        $this->_sql = "delete from ".$tbName." ".$this->_where;
        $this->_clear = 1;
        $this->_clear();
        return $this->_Exec();
    }
  
    /**
     * 更新函数
     * @param string $tbName 操作的数据表名
     * @param array $data 参数数组
     * @return int 受影响的行数
     */
    public function update($tbName,array $data)
    {
        //安全考虑,阻止全表更新
        if (!trim($this->_where)) return false;
        $data = $this->_dataFormat($tbName,$data);
        if (!$data) return;
        $valArr = '';
        foreach($data as $k=>$v){
            $valArr[] = $k.'='.$v;
        }
        $valStr = implode(',', $valArr);
        $this->_sql = "update ".trim($tbName)." set ".trim($valStr)." ".trim($this->_where);
        return $this->_Exec();
    }
  
    /**
     * 查询函数
     * @param string $tbName 操作的数据表名
     * @return array 结果集
     */
    public function select($tbName='') 
    {
        $this->_sql = trim( "select ".trim($this->_field)." from ".$tbName." ".
        					  trim($this->_where)." ".trim($this->_order)." ".
        					  	trim($this->_limit)  );
        $this->_clear = 1;
        $this->_clear();
        return $this->_Query();
    }
  
    /**
     * @param mixed $option 组合条件的二维数组，例：$option['field1'] = array(1,'=>','or')
     * @return $this
     */
    public function where($option) 
    {
        if ($this->_clear>0) $this->_clear();
        $this->_where = ' where ';
        $logic = ' and ';
        if (is_string($option)) 
        {
            $this->_where .= $option;
        } elseif (is_array($option)) {
            foreach($option as $field => $v)
            {
                if (is_array($v))
                {
                    $relative = isset($v[1]) ? $v[1] : '=';
                    // $logic    = isset($v[2]) ? $v[2] : ' and ';
                    $condition = ' ('.$this->_addChar($field).' '.$relative.' '.$v[0].') ';
                } else {
                    $logic = 'and';
                    $condition = ' ('.$this->_addChar($field).'	=\''.$v.'\') ';
                }
                $this->_where .= isset($mark) ? $logic.$condition : $condition;
                $mark = 1;
            }
        }
        return $this;
    }
  
    /**
     * 设置排序
     * @param mixed $option 排序条件数组 例:array('sort'=>'desc')
     * @return $this
     */
    public function order($option)
    {
        if ($this->_clear>0) $this->_clear();
        $this->_order = ' order by ';
        if (is_string($option))
        {
            $this->_order .= $option;
        } elseif(is_array($option)) {
            foreach($option as $field=>$v)
            {
                $order = $this->_addChar($field).' '.$v;
                $this->_order .= isset($mark) ? ','.$order : $order;
                $mark = 1;
            }
        }
        return $this;
    }
  
    /**
     * 设置查询行数及页数
     * @param int $page pageSize不为空时为页数，否则为行数
     * @param int $pageSize 为空则函数设定取出行数，不为空则设定取出行数及页数
     * @return $this
     */
    public function limit($page,$pageSize=null)
    {
        if ($this->_clear>0) $this->_clear();
        if ($pageSize===null)
        {
            $this->_limit = " limit ".$page;
        } else {
            $pageval = intval( ($page - 1) * $pageSize);
            $this->_limit = " limit ".$pageval.",".$pageSize;
        }
        return $this;
    }
  
    /**
     * 设置查询字段
     * @param mixed $field 字段数组
     * @return $this
     */
    public function field($field)
    {
        if ($this->_clear>0) $this->_clear();
        if (is_string($field))
        {
            $field = explode(',', $field);
        }
        //array_map作用: 调用this 的_addChar 方法, 参数为 $field
        $nField = array_map(array($this,'_addChar'), $field);
        $this->_field = implode(',', $nField);
        return $this;
    }
  
    /**
     * 清理标记函数
     */
    protected function _clear()
    {
        $this->_where = '';
        $this->_order = '';
        $this->_limit = '';
        $this->_field = '*';
        $this->_clear = 0;
    }
  
    /**
     * 手动清理标记
     * @return $this
     */
    public function clearKey()
    {
        $this->_clear();
        return $this;
    }
 
    /**
    * 启动事务 
    * @return void 
    */
    public function startTrans()
    { 
        //数据rollback 支持 
        if ($this->_trans==0) static::$link->beginTransaction();
        $this->_trans++; 
        return; 
    }
     
    /** 
    * 用于非自动提交状态下面的查询提交 
    * @return boolen 
    */
    public function commit()
    {
        $result = true;
        if ($this->_trans>0)
        { 
            $result = static::$link->commit(); 
            $this->_trans = 0;
        } 
        return $result;
    }
 
    /** 
    * 事务回滚 
    * @return boolen 
    */
    public function rollback()
    {
        $result = true;
        if ($this->_trans>0)
        {
            $result = static::$link->rollback();
            $this->_trans = 0;
        }
        return $result;
    }
 
    /**
    * 关闭连接
    * PHP 在脚本结束时会自动关闭连接。
    */
    public function close()
    {
        if (!is_null(static::$link)) static::$link = null;
    }

    /** 
    * 获取最近一次查询的sql语句 
    * @return String 执行的SQL 
    */
    public function get_Sql()
    { 
        return $this->_sql;
    }

    public function lastInsertId()
    {
        return static::$link->lastInsertId();
    }
}
