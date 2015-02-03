<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +---------------------------------
 *	淘宝类目与属性
 +---------------------------------
 */
class TB_Cat extends adm\Front {
	
	//列表
	function items(){
		$tb_cat = new md\tb_cat();
		$tb_cat_items = $tb_cat -> items();
		
		$this -> smarty -> assign('tb_cat_items', $tb_cat_items);
		$this -> smarty -> display('product/tb_cat_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		$this -> smarty -> display('product/tb_cat_add.tpl');
	}
	
	
	//添加操作
	function add_submit(){
		$tb_cat_id = intval($_POST['tb_cat_id']);
		$tb_cat_name = trim($_POST['tb_cat_name']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '请填写淘宝类目ID！', -1);
		}
		if (empty($tb_cat_name)){
			cpmsg(false, '请填写淘宝类目名称！', -1);
		}
		
		$tb_cat = new md\tb_cat();
		$f = $tb_cat -> isExist($tb_cat_id);
		if ($f){
			cpmsg(false, '该淘宝类目已存在！', -1);
		}
		
		$tb_cat -> add(array('tb_cat_id' => $tb_cat_id, 'tb_cat_name' => $tb_cat_name, 'displayorder' => $displayorder));
		$f = $tb_cat -> isExist($tb_cat_id);
		if ($f){
			cpmsg(true, '淘宝类目添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '淘宝类目添加失败！', -1);	
		}
	}
	
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
	
		$tb_cat_id = $this -> params[1];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '缺少淘宝类目信息！', -1);
		}
		
		$tb_cat = new md\TB_cat();
		$tb_cat_data = $tb_cat -> info($tb_cat_id);
		
		$this -> smarty -> assign('tb_cat_data', $tb_cat_data);
		$this -> smarty -> display('product/tb_cat_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$tb_cat_id = intval($_POST['tb_cat_id']);
		$tb_cat_name = trim($_POST['tb_cat_name']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '淘宝类目ID不正确！', -1);
		}
		if (empty($tb_cat_name)){
			cpmsg(false, '请填写淘宝类目名称！', -1);
		}
		
		$tb_cat = new md\tb_cat();
		$f = $tb_cat -> update($tb_cat_id, array('tb_cat_name' => $tb_cat_name,'displayorder' => $displayorder));
		if ($f > 0){
			cpmsg(true, '淘宝类目编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '淘宝类目更新失败！', -1);
		}
	}
	
	//删除
	function del(){
		$tb_cat_id = $this -> params[1];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '缺少淘宝类目信息！', -1);
		}
		
		$tb_cat = new md\tb_cat();
		
		$f = $tb_cat -> delete($tb_cat_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '淘宝类目删除失败！', -1);
		}
	}
} 