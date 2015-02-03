<?php
namespace admin\operate;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +-----------------------------------
 *	文章分类
 +-----------------------------------
 */
class Article_Category extends adm\Front {
	
	function items(){
		$art_cat = new md\article_category();
		$cat_items = $art_cat -> items_admin();
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> display('operate/article_category_list.tpl');
	}
	
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		//页面显示
		$this -> smarty -> assign('more_navs', '添加文章分类');
		$this -> smarty -> display('operate/article_category_add.tpl');
	}
	
	private function add_submit(){
		$cat_name = trim($_POST['cat_name']);
		$displayorder = intval($_POST['displayorder']);
		
		if (empty($cat_name)){
			cpmsg(false, '请填写分类名称！', -1);
		}
		$art_cat = new md\article_category();
		$f = $art_cat -> add(array(
			'cat_name' => $cat_name,
			'displayorder' => $displayorder,
		));
		if ($f){
			cpmsg(true, '文章分类添加成功！', '/'.$this->mod.'/'.$this->col.'/cat');
		} else {
			cpmsg(false, '文章分类添加失败！', -1);
		}
	}
	
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$cat_id = $this -> params[2];
		
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$art_cat = new md\article_category();
		$cat_data = $art_cat -> info($cat_id);
		if ($cat_data){} else {
			cpmsg(false, '该分类不存在或已被删除！', -1);	
		}
		
		
		$this -> smarty -> assign('cat_data', $cat_data);
		$this -> smarty -> display('operate/article_category_edit.tpl');
	}
	
	private function edit_submit(){
		$cat_id = trim($_POST['cat_id']);
		$cat_name = trim($_POST['cat_name']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		if (empty($cat_name)){
			cpmsg(false, '请填写分类名称！', -1);
		}
		$art_cat = new md\article_category();
		$f = $art_cat -> update($cat_id ,array(
			'cat_name' => $cat_name,
			'displayorder' => $displayorder,
		));
		if ($f > 0){
			cpmsg(true, '文章更新成功！', '/'.$this->mod.'/'.$this->col.'/cat');
		} else {
			cpmsg(false, '文章更新失败！', -1);
		}
	}

	function del(){
		$cat_id = $this -> params[2];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$art_cat = new md\article_category();
		$art_cat -> delete($cat_id);
		cpurl('/'.$this->mod.'/'.$this->col.'/cat');
	}
	
	function hide(){
		$cat_id = $this -> params[2];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$art_cat = new md\article_category();
		$art_cat -> validate($cat_id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/cat');
	}
	
	function show(){
		$cat_id = $this -> params[2];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$art_cat = new md\article_category();
		$art_cat -> validate($cat_id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/cat');
	}
}