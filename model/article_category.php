<?php
namespace model;

/**
 +-----------------------------------
 *	文章分类表
 +-----------------------------------
 */
class Article_Category extends Front {
	
	//添加
	function add($data){
		$cat_name = trim($data['cat_name']);
		$displayorder = intval($data['displayorder']);
		if (empty($cat_name)){return false;}
		return $this -> db -> insert('operate_article_category', array(
			'cate_name'	=> $cat_name,
			'displayorder' => $displayorder,
			'status' => 1
		));
	}
	
	//编辑
	function update($cat_id, $data){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		$cat_name = trim($data['cat_name']);
		$displayorder = intval($data['displayorder']);
		
		if (empty($cat_name)){return false;}
		
		return $this -> db -> update('operate_article_category', array(
			'cat_name' => $cat_name, 
			'displayorder' => $displayorder
		), array(
			'cat_id' => $cat_id
		));
	}
	
	//设置显示
	function validate($cat_id, $status){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		if ($status != 1){$status = 0;}
		
		return $this -> db -> update('operate_article_category', array(
			'status' => $status
		), array(
			'cat_id' => $cat_id
		));
	}
	
	//删除
	function delete($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		return $this -> db -> delete('operate_article_category', array('cat_id' => $cat_id)); 	
	}
	
	//所有分类
	function items(){
		$sql = 'SELECT cat_id, cat_name FROM `operate_article_category` WHERE status = 1 ORDER BY displayorder ASC, cat_id ASC';
		return $this -> db -> rows($sql);	
	}
	
	//查询所有分类
	function items_admin(){
		$sql = 'SELECT * FROM `operate_article_category` ORDER BY displayorder ASC, cat_id ASC';
		return $this -> db -> rows($sql);	
	}
	
	//获取信息
	function info($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		$sql = 'SELECT * FROM `operate_article_category` WHERE cat_id = '.$cat_id.'';
		return $this -> db -> row($sql); 	
	}
}