<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------------
 *	控制器类
 +-------------------------------
 */
class Controller {
	public $db;
	public $RTR;
	public $smarty;
	
	public $class;
	public $method;
	public $params;
	
	function __construct(){
		$this -> db 	= fetch_instance('DB');
		$this -> smarty = fetch_instance('SMARTY');
		$this -> RTR 	= fetch_instance('RTR');
		
		$this->class  = $this -> RTR -> fetch_class();
		$this->method = $this -> RTR -> fetch_method();
		$this->params = $this -> RTR -> fetch_params();
	}
	
	//页面控制器最初入口
	function init(){
		$class = APP_PATH.'\\'. $this->class;
		$method = $this->method;
		load_class($class);
		
		$cls = new $class();
		if (method_exists($class, $method)){
			$cls -> $method();
			exit();
		} else {
			exit('the method is not exist.');
		}
	}
	//记录日志 系统异常或程序出错时记录
	function add_log($data){
		if($data){
			$f = $this -> db -> insert('system_error_log', $data);
			return $f;
		}
	}
}
?>