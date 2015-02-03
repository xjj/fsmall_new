<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use admin\system as sys;

/**
 +------------------------------
 *	系统页面
 +------------------------------
 */
class System extends Front {
	
	function index(){
		$this -> setting();
	}
	
	//网站配置
	function setting(){
		#$this -> smarty -> display('system/setting.tpl');
		$p = $this -> params[0];
		$ps = array('items','add','edit','del');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$setting = new sys\setting();
		$setting -> $p();
	}
	
	//汇率
	function rate(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$rate = new sys\rate();
		$rate -> $p();
		
	}
	
	//支付
	function payment(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del','hide', 'show');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$payment = new sys\payment();
		$payment -> $p();
		
	}
	
	//地区
	function region(){
		$p = $this -> params[0];
		$ps = array('items', 'add', 'edit', 'del', 'bat_edit', 'children');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$region = new sys\region();
		$region -> $p();
		
	}
	
	//管理员
	function user(){
		$p = $this -> params[0];
		$ps = array('items', 'add', 'edit', 'del', 'auth', 'logs', 'show', 'hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$user = new sys\user();
		$user -> $p();
	}
	
	//配送
	function shipping(){
		$p = $this -> params[0];
		$ps = array('items', 'add', 'edit', 'del', 'fee', 'show', 'hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$shipping = new sys\shipping();
		$shipping -> $p();
	}
}