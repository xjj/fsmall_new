<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +------------------------------
 *	路由类
 +------------------------------
 */
class Router {
	private $params = array(); 	//除class和method外的参数
	private $routers = array();
	private $class;
	private $method;
	private $segments = array();
	
	function __construct(){
		$this -> routers = load_config('router', 'return');
		$this -> _parse_url();
		$this -> _parse_routes();

	}
	
	//解析路径
	function _parse_url($path = ''){
		if ($path == ''){
			if (!isset($_SERVER['PATH_INFO'])){
				$path = '';
			} else {	
				$path = $_SERVER['PATH_INFO'];
			}
		}
		$path = trim($path, '/');
		$arr = explode('/', $path);
		if (empty($arr[0])){
			$this->class = $arr[0] = 'index';
			$this->method = $arr[0] = 'index';
		} else {
			$this->class = $arr[0];
			$this->method = isset($arr[1]) ? $arr[1] : 'index';
			if (isset($arr[2])){
				$this->params = array_slice($arr, 2);
			}
		}
		$this->segments = $arr;
	}
	
	//解析路由
	function _parse_routes(){
		$uri = implode('/', $this -> segments);
		
		if (empty($this -> routers)){return false;}

		foreach ($this -> routers as $key => $val) {
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			
			if (preg_match('#^'.$key.'$#', $uri)){
				if (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE){
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}
				
				$this -> _parse_url($val);
			}
		}
	}
	
	//获取class
	function fetch_class(){
		return $this->class;
	}
	
	//获取method
	function fetch_method(){
		return $this->method;
	}
	
	//获取params
	function fetch_params(){
		return $this->params; 
	}	
}