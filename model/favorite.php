<?php
namespace model;

/**
 +---------------------------------
 *	加入收藏
 +---------------------------------
 */
class Favorite extends front {
	
	//添加
	function add($uid, $prd_id){
		
		if ($prd_id <= 0 || $uid <= 0){
			return false;
		}
		
		return $this -> db -> insert('product_favorite', array(
			'uid' => $uid,
			'prd_id' => $prd_id,
			'add_time' => time()
		));
	}
	
	//删除
	function delete($uid, $prd_id){
		return $this -> db -> delete('product_favorite', array('uid'=> $uid, 'prd_id' => $prd_id));
	}
	
	//判断是否已收藏
	function isExist($uid, $prd_id){
		$sql = 'SELECT COUNT(*) AS num FROM `product_favorite` WHERE uid = '.$uid.' AND prd_id = '.$prd_id.'';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true; 
		} else {
			return false;
		}
	}
	
	//列表
	function search($uid, $page, $pagesize){
		
		$where = ' WHERE f.uid = '.$uid.'';
		
		$sql = 'SELECT p.prd_id, p.product_name, p.price, p.freight, p.is_freight, p.brand_id, p.cat_id, p.pic_thumb, p.is_delete, p.is_on_sale FROM `product_favorite` f LEFT JOIN `product_info` p ON f.prd_id = p.prd_id '.$where.' ORDER BY f.favo_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = array();
			$prd = new product();
			foreach ($rows as $row){
				$product_items[] = $prd -> data($row['prd_id'], $uid, $row);
			}
			
			$sql = 'SELECT COUNT(*) AS num FROM `product_favorite` '.$where.'';
			$row = $this -> db -> row($sql);
			$total = $row['num'];
			
		} else {
			$product_items = false;
			$total = 0;
		}
		
		return array(
			'items' => $product_items,
			'total' => $total
		);		
	}
}
