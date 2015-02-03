<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use admin\suplier as spl;

/**
 +------------------------------
 *	供应商
 +------------------------------
 */

class Suplier extends front {
	
	function index(){
		$this -> profile();
	}
	
	//供应商信息
	function profile(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'add', 'edit');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$disc = new spl\profile();
		$disc -> $p();	
		
	}
	
	//账号
	function users(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'add', 'update','hide','show');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$disc = new spl\user();
		$disc -> $p();	
	}
	//账目
	function account(){
			
	} 
	
	//折扣
	function discount(){
		$p0 = $this -> params[0];
		if ($p0 != 'product'){$p0 = 'brand';}
		
		if ($p0 == 'brand'){
			$p = $this -> params[1];
			$ps = array('items', 'del', 'add', 'show', 'hide');
			if (!in_array($p, $ps)){$p = 'items';}
			
			$disc = new spl\discount_brand();
			$disc -> $p();	
		} else {
			$p = $this -> params[1];
			$ps = array('items', 'del', 'add', 'show', 'hide', 'clear');
			if (!in_array($p, $ps)){$p = 'items';}	
			
			$disc = new spl\discount_product();
			$disc -> $p();	
		}	
	}
}