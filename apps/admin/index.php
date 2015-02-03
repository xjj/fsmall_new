<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed.');

/**
 *	首页控制器
 */
class Index extends Front {
	
	//首页
	function index(){
		
		
		
		$this -> smarty -> display('index.tpl');
	}
	
	//设置左侧导航的显示和隐藏
	function sidenav(){
		$f = $_COOKIE['AdminSideBar'];
		if (empty($f)){
			$f = 1; //隐藏 
		} else {
			$f = 0; //显示
		}
		$expired = time() + 604800;
		setcookie("AdminSideBar", $f, $expired, '/', '');
		echo json_encode(array('error' => 0, 'status' => $f));
	}
}