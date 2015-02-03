<?php
namespace admin\product;

use model as md;
use admin as adm;
/**
 +----------------------------------
 *	现货折扣控制器
 +----------------------------------
 */
class Discount_Spot extends adm\Front {
	
	//现货折扣列表
	function items(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		//清除过期的折扣
		$disc = new md\discount_spot();
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
			'url' => '/'.$this->mod.'/'.$this->col.'/spot',
		));
		
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('discType', 'spot');
		$this -> smarty -> assign('more_navs', '现货折扣');
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> display('product/discount_spot_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		}
		
		$this -> smarty -> assign('more_navs', '添加现货折扣');
		$this -> smarty -> display('product/discount_spot_add.tpl');
	}
	
	//添加操作
	function add_submit(){
		
		$discount = intval($_POST['discount']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);
		}
		if (!isDate($start_time)){
			cpmsg(false, '开始时间格式不正确！', -1);
		}
		if (!isDate($end_time)){
			cpmsg(false, '结束时间格式不正确！', -1);
		}
		
		$disc = new md\discount_spot();
		$f = $disc -> add(array(
			'discount' => $discount,
			'start_time' => $start_time,
			'end_time' => $end_time
		));
		if ($f){
			cpmsg(true, '添加折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/spot');
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
		
		$disc = new md\discount_spot();
		$disc_data = $disc -> info($id);
		if ($disc_data){} else {
			cpmsg(false, '该折扣信息不存在或已被删除！', -1);
		}
		
		if ($disc_data['is_over'] == 1){
			cpmsg(false, '该折扣信息已结束，不可编辑！', -1);
		}
		
		$this -> smarty -> assign('disc_data', $disc_data);
		$this -> smarty -> display('product/discount_spot_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$id = $_POST['id'];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$discount = intval($_POST['discount']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if ($discount < 1 || $discount > 99){
			cpmsg(false, '折扣值范围错误！', -1);
		}
		if (!isDate($start_time) || !isDate($end_time)){
			cpmsg(false, '开始或结束时间格式不正确！', -1);
		}
		
		$disc = new md\discount_spot();
		$f = $disc -> update($id, array(
			'discount' => $discount,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		if ($f > 0){
			cpmsg(true, '编辑折扣信息成功！', '/'.$this->mod.'/'.$this->col.'/spot');
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
		
		$disc = new md\discount_spot();
		$f = $disc -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/spot?page='.$page);
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
		
		$disc = new md\discount_spot();
		$disc -> validate($id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/spot?page='.$page);
	}
	
	//隐藏
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_spot();
		$disc -> validate($id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/spot?page='.$page);
	}
	
	//设为结束
	function over(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		
		$disc = new md\discount_spot();
		$disc -> over($id);
		cpurl('/'.$this->mod.'/'.$this->col.'/spot?page='.$page);
	}
}