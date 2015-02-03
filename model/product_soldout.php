<?php
namespace model;

/**
 +-----------------------------
 *	商品断货 -- 只记录商品的断货
 +-----------------------------
 */
class Product_Soldout extends front {
	
	//添加断货信息
	function add($prd_id){
		
		//更改商品断货
		$prd = new product();
		$prd -> soldout($prd_id);
		
		//删除原断货信息
		$this -> delete($prd_id);

		//添加新断货信息
		return $this -> db -> insert('product_soldout', array(
			'prd_id' => $prd_id,
			'add_time' => time(),
			'type' => 1,
		));
	}
	
	//取消断货
	function cancle($prd_id){
		
		//取消商品断货
		$prd = new product();
		$prd -> soldout_cancle($prd_id);
		
		//删除原断货信息
		$this -> delete($prd_id);
		
		//添加取消断货信息
		return $this -> db -> insert('product_soldout', array(
			'prd_id' => $prd_id,
			'type' => 0,
			'add_time' => time(),
		));
	}
	
	//删除断货信息
	function delete($prd_id){
		return $this -> db -> update('product_soldout', array(
			'is_delete' => 1, 
			'delete_time' => time()
		), array(
			'prd_id' => $prd_id,
			'is_delete' => 0
		));
	}
	
	//查询断货商品列表
	function search($params, $page, $pagesize){
		$sql = 'SELECT ';
	}
}