<?php
namespace admin\product;

use model as md;
use admin as adm;
/**
 +----------------------------------
 *	会员等级折扣控制器
 +----------------------------------
 */
class Discount_Grade extends adm\Front {
	
	//现货折扣列表
	function items(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		//折扣列表
		$disc = new md\discount_grade();
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
			'url' => '/'.$this->mod.'/'.$this->col.'/grade',
		));
		
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discType', 'grade');
		$this -> smarty -> assign('more_navs', '会员等级折扣');
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> display('product/discount_grade_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		}
		
		//所有会员等级
		$grade = new md\user_grade();
		$grade_items = $grade -> items();
		
		//所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items();
		
		$this -> smarty -> assign('more_navs', '添加会员等级折扣');
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('grade_items', $grade_items);
		$this -> smarty -> display('product/discount_grade_add.tpl');
	}
	
	//添加操作
	//discount 为按百分比填写的折扣如98%
	function add_submit(){
		
		$discount2 = intval($_POST['discount2']);
		$grade_id = intval($_POST['grade_id']);
		$brand_id = intval($_POST['brand_id']);
		
		if ($grade_id <= 0){
			cpmsg(false, '请选择会员等级！', -1);
		}
		if ($discount2 <= 0 || $discount2 >= 200){return false;}
		if ($brand_id <= 0){$brand_id = 0;}
		
		$disc = new md\discount_grade();
		$f = $disc -> isExist($grade_id, $brand_id);
		if ($f){
			cpmsg(false, '该折扣信息已存在！', -1);
		}

		$f = $disc -> add(array('discount2' => $discount2, 'grade_id' => $grade_id, 'brand_id' => $brand_id));
		if ($f){
			cpmsg(true, '添加折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/grade');
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
		
		$disc = new md\discount_grade();
		$disc_data = $disc -> info($id);
		if ($disc_data){} else {
			cpmsg(false, '该折扣信息不存在或已被删除！', -1);
		}
		
		//所有会员等级
		$grade = new md\user_grade();
		$grade_items = $grade -> items();
		
		//所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items();
		
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('grade_items', $grade_items);
		$this -> smarty -> assign('disc_data', $disc_data);
		$this -> smarty -> display('product/discount_grade_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$id = $_POST['id'];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$discount2 = intval($_POST['discount2']);
		$grade_id = intval($_POST['grade_id']);
		$brand_id = intval($_POST['brand_id']);
		
		if ($grade_id <= 0){
			cpmsg(false, '请选择会员等级！', -1);
		}
		if ($discount2 <= 0 || $discount2 >= 200){return false;}
		if ($brand_id <= 0){$brand_id = 0;}
		

		$disc = new md\discount_grade();
		$f = $disc -> isExist($grade_id, $brand_id, $id);
		if ($f){
			cpmsg(false, '该折扣信息已存在！', -1);
		}
		
		$f = $disc -> update($id, array('discount2' => $discount2, 'grade_id' => $grade_id, 'brand_id' => $brand_id));
		if ($f > 0){
			cpmsg(true, '编辑折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/grade');
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
		
		$disc = new md\discount_grade();
		$f = $disc -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/grade?page='.$page);
		} else {
			cpmsg(false, '促销信息删除失败！', -1);
		}
	}
}