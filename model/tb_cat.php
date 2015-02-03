<?php
namespace model;

/**
 +--------------------------
 *	淘宝类目类
 +--------------------------
 */
class TB_Cat extends Front {
	
	//获取信息
	function info($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `product_tb_cat` WHERE tb_cat_id = '.$tb_cat_id;
		return $this -> db -> row($sql);
	}
	
	//添加
	function add($data){
		$tb_cat_id = intval($data['tb_cat_id']);
		$tb_cat_name = trim($data['tb_cat_name']);
		$displayorder = intval($data['displayorder']);
		
		if ($tb_cat_id <= 0 || empty($tb_cat_name)){return false;}
		
		$f = $this -> isExist($tb_cat_id);
		if ($f){return false;}
		
		return $this -> db -> insert('product_tb_cat', array(
			'tb_cat_id' => $tb_cat_id,
			'tb_cat_name' => $tb_cat_name,
			'displayorder' => $displayorder,
		));
	}
	
	//判断是否存在
	function isExist($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		$sql = 'SELECT COUNT(*) AS num FROM `product_tb_cat` WHERE tb_cat_id = '.$tb_cat_id.'';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true; 
		} else {
			return false;
		}
	}
	
	//更新
	function update($tb_cat_id, $data){
		return $this -> db -> update('product_tb_cat', $data, array('tb_cat_id' => $tb_cat_id));
	}
	
	//删除
	function delete($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		return $this -> db -> delete('product_tb_cat', array('tb_cat_id' => $tb_cat_id));
	}
	
	//所有淘宝类目
	function items(){
		$sql = 'SELECT * FROM `product_tb_cat` ORDER BY displayorder ASC, tb_cat_id ASC';
		return $this -> db -> rows($sql);
	}
}