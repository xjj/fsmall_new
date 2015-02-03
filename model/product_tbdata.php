<?php
namespace model;

/**
 +-----------------------------
 *	淘宝数据包
 +-----------------------------
 */
class Product_tbdata extends front {
	
	//添加
	function add($data){
		
		return $this -> db -> insert('product_tbdata', array(
			'prd_id' => $data['prd_id'],
			'csv' => $data['csv'],
			'pic' => $data['pic'],
			'adm_uid' => $data['adm_uid'],
			'add_time' => time(),
		));
	}
	
/*------------------------分割线 下面的是无用的 可删----------------*/
	
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