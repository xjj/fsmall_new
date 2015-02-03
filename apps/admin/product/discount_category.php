<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 +-------------------------------
 *	类目折扣控制器
 +-------------------------------
 */

class Discount_Category extends adm\Front {
	
	//类目折扣列表
	function items(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		//清除过期的折扣
		$disc = new md\discount_category();
		$disc -> clear();
		
		//折扣列表
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
			'url' => '/'.$this->mod.'/'.$this->col.'/category',
		));
		
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discType', 'category');
		$this -> smarty -> assign('more_navs', '类目折扣');
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> display('product/discount_category_list.tpl');
	}
	
	
	//类目折扣添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		}
		
		//查询一级类目
		$cat = new md\category();
		$category_items = $cat -> child_items(0, 1);
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		$this -> smarty -> assign('more_navs', '添加类目折扣');
		$this -> smarty -> assign('category_items', $category_items);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> display('product/discount_category_add.tpl');
	}
	
	//添加操作
	function add_submit(){
		
		//获取类目ID
		$cat_id1 = intval($_POST['cat_id1']);
		$cat_id2 = intval($_POST['cat_id2']);
		$cat_id3 = intval($_POST['cat_id3']);
		$brand_id = intval($_POST['brand_id']);
		$discount = intval($_POST['discount']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if (isint($cat_id3) && $cat_id3 > 0){
			$cat_id = $cat_id3;
		} else {
			cpmsg(false, '请选择三级类目，如果多个三级类目请逐一添加！', -1);
		}
		
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);
		}
		if (!isDate($start_time)){
			cpmsg(false, '开始时间格式不正确！', -1);
		}
		if (!isDate($end_time)){
			cpmsg(false, '结束时间格式不正确！', -1);
		}
		
		$disc = new md\discount_category();
		$f = $disc -> add(array(
			'cat_id' => $cat_id,
			'brand_id' => $brand_id,
			'discount' => $discount,
			'start_time' => $start_time,
			'end_time' => $end_time
		));
		if ($f){
			cpmsg(true, '添加折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/category');
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
		
		$disc = new md\discount_category();
		$disc_data = $disc -> info($id);
		if ($disc_data){} else {
			cpmsg(false, '该折扣信息不存在或已被删除！', -1);
		}
		
		if ($disc_data['is_over'] == 1){
			cpmsg(false, '该折扣信息已结束，不可编辑！', -1);
		}
		
		$cat_id = $disc_data['cat_id'];
		if ($cat_id <= 0){
			cpmsg(false, '该促销信息的数据不正确，请与管理员联系！', -1);
		}
		
		//查询该类目信息
		$cat = new md\category();
		$cat_info = $cat -> info($cat_id);
		if ($cat_info){} else {
			cpmsg(false, '促销中的类目信息查询失败，该类目不存在或已被删除！', -1);
		}
		
		//获取当前类目的层级
		$layer = $cat_info['layer'];
		
		//获取一级二级三级类目列表
		//获取一级二级三级类目ID
		$category_items2 = $category_items3 = false;
		$cat_id1 = $cat_id2 = $cat_id3 = 0; 
		if ($layer == 2){
			//查询二级类目
			$category_items2 = $cat -> sibling_items($cat_id, 1);
			$category_items3 = $cat -> child_items($cat_id, 1);
			$cat_id2 = $cat_id;
			$cat_id1 = $cat_info['parent_id'];
		} elseif ($layer == 3){
			//查询二级类目
			$category_items2 = $cat -> sibling_items($cat_info['parent_id'], 1);
			//查询三级类目
			$category_items3 = $cat -> sibling_items($cat_id, 1);
			
			$cat_id3 = $cat_id;
			$cat_id2 = $cat_info['parent_id'];
			
			$cat_info2 = $cat -> info($cat_id2);
			if ($cat_info2){
				$cat_id1 = $cat_info2['parent_id'];
			}
		} else {
			$category_items2 = $cat -> child_items($cat_id, 1);
			$cat_id1 = $cat_id;
		}
		//查询一级类目
		$category_items = $cat -> child_items(0, 1);
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		$this -> smarty -> assign('disc_data', $disc_data);
		$this -> smarty -> assign('category_items',  $category_items);
		$this -> smarty -> assign('category_items2', $category_items2);
		$this -> smarty -> assign('category_items3', $category_items3);
		$this -> smarty -> assign('cat_id1', $cat_id1);
		$this -> smarty -> assign('cat_id2', $cat_id2);
		$this -> smarty -> assign('cat_id3', $cat_id3);
		$this -> smarty -> assign('brand_items',  $brand_items);
		$this -> smarty -> display('product/discount_category_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$id = $_POST['id'];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$cat_id1 = intval($_POST['cat_id1']);
		$cat_id2 = intval($_POST['cat_id2']);
		$cat_id3 = intval($_POST['cat_id3']);
		$brand_id = intval($_POST['brand_id']);
		$discount = intval($_POST['discount']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if (isint($cat_id3) && $cat_id3 > 0){
			$cat_id = $cat_id3;
		} else {
			cpmsg(false, '请选择三级类目，如果多个三级类目请逐一添加！', -1);
		}
		
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);
		}
		if (!isDate($start_time) || !isDate($end_time)){
			cpmsg(false, '开始或结束时间格式不正确！', -1);
		}
		
		$disc = new md\discount_category();
		$f = $disc -> update($id, array(
			'cat_id' => $cat_id,
			'brand_id' => $brand_id,
			'discount' => $discount,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		if ($f > 0){
			cpmsg(true, '编辑折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/category');
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
		
		$disc = new md\discount_category();
		$f = $disc -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/category?page='.$page);
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
		
		$disc = new md\discount_category();
		$disc -> validate($id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/category?page='.$page);
	}
	
	//隐藏
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_category();
		$disc -> validate($id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/category?page='.$page);
	}
	
	//设为结束
	function over(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_category();
		$disc -> over($id);
		cpurl('/'.$this->mod.'/'.$this->col.'/category?page='.$page);
	}
}