<?php
namespace shop;

use model as md;

if (!defined('START')) exit('No direct script access allowed');

/**
 +---------------------------
 *	类目控制器
 +---------------------------
 */
class Category extends Front {
	
	//类目页
	function index(){
		$this -> items();
	}
	
	//列表页
	function items(){
		$cat_id = $this -> params[0];
		
		$cat = new md\category();
		
		//根据类目ID获取类目路径 -- 输出面包屑导航
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){} else {
			cpmsg(false, '抱歉，该商品类目不存在或已被移除！', -1);
		}
		
		$title = '';
		$cat_path_data = $cat -> parent_path($cat_id);
		if ($cat_path_data){
			foreach ($cat_path_data as $item){
				$title  .= $item['cat_name'].'_';
			}
		}
		$title = rtrim($title, '_');
		
		//查询类目的下级或同级类目
		if ($cat_data['layer'] == 3){
			$sibling_items = $cat -> sibling_items($cat_id, 1);	
		} else {
			$child_items = $cat -> child_items($cat_id, 1);	
		}
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$prd = new md\product();
		$data = array(
			'cat_id' => $cat_id,
			'type' => $_GET['type'],
			'brand_id' => $_GET['brand_id'],
			'order' => $_GET['order'],
			'start_price' => $_GET['sp'],
			'end_price' => $_GET['ep'],
			
			'uid' => $_SESSION['uid'],
		);
		$ret = $prd -> search2($data, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/category/'.$cat_id,
			'str' => $_GET
		));
		
		$params = fetch_url_query($_GET, 'page');
		
		$pagecount = ($total % $pagesize == 0) ? intval($total / $pagesize) : ceil($total / $pagesize);
		
		$this -> smarty -> assign('title', $title);
		$this -> smarty -> assign('cat_data', $cat_data);
		$this -> smarty -> assign('sibling_items', $sibling_items);
		$this -> smarty -> assign('child_items', $child_items);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('cat_path_data', $cat_path_data);
		
		$this -> smarty -> assign('page', $page);
		$this -> smarty -> assign('pagecount', $pagecount);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('category.tpl');
	} 
	
	
	/**
	 *	获取子类目数据[JSON]
	 *	url=/category/children/{$parent_id}
	 */
	function children(){
		$parent_id = $this -> params[0];
		if (!isint($parent_id) || $parent_id <= 0){
			echo json_encode(array('error' => 1, 'message' => 'Category ID Error！'));
			exit();
		}
		
		$cat = new md\category();
		$items = $cat -> children($parent_id, 1);
		if ($items){
			$data = array();
			foreach ($items as $row){
				$data[] = array(
					'cat_id' 	=> $row['cat_id'],
					'parent_id' => $row['parent_id'],
					'cat_name' 	=> $row['cat_name'],
					'cat_tb_id' => $row['cat_tb_id'],
					'cat_tb_name' => $row['cat_tb_name'],
					'weight' 	=> $row['weight'],
					'layer' 	=> $row['layer']
				);
			}
			echo json_encode(array('error' => 0, 'data' => $data));
		} else {
			echo json_encode(array('error' => 0, 'data' => ''));	
		}
	}
}