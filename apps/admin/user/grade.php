<?php
namespace admin\user;

use admin as adm;
use model as md;

/**
 +---------------------------------
 *	会员等级页面
 +---------------------------------
 */
class Grade extends adm\Front {
	
	//会员等级列表
	function items(){
		$sql = 'SELECT * FROM `user_grade` WHERE is_delete = 0 ORDER BY grade_id ASC';
		$grade_items = $this -> db -> rows($sql);
		
		$this -> smarty -> assign('grade_items', $grade_items);
		$this -> smarty -> display('user/grade_list.tpl');
	}
	
	//添加会员等级
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		$this -> smarty -> display('user/grade_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$grade_name = trim($_POST['grade_name']);
		$discount = intval($_POST['discount']);
		
		if (empty($grade_name)){
			cpmsg(false, '请填写会员等级名称！', -1);
		}
		if ($discount < 1 || $discount > 200){
			cpmsg(false, '折扣值范围不正确，请按提示填写！', -1);
		}
		
		$grade = new md\user_grade();
		$f = $grade -> add(array(
			'grade_name' => $grade_name,
			'discount' => $discount
		));
		if ($f > 0){
			cpmsg(true, '会员等级添加成功！', '/'.$this->mod.'/'.$this->col);	
		} else {
			cpmsg(false, '会员等级添加失败！', -1);
		}
	}
	
	//编辑
	function edit(){
		if(isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$grade_id = $this -> params[1];
		$grade = new md\user_grade();
		$grade_data = $grade -> info($grade_id);
		if ($grade_data){} else {
			cpmsg(false, '该会员等级不存在或已被删除！');
		}
		
		$this -> smarty -> assign('grade_data', $grade_data);
		$this -> smarty -> display('user/grade_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$grade_id = trim($_POST['grade_id']);
		$grade_name = trim($_POST['grade_name']);
		$discount = intval($_POST['discount']);
		
		if (!isint($grade_id) || $grade_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		if (empty($grade_name)){
			cpmsg(false, '请填写会员等级名称！', -1);
		}
		if ($discount < 1 || $discount > 200){
			cpmsg(false, '折扣值范围不正确，请按提示填写！', -1);
		}
		
		$grade = new md\user_grade();
		$num = $grade -> update($grade_id, array(
			'grade_name' => $grade_name,
			'discount' => $discount
		));
		if ($num > 0){
			cpmsg(true, '会员等级信息更新成功！', '/'.$this->mod.'/'.$this->col);	
		} else {
			cpmsg(false, '会员等级信息更新失败！', -1);
		}
	}
	
	//删除
	function del(){
		$grade_id = $this -> params[1];
		
		if (!isint($grade_id) || $grade_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
			
		$grade = new md\user_grade();
		$f = $grade -> delete($grade_id);
		if ($f > 0){
			cpurl('/'.$this->mod.'/'.$this->col);		
		} else {
			cpmsg(false, '删除会员等级信息失败！', -1);
		}
	}
	
	//有效
	function show(){
		$grade_id = $this -> params[1];
		
		if (!isint($grade_id) || $grade_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$grade = new md\user_grade();
		$grade -> show($grade_id);
		cpurl('/'.$this->mod.'/'.$this->col);		
	}
	
	//无效
	function hide(){
		$grade_id = $this -> params[1];
		
		if (!isint($grade_id) || $grade_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$grade = new md\user_grade();
		$grade -> hide($grade_id);
		cpurl('/'.$this->mod.'/'.$this->col);		
	}
	
	//品牌折扣设置
	function brand(){
		$grade_id = $this -> params[1];
		$p = $this -> params[2];
		
		if (!isint($grade_id) || $grade_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$ps = array('items', 'add', 'del');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$method = 'brand_disc_'.$p;
		$this -> $method();
	}
	
	//品牌折扣列表
	private function brand_disc_items(){
		
		$grade_id = $this -> params[1];
		
		$grade = new md\user_grade();
		
		//查询等级信息
		$grade_data = $grade -> info($grade_id);
		if ($grade_data){} else {
			cpmsg(false, '该会员等级不存在或已被删除！');
		}
		
		//查询所有会员品牌折扣
		$discount_items = $grade -> brand_discount_items($grade_id);
		
		$this -> smarty -> assign('grade_data', $grade_data);
		$this -> smarty -> assign('discount_items', $discount_items);
		$this -> smarty -> display('user/grade_brand.tpl');
	} 
	
	//添加品牌折扣信息
	function brand_disc_add(){
		$grade_id = intval($_POST['grade_id']);
		$brand_name = trim($_POST['brand_name']);
		$discount = intval($_POST['discount']);
		
		if (empty($brand_name)){
			cpmsg(false, '请填写品牌名称！', -1);
		}
		if ($discount < 1 || $discount > 200){
			cpmsg(false, '折扣值范围不正确，请按提示填写！', -1);
		}
		
		$brand = new md\brand();
		$brand_id = $brand -> get_id_by_name($brand_name);
		if ($brand_id){} else {
			cpmsg(false, '没有查询到该品牌，请检查品牌名是否正确！', -1);
		}
		
		$grade = new md\user_grade();
		$f = $grade -> add_brand_discount($grade_id, $brand_id, $discount);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/brand/'.$grade_id);
		} else {
			cpmsg(false, '添加品牌折扣失败！', -1);
		}
	}
	
	//删除品牌折扣信息
	function brand_disc_del(){
		$grade_id = $this -> params[1];
		$brand_id = $this -> params[3];
		
		$grade = new md\user_grade();
		$num = $grade -> delete_brand_discount($grade_id, $brand_id);
		if ($num > 0){
			cpurl('/'.$this->mod.'/'.$this->col.'/brand/'.$grade_id);
		} else {
			cpmsg(false, '删除品牌折扣信息失败！', -1);
		}
	}
}