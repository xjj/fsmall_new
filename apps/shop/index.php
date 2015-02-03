<?php
namespace shop;

if (!defined('START')) exit('No direct script access allowed.');

use model as md;

/**
 *	首页控制器
 */
class Index extends Front {
	
	function index(){
		
		//echo date('Y-m-d H:i:s', 1419476266);
		
		$this -> smarty -> assign('title', '首页');
		$this -> smarty -> display('index.tpl');
	}
}