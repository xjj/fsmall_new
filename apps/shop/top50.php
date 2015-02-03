<?php
namespace shop;

use model as md;

/**
 +-------------------------------
 *	
 +-------------------------------
 */
class Top50 extends Front {
	
	//查询前排50条商品 -- 可选条件类目、品牌
	function index(){
		
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		//查询所有类目信息
		$cat = new md\category();
		$cat_items = $cat -> layer_child_items(1, 1);
		
		$prd = new md\product();
		$product_items = $prd -> top($_GET, 50);
		
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> display('top50.tpl');
	}	
}
