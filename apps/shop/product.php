<?php
namespace shop;

use model as md;

/**
 +----------------------------
 *	商品控制器
 +----------------------------
 */

class Product extends Front {
	
	//商品页
	function index(){
		$this -> detail();
	}
	
	//商品显示页
	function detail(){
	
		$prd_id = $this -> params[0];
		if (!isint($prd_id) || $prd_id <= 0){
			cpmsg(false, '错误的请求地址！', -1);
		}
		
		$user  = new md\user();
		
		$uid = intval($_SESSION['uid']);
		$grade_id = intval($_SESSION['grade_id']);
		if ($grade_id <= 0){$grade_id = 0;}
		
		//获取商品的信息
		$product = new md\product();
		$product_data = $product -> data($prd_id, $uid, false);
		if ($product_data){
			$cat_id = $product_data['cat_id'];
		} else {
			$message = new message();
			$message -> Product_Not_Exist();
			exit();
		}
		
		//查询商品的详细图
		
		
		//获取商品类目路径
		$cat = new md\category();
		$cat_path_data = $cat -> parent_path($cat_id);
		
		//查询商品淘宝类目ID
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){
			$tb_cat_id = $cat_data['tb_cat_id'];
		} else {
			cpmsg(false, '该商品的类目信息已不存在，无法获取商品信息！', -1);
		}
		
		//获取商品的所有销售属性
		$product_prop = new md\product_prop();
		$product_prop_items = $product_prop -> items($prd_id);
		
		//查询分组的销售属性
		$group_items = $product_prop -> group_items($prd_id);
		
		
		//查询商品的所有SKU
		$sku_data = false;
		$product_sku = new md\product_sku();
		$product_sku_items = $product_sku -> items($prd_id, 'all'); //断货的也显示
		
		//计算SKU的价格
		$prd_price = new md\product_price();
		$params = array(
			'brand_id' => $product_data['brand_id'],
			'cat_id' => $product_data['cat_id'],
			'is_spot' => $product_data['is_spot'],
			'freight' => $product_data['freight'],
			'is_freight' => $product_data['is_freight'],
		);
		
		//循环SKU重新计算价格
		if ($product_sku_items){
			foreach ($product_sku_items as $key => $item){
				$params['price'] = $item['price'];
				$ret = $prd_price -> price($prd_id, $params, $uid, $grade_id);
				$product_sku_items[$key]['price'] = $ret['price'];
			}
		}
		
		$this -> smarty -> assign('title', $product_data['product_name']);
		$this -> smarty -> assign('product_data', $product_data);
		$this -> smarty -> assign('keywords', $prd_data['keywords']);
		$this -> smarty -> assign('description', $prd_data['description']);
		
		$this -> smarty -> assign('product_prop_items', $product_prop_items);
		$this -> smarty -> assign('group_items', $group_items);
		$this -> smarty -> assign('cat_path_data', $cat_path_data);
		$this -> smarty -> assign('product_sku_items', $product_sku_items);
		$this -> smarty -> assign('sku_data', $sku_data);
		$this -> smarty -> display('product.tpl');
	}
}