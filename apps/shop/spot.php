<?php
namespace shop;

use model as md;

/**
 +--------------------------------
 *	现货
 +--------------------------------
 */
class Spot extends front {
	
	function index(){
		$page = intval($_GET['page']);
		$page = ($page <= 0) ? 1 : $page;
		$pagesize = 20;
		
		$prd = new md\product();
		$ret = $prd -> spot_items($_GET, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/spot'
		));
		
		$this -> smarty -> assign('title', '现货商品');
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('spot.tpl');	
	}	
}