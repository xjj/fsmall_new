<?php
namespace shop;

use model as md;

/**
 +---------------------------------
 *	搜索
 +---------------------------------
 */
class Search extends Front {
	
	function index(){
		$kw = trim($_GET['kw']);
		if (empty($kw)){
			cpmsg(false, '请填写搜索关键词！', -1);	
		}
		
		$page = intval($_GET['page']);
		$page = ($page <= 0) ? 1 : $page;
		$pagesize = 20;
		
		$prd = new md\product();
		$ret = $prd -> search3($_GET, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		//查询所有类目信息
		$cat = new md\category();
		$cat_items = $cat -> layer_child_items(1, 1);
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/search',
			'params' => fetch_url_query($_GET, 'page'),
		));
		
		$this -> smarty -> assign('title', urldecode($kw).'_搜索');
		$this -> smarty -> assign('pagebox', $pg -> show());
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> display('search.tpl');	
	}
}
