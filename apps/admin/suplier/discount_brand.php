<?php
namespace admin\suplier;

use model as md;
use admin as adm;

class Discount_Brand extends adm\Front {
	
	//所有品牌折扣信息
	function items(){
		$page = intval($_GET['page']);
		$pagesize = 20;
		if ($page <= 0){$page = 1;}
		
		$brand_discount = new md\suplier_brand_discount();
		$ret = $brand_discount -> search($_GET, $page, $pagesize);	
		$discount_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this -> col.'/brand',
			'params' => $_GET
		));
		
		$params = fetch_url_query($_GET);
		
		$this -> smarty -> assign('more_navs', '品牌折扣列表');
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('suplier/discount_brand_list.tpl');
	}
	
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();	
		}
		
		$brand = new md\brand();
		$brand_items = $brand -> items(1); 
		
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> display('suplier/discount_brand_add.tpl');
	}	
	
	//添加操作
	private function add_submit(){
		$brand_id = intval($_POST['brand_id']);
		$discount = intval($_POST['discount']);
		
		if ($brand_id <= 0){
			cpmsg(false, '请选择品牌！', -1);	
		}
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);	
		}
		
		$brand_discount = new md\suplier_brand_discount();
		$f = $brand_discount -> add($brand_id, $discount);
		if ($f){
			cpmsg(true, '供应商品牌折扣添加成功！', '/'.$this->mod.'/'.$this->col.'/brand');	
		} else {
			cpmsg(false, '品牌折扣添加失败！', -1);	
		}
	}
	
	function show(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$brand_discount = new md\suplier_brand_discount();
		$brand_discount -> validate($id, 1);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/brand';
		if (!empty($params)){$url .= '?'.$params;}
		cpurl($url);
	}
	
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$brand_discount = new md\suplier_brand_discount();
		$brand_discount -> validate($id, 0);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/brand';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
	
	function del(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$brand_discount = new md\suplier_brand_discount();
		$brand_discount -> delete($id);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'/brand';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
}