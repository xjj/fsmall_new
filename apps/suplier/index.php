<?php
namespace suplier;

if (!defined('START')) exit('No direct script access allowed.');

use model as md;

/**
 *	首页控制器
 */
class Index extends Front {
	
	//首页
	function index(){
		cpurl('/order');
	}
	
	//设置左侧导航的显示和隐藏
	function sidenav(){
		$status = $_COOKIE['sidebar_status'];
		if (empty($status)){
			$status = 1; //隐藏 
		} else {
			$status = 0; //显示
		}
		$expired = time() + 604800;
		setcookie("sidebar_status", $status, $expired, '/', '');
		echo json_encode(array('error' => 0, 'status' => $status));
	}
}