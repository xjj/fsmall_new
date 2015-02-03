<?php
namespace model;
/**
 +-----------------------------
 *	获取商品图片模型
 +-----------------------------
 */
class Product_pics extends front {
	function search($array, $page=1, $pagesize=10){
		$where='1=1 ';
		if(isset($array['prd_id'])){
			if (!isint($prd_id) || $prd_id <= 0){return false;}
			$where .=' and prd_id = '.$prd_id;
		}
		if(isset($array['timeline'])){
			$where.=' and  LEFT (FROM_UNIXTIME(timeline), 10) >= LEFT (NOW(), 10)';
		}
		if(isset($array['is_delete'])){
			$where.=' and  is_delete <>1';
		}
		if(isset($array['is_confirm'])){
			$where.=' and  is_confirm<>1 ';
		}
		$sql = 'SELECT * FROM `product_pics` WHERE '.$where;
		$res = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `product_pics` WHERE '.$where;
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		return array(
			'items' => $res,
			'num' => $total
		);	
	}
}