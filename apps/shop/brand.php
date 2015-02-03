<?php
namespace shop;

use model as md;

/**
 +---------------------------------
 *	品牌页控制器
 +---------------------------------
 */
class Brand extends Front {
	
	//所有品牌
	function index(){	
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		if ($brand_items){
			$data = array();
			foreach ($brand_items as $item){
				$type = $item['type'];
				$data[$type][] = $item;	
			}
		} else {
			$data = false;
		}
		
		$this -> smarty -> assign('title', '品牌中心');
		$this -> smarty -> assign('brand_items', $data);
		$this -> smarty -> display('brand_list.tpl');
	}
	
	//商品列表
	function items(){
		$brand_id = $this->params[0];
		$cat_id = $_GET['cat_id'];
		
		$brand = new md\brand();
		$brand_data = $brand -> info($brand_id);
		
		if (!isint($brand_id) || $brand_id <= 0){
			cpurl('/brand');	
		}
		
		//查询品牌类目
		$brand_cat = new md\brand_category();
		$brand_cat_items = $brand_cat -> items($brand_id);
		
		//查询子类目
		if (isint($cat_id) && $cat_id > 0){
			$cat = new md\category();
			$cat_data = $cat -> info($cat_id);
			
			if ($cat_data && $cat_data['layer'] == 3){
				$cat_items = $cat -> sibling_items($cat_id, 1);	
			} else {
				$cat_items = $cat -> child_items($cat_id, 1);	
			}
		} else {
			$cat_data = false;	
		}
		
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$prd = new md\product();
		$data = array(
			'cat_id' => $_GET['cat_id'],
			'type' => $_GET['type'],
			'brand_id' => $brand_id,
			'order' => $_GET['order'],
			'start_price' => $_GET['sp'],
			'end_price' => $_GET['ep'],
			
			'uid' => $_SESSION['uid'],
		);
		$ret = $prd -> search2($data, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		$params = fetch_url_query($_GET, 'page');
		
		$this -> smarty -> assign('title', $brand_data['brand_name'].'_品牌中心');
		$this -> smarty -> assign('brand_data', $brand_data);
		$this -> smarty -> assign('brand_cat_items', $brand_cat_items);
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> assign('cat_data', $cat_data);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('brand.tpl');
	}
}