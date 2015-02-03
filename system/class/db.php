<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +-----------------------------------
 *	数据库类[MYSQL]
 +-----------------------------------
 */
class DB {
	private $host;			//服务器名
	private $user;			//数据库账户
	private $pwd;			//数据库密码
	private $name;			//数据库名
	private $link;			//连接资源
	private $result;		//查询资源
	public  $sqls = array();//查询语句
	public  $counter = 0;	//查询计数器
	private $_errno = 0;		//错误代码
	
	
	//构造函数
	function __construct($conf = array()){
		if (empty($conf)){
			global $_G;
			$conf = $_G['DB'][0];
		}
		$this -> host = $conf['HOST'];
		$this -> user = $conf['USER'];
		$this -> pwd  = $conf['PWD'];
		$this -> name = $conf['NAME'];
		
		$this -> connect();
	}
	
	//连接数据库
	function connect(){
		$this -> link = @mysql_connect($this -> host, $this -> user, $this -> pwd);
	    if (empty($this -> link)) {
	    	exit('Connect db error.');
	    } else {
	    	mysql_select_db($this -> name);
			mysql_query("set names 'utf8'");
	    }
	}
	
	//关闭数据库连接
	function close(){
		return mysql_close($this -> link);
	}
	
	//执行一条语句
	function query($sql){
		$this -> sqls[] = $sql;
		$this -> result = mysql_query($sql, $this -> link);
		if ($this -> result) {
			$str = strtoupper(substr($sql, 0, 7));
			$str = trim($str);
			if ($str == "INSERT"){
				$rt = mysql_insert_id();
			} elseif ($str == "UPDATE" || $str == "DELETE" || $str == 'REPLACE'){
				$rt = mysql_affected_rows();
			} elseif ($str == 'SELECT'){
				$rt = &$this -> result;
			} else {
				$rt = 0;
			}
			$this -> counter += 1;
			$this -> _errno = 0;
			return $rt;
		} else {
			$this -> _errno = mysql_errno($this -> link);
			return false;
		}
	}
	
	//获取下一行数据
	function fetchNext(){
		$row = mysql_fetch_array($this->result, MYSQL_ASSOC); 
		if ($row){
			return $row;
		}
		return false;
	}
	
	//获取一行数据
	function row($sql){
		$result = $this -> query($sql, $this->link);
		if ($result){
			$row = mysql_fetch_array($result, MYSQL_ASSOC); 
			if ($row){
				return $row;
			}
		} 
		return false;
	}
	
	//获取多行数据
	function rows($sql){
		$result = $this -> query($sql, $this->link);
		if ($result){
			$count = mysql_num_rows($result);
			if ($count > 0){
				for($i = 0; $i < $count; $i++){
					$rows[$i] = mysql_fetch_array($result, MYSQL_ASSOC);
				}
				return $rows; 
			} 	
		} 
		return false;
	}
	
	//插入数据
	function insert($table, $data,$isdebug=false){
		$sql = $this -> implode_field_value($data);
		if($isdebug==true){ return "INSERT INTO `$table` SET $sql"; }
		return $this -> query("INSERT INTO `$table` SET $sql");
	}
	
	//替换数据
	//$data		必须要有主键
	function replace($table, $data){
		$sql = $this -> implode_field_value($data);
		return $this -> query("REPLACE INTO `$table` SET $sql");
	}
	
	//更新数据
	//$condition	字符串或数组
	function update($table, $data, $condition,$isdebug=false){
		$sql = $this -> implode_field_value($data);
		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)){
			$where = $this -> implode_field_value($condition, 'AND');
		} else {
			$where = $condition;
		}
		if($isdebug==true){ echo "UPDATE `$table` SET $sql WHERE $where".'<br>';  }
		return $this -> query("UPDATE `$table` SET $sql WHERE $where"); 
	}
	
	//删除数据
	function delete($table, $condition, $limit = 0){
		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)){
			$where = $this -> implode_field_value($condition, 'AND');
		} else {
			$where = $condition;
		}
		$limit = $limit ? 'LIMIT 0,'.$limit : '';
		return $this -> query("DELETE FROM `$table` WHERE $where $limit");
	}
	
	//查询语句组合函数
	function implode_field_value($array, $glue = ',') {
		$sql = $str = '';
		foreach ($array as $k => $v) {
			$sql .= $str.' `'.$k.'`=\''.encode($v).'\' ';
			$str  = $glue;
		}
		return $sql;
	}
	
	/**
	 *	组合数组成字符串（值）
	 *	主要用于数据库写入值操作[INSERT]
	 */
	function implode_field_val($array, $glue = ','){
		if (empty($array)){return false;}
		$str = $dot = '';
		foreach ($array as $item) {
			$str .= $dot.encode($item);
			$dot  = $glue;
		}
		return $str;
	}
	
	/**
	 *	组合数组成字符串（键）
	 *	主要用于数据库字段名操作
	 */
	function implode_field_key($array, $glue = ','){
		if (empty($array)){return false;}
		$str = $dot = '';
		foreach ($array as $item) {
			$str .= $dot.'`'.$item.'`';
			$dot  = $glue;
		}
		return $str;
	}
	
	//插入多行数据
	//$data		二维数据表，第二维必须有与数据表字段对应的键
	function insert_multi($table, $data){
		if (empty($data) || !is_array($data[0])){return false;}
		
		$glue = $fields = '';
		foreach ($data[0] as $k => $val){
			$fields .= $glue . ' `'.$k.'`';
			$glue = ',';
		}
		
		$glue = $values = '';
		foreach ($data as $item){
			$values .= $glue . ' (';
			$dot = '';
			foreach ($item as $val){
				$values .= $dot. ' \''.encode($val).'\'';
				$dot = ',';
			}
			$values .= ' )';
			$glue = ',';
		}

		$this -> query('INSERT INTO `'.$table.'` ('.$fields.') VALUES '.$values.'');
		return mysql_affected_rows();
		
	}
	
	//更新多行数据
	//$data		二维数据表，第二维必须有与数据表字段对应的键
	function update_multi($table, $data, $condition){
		if (empty($condition) || !is_array($condition[0])){return false;}
		if (empty($data) || !is_array($data)){return false;}
		
		$values = $this -> implode_field_value($data, ',');
		
		$where = '';
		$dot = '';
		foreach ($condition as $item){
			$where .= $dot . ' ( ';
			$where .= $this -> implode_field_value($item, 'AND');
			$where .= ' ) ';
			$dot = 'OR';
		}

		return $this -> query('UPDATE `'.$table.'` SET '.$values.' WHERE '.$where.'');
	}
	
	
	//更新统计数
	function update_counter($table, $data, $condition){
		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)){
			$where = $this -> implode_field_value($condition, 'AND');
		} else {
			$where = $condition;
		}
		
		$sql = $str = '';
		foreach ($data as $k => $v) {
			$sql .= $str.' `'.$k.'`=`'.$k.'`+'. $v .' ';
			$str  = ',';
		}
		
		return $this -> query("UPDATE `$table` SET $sql WHERE $where"); 
	}
	
	//获取错误号
	function errno(){
		return $this -> _errno;
	}
	
	//开启事务
	function begin(){
		mysql_query('START TRANSACTION');
	}
	
	//结束事务
	function end(){
		mysql_query('END');
	}
	
	//回滚
	function rollback(){
		mysql_query('ROLLBACK');
	}
	
	//提交
	function commit(){
		mysql_query('COMMIT');
	}
}
?>
