<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use admin\operate as opt;

/**
 +-----------------------------
 *	运营页面
 +-----------------------------
 */
class Operate extends Front {
	
	function index(){
		$this -> items();
	}
	
	//公告
	function notice(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del', 'show', 'hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$notice = new opt\notice();
		$notice -> $p();
	}
	//文章
	function article(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del', 'show', 'hide','cat'); 
		if (!in_array($p, $ps)){$p = 'items';}
		$art = new opt\article();
		$art -> $p();
	}
	//广告位
	function adp(){
		$p = $this -> params[0];
		$ps = array('items', 'edit', 'add', 'del', 'ads');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$adp = new opt\adp();
		$adp -> $p();
	}
	
	//活动
	function event(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$event = new opt\event();
		$event -> $p();
	}
	
	//友情链接
	function friendlink(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$fl = new opt\friendlink();
		$fl -> $p();
	}
	
	//数据更新
	function update(){
		$p = $this -> params[0];
		$ps = array('items','brand_category');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$update = new opt\update();
		$update -> $p();	
	}
	
}