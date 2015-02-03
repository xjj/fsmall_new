<?php
namespace suplier;

use model as md;

class Product extends Front {
	
	//获取商品列表
	function index(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 30; 
		
		$pn = trim($_GET['pn']);
		$sn = trim($_GET['sn']);
		$ps = trim($_GET['ps']);
		$st = $_GET['st'];	//下单时间 - 开始
		$et = $_GET['et'];	//下单时间 - 结束
		
		$where = '';
		if ($ps == 'sale'){
			$where .= ' AND p.is_soldout = 0';
		} elseif ($ps == 'soldout'){
			$where .= ' AND p.is_soldout = 1';
		} 
		
		if (!empty($pn)){
			$where .= ' AND p.product_name_kr like \'%'.encode($pn).'%\'';
		}
		if (!empty($sn)){
			$where .= ' AND p.product_sn like \'%'.encode($sn).'%\'';
		}
		if (isDate($st)){
			$where .= ' AND p.add_time >= '.strtotime($st).'';
		}
		if (isDate($et)){
			$where .= ' AND p.add_time <= '.strtotime($et).'';
		}
		
		$sql = 'SELECT s.*, p.product_name_kr, p.cat_id, p.product_sn FROM `product_skus` s INNER JOIN `products` p ON s.prd_id = p.prd_id WHERE s.is_delete = 0 AND p.is_spot = 0 and p.is_delete = 0 '.$where.' ORDER BY p.prd_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$product_items = $this -> db -> rows($sql);
		if ($product_items){
			$prd_sku = new md\product_sku();
			$cat = new md\category();
			foreach ($product_items as $key => $item){
				$price_kr = $item['price_kr'];
				$dicount_price = round($price_kr * $_SESSION['suplier']['discount']);
				$product_items[$key]['price_kr_discount'] = $dicount_price;
			}
		}
		
		$sql = 'SELECT COUNT(*) AS num FROM (SELECT 1 FROM `product_skus` s INNER JOIN `products` p ON s.prd_id = p.prd_id WHERE s.is_delete = 0 AND p.is_spot = 0 and p.is_delete = 0 '.$where.') T';
		$row = $this -> db -> row($sql);
		$num = $row['num'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		
		$pg = new \Page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/product',
			'params' => $GET
		));
		
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('product.tpl');
	}
	
	//断货流程
	private function sub_soldout($sku_id){
		$prd_sku = new md\Product_SKU();
		
		//查询该单品信息
		$info = $prd_sku -> info($sku_id);
		if ($info){} else {return false;}
		
		$prd_id = $info['prd_id'];
		
		//查询商品是否还有其他未断货的单品
		$f = false;
		$items = $prd_sku -> items($prd_id, 0);
		if ($items){
			foreach ($items as $item){
				if ($item['sku_id'] != $sku_id){
					$f = true;
					break;
				}
			}	
		}
		
		$soldout = new md\soldout();
		if ($f){
			$soldout -> add($sku_id, 0);
		} else {
			$soldout -> add($sku_id, 1);
		}
	}
	
	//取消流程
	private function sub_cancle_soldout($sku_id){
		$prd_sku = new md\Product_SKU();
		
		//查询该单品信息
		$info = $prd_sku -> info($sku_id);
		if ($info){} else {return false;}
		
		if ($info['is_soldout'] == 0){return false;}
		
		$prd_id = $info['prd_id'];
		
		//取消断货
		$soldout = new md\soldout();
		$soldout -> cancle($sku_id);
	}
	
	
	//断货
	function soldout(){
		$sku_id = intval($this -> params[0]);
		if ($sku_id <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);
		}
		
		//断货流程
		$this -> sub_soldout($sku_id);
		
		$url = '/product';
		$params = fetch_url_query($_GET);
		if (!empty($params)){
			$url .= '?'.$params;
		}
		cpurl($url);
	} 
	
	//取消断货
	function cancle_soldout(){
		$sku_id = intval($this -> params[0]);
		if ($sku_id <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);
		}
		
		$this -> sub_cancle_soldout($sku_id);
		
		$url = '/product';
		$params = fetch_url_query($_GET);
		if (!empty($params)){
			$url .= '?'.$params;
		}
		cpurl($url);
	}
	
	
	//批量断货
	function soldout_multi(){
		$sku_ids = $_POST['sku_id'];
		if (!empty($sku_ids)){
			$ids = explode(',', $sku_ids);
			foreach ($ids as $sku_id){
				if (isint($sku_id)){
					$this -> sub_soldout($sku_id);
				}
			}
		}
		echo json_encode(array('error' => 0));
	}
	
	//批量取消断货
	function soldout_cancle_multi(){
		$sku_ids = $_POST['sku_id'];
		if (!empty($sku_ids)){
			$ids = explode(',', $sku_ids);
			foreach ($ids as $sku_id){
				if (isint($sku_id)){
					$this -> sub_cancle_soldout($sku_id);
				}
			}
		}
		echo json_encode(array('error' => 0));
	}
	
}