<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 +-------------------------------
 *	商品折扣控制器
 +-------------------------------
 */
class Discount_Product extends adm\Front {
	
	//类目折扣列表
	function items(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		//清除过期
		$disc = new md\discount_product();
		$disc -> clear();
		
		$ret = $disc -> items($page, $pagesize);
		if ($ret){
			$discount_items = $ret['items'];
			$total = $ret['total'];
		} else {
			$discount_items = false;
			$total = 0;
		}
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col.'/product',
		));
		
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discType', 'product');
		$this -> smarty -> assign('more_navs', '商品折扣');
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> display('product/discount_product_list.tpl');
	}
	
	
	//类目折扣添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		}
		
		$this -> smarty -> assign('more_navs', '添加商品折扣');
		$this -> smarty -> display('product/discount_product_add.tpl');
	}
	
	//添加操作
	function add_submit(){
		$prd_id   = intval($_POST['prd_id']);
		$uid = trim($_POST['uid']);		//用户名或ID
		$discount = intval($_POST['discount']);
		$price = intval($_POST['price']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if ($prd_id <= 0){
			cpmsg(false, '请输入商品id！', -1);
		}
		if ($discount >= 1 && $discount <= 99){
			$price = 0;
		} else {
			$discount = 0;
			if ($price == 0){
				cpmsg(false, '折扣值与价格至少要填写一个！', -1);
			}
		}
		if (!isDate($start_time) || !isDate($end_time)){
			cpmsg(false, '开始或结束时间格式不正确！', -1);
		}
		
		$start_line = strtotime($start_time);
		$end_line 	= strtotime($end_time);
		$now_line 	= time();
		if ($end_line < $now_line || $start_line > $end_line){
			cpmsg(false, '结束时间不正确！', -1);
		}
		$disc = new md\discount_product();
		$f = $disc -> add(array(
			'prd_id' => $prd_id,
			'uid' => $uid,
			'discount' => $discount,
			'price' => $price,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		if ($f){
			cpmsg(true, '添加折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/product');
		} else {
			cpmsg(false, '添加折扣信息失败！', -1);
		}
	}
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
			exit();
		}
		
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '请求错误！', -1);
		}
		
		$disc = new md\discount_product();
		$disc_data = $disc -> info($id);
		if ($disc_data){} else {
			cpmsg(false, '该折扣信息不存在或已被删除！', -1);
		}
		
		if ($disc_data['is_over'] == 1){
			cpmsg(false, '已结束的促销信息，不能被修改！', -1);
		}
		
		$this -> smarty -> assign('disc_data', $disc_data);
		$this -> smarty -> display('product/discount_product_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$id = intval($_POST['id']);
		$prd_id = intval($_POST['prd_id']);
		$uid = trim($_POST['uid']);		//用户名或ID
		$discount = intval($_POST['discount']);
		$price = intval($_POST['price']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if ($id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if ($prd_id <= 0){
			cpmsg(false, '请输入商品id！', -1);
		}
		if ($discount >= 1 && $discount <= 99){
			$price = 0;
		} else {
			$discount = 0;
			if ($price == 0){
				cpmsg(false, '折扣值与价格至少要填写一个！', -1);
			}
		}
		if (!isDate($start_time) || !isDate($end_time)){
			cpmsg(false, '开始或结束时间格式不正确！', -1);
		}
		
		$start_line = strtotime($start_time);
		$end_line 	= strtotime($end_time);
		$now_line 	= time();
		if ($end_line < $now_line || $start_line > $end_line){
			cpmsg(false, '结束时间不正确！', -1);
		}
		
		$disc = new md\discount_product();
		$f = $disc -> update($id, array(
			'prd_id' => $prd_id,
			'uid' => $uid,
			'discount' => $discount,
			'price' => $price,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		if ($f > 0){
			cpmsg(true, '编辑折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/product');
		} else {
			cpmsg(false, '编辑折扣信息失败！', -1);
		}
	}
	
	//删除
	function del(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_product();
		
		$f = $disc -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/product?page='.$page);
		} else {
			cpmsg(false, '促销信息删除失败！', -1);
		}
	}
	
	//显示
	function show(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_product();
		$disc -> validate($id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/product?page='.$page);
	}
	
	//隐藏
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_product();
		$disc -> validate($id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/product?page='.$page);
	}
	
	//设为结束
	function over(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_product();
		$disc -> over($id);
		cpurl('/'.$this->mod.'/'.$this->col.'/product?page='.$page);
	}
}