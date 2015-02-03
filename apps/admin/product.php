<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use admin\product as product;

/**
 +------------------------------
 *	商品控制器
 +------------------------------
 */
class Product extends Front {
	
	function index(){
		$this -> items();
	}
	
	//列表
	function items(){
		$p = $this -> params[0];
		$ps = array('items','edit','del','sale', 'sale_cancle', 'soldout', 'soldout_cancle', 'sale_cancle_multi', 'del_multi','pics');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$product = new product\product();
		$product -> $p();
	}
		
	//品牌
	function brand(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del','show','hide','cat');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$brand = new product\brand();
		$brand -> $p();
		
	}
	
	//类目
	function category(){
		$p = $this -> params[0];
		$ps = array('items','add','edit','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$cat = new product\category();
		$cat -> $p();
	}
	
	//打折促销
	function discount(){
		$p = $this -> params[0];
		$ps = array('category','brand','product','spot','grade');
		if (!in_array($p, $ps)){$p = 'category';}
		
		$p2 = $this -> params[1];
		$ps2 = array('items','add','edit','del','show','hide','over');
		if (!in_array($p2, $ps2)){$p2 = 'items';}
		
		switch ($p){
			case 'category':
				$disc = new product\discount_category();
				break;
			case 'brand':
				$disc = new product\discount_brand();
				break;
			case 'product':
				$disc = new product\discount_product();
				break;
			case 'spot':
				$disc = new product\discount_spot();
				break;
			case 'grade':
				$disc = new product\discount_grade();
				break;
		}
		$disc -> $p2();
	}
	
	//添加商品
	function add(){
		$cat_id = $this -> params[0];
		$product = new product\product();
		if (isint($cat_id) && $cat_id > 0){
			$product -> add();
		} else {
			$product -> cat();
		}
	}
	
	//断货
	function soldout(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'duan');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$soldout = new product\soldout();
		$soldout -> $p();
	}
	
	//回收站
	function recycle(){
		$p  = $this -> params[0];
		$ps = array('items', 'del', 'del_multi', 'back');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$rec = new product\recycle();
		$rec -> $p();
	}
	
	//淘宝类目与属性
	function tb_cat(){
		$p  = $this -> params[0];
		$ps = array('items', 'del', 'edit', 'add', 'attr', 'prop', 'attr_value', 'prop_value');
		if (!in_array($p, $ps)){$p = 'items';}
		
		if ($p == 'attr'){
			$this -> tb_attr();
		} elseif ($p == 'prop'){
			$this -> tb_prop();
		} elseif ($p == 'attr_value'){
			$this -> tb_attr_value();
		} elseif ($p == 'prop_value'){
			$this -> tb_prop_value();
		} else {
			$tb = new product\tb_cat();
			$tb -> $p();
		}
	}
	
	//淘宝属性
	function tb_attr(){
		$tb_cat_id = $this -> params[1];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$p = $this -> params[2];
		$ps = array('items','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$tb = new product\tb_attr();
		$tb -> $p();
	}
	
	//销售属性
	function tb_prop(){
		$tb_cat_id = $this -> params[1];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$p = $this -> params[2];
		$ps = array('items','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$tb = new product\tb_prop();
		$tb -> $p();
	}
	
	//淘宝属性值
	function tb_attr_value(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$p = $this -> params[2];
		$ps = array('items','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$tb = new product\tb_attr_value();
		$tb -> $p();
	}
	
	//淘宝销售属性值
	function tb_prop_value(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$p = $this -> params[2];
		$ps = array('items','del','show','hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$tb = new product\tb_prop_value();
		$tb -> $p();
	}
	//淘宝数据包
	function tbdata(){
		//var_dump($this -> params );
		$p = $this -> params[0];
		$ps = array('items','dotbdata','dotbdata_sel');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$tb = new product\tbdata();
		$tb -> $p();
	}
}