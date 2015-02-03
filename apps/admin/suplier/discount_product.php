<?php
namespace admin\suplier;

use model as md;
use admin as adm;

class Discount_Product extends adm\Front {
	
	//所有商品折扣信息
	function items(){
		$page = intval($_GET['page']);
		$pagesize = 20;
		if ($page <= 0){$page = 1;}
		
		$prd_discount = new md\suplier_product_discount();
		$ret = $prd_discount -> search($_GET, $page, $pagesize);	
		$discount_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this -> col.'/product',
			'params' => $_GET
		));
		
		$params = fetch_url_query($_GET);
		
		$this -> smarty -> assign('more_navs', '商品折扣列表');
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('suplier/discount_product_list.tpl');
	}
	
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();	
		}

		$this -> smarty -> display('suplier/discount_product_add.tpl');
	}	
	
	//添加操作
	private function add_submit(){
		$prd_id = intval($_POST['prd_id']);
		$discount = intval($_POST['discount']);
		
		if ($prd_id <= 0){
			cpmsg(false, '请填写商品ID！', -1);	
		}
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);	
		}
		
		$prd_discount = new md\suplier_product_discount();
		$f = $prd_discount -> add($prd_id, $discount);
		if ($f){
			cpmsg(true, '供应商品牌折扣添加成功！', '/'.$this->mod.'/'.$this->col.'/product');	
		} else {
			cpmsg(false, '品牌折扣添加失败！', -1);	
		}
	}
	
	//
	function show(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$prd_discount = new md\suplier_product_discount();
		$prd_discount -> validate($id, 1);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/product';
		if (!empty($params)){$url .= '?'.$params;}
		cpurl($url);
	}
	
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$prd_discount = new md\suplier_product_discount();
		$prd_discount -> validate($id, 0);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/product';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
	
	function del(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$prd_discount = new md\suplier_product_discount();
		$prd_discount -> delete($id);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/product';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
	
	//清理 -- 已断货的商品的折扣信息 清除
	function clear(){
		$prd_discount = new md\suplier_product_discount();
		$num = $prd_discount -> clear();
		
		cpmsg(true, '清理完成，共清理商品信息 '.$num.' 条！', '/'.$this->mod.'/'.$this->col.'/product');	
	}
	
}