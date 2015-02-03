<?php
namespace admin;

use admin\user as user;

/**
 +--------------------------
 *	会员管理
 +--------------------------
 */

class User extends Front {
	
	function index(){
		$this -> items();
	}
	
	//会员列表
	function items(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'edit', 'addr', 'account');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$user = new user\user();
		$user -> $p();
		
	}
	
	//会员等级
	function grade(){
		$p = $this -> params[0];
		$ps = array('items', 'add', 'del', 'edit', 'brand', 'hide', 'show');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$grade = new user\grade();
		$grade -> $p();
	}
	//会员积分
	function score(){
		$p = $this -> params[0];
		$ps = array('items', 'add');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$score = new user\score();
		$score -> $p();
	}
	//资金管理
	function money(){
		$p = $this -> params[0];
		$ps = array('items', 'add');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$money = new user\money();
		$money -> $p();
	}
	
	//退货管理
	function refund(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'edit');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$refund = new user\refund();
		$refund -> $p();
	}
	
	//第三方登录
	function otherlogin(){
		$p = $this -> params[0];
		$ps = array('items', 'del');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$otherlogin = new user\otherlogin();
		$otherlogin -> $p();
	}
	
	//批发会员审核
	function upgrade(){
		$p = $this -> params[0];
		$ps = array('items', 'agree','disagree');
		if (!in_array($p, $ps)){$p = 'items';}
		$upgrade = new user\upgrade();
		$upgrade -> $p();
	}
}