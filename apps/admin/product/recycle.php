<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 +--------------------------------
 *	回收站页面
 +--------------------------------
 */
class Recycle extends adm\front {
	
	//
	function items(){
		
		$this -> smarty -> display('product/recycle_list.tpl');
	}	
}