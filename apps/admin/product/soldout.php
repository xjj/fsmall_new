<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 *	断货页面
 */
class SoldOut extends adm\front {
	
	/**
	 *	列表页
	 */
	function items(){
		
		$this -> smarty -> display('product/soldout_list.tpl');
	}	
}