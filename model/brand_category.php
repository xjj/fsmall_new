<?php
namespace model;

/**
 +-----------------------------------
 *	品牌类目记录表
 +-----------------------------------
 */
class Brand_Category extends front {

	//查询某品牌的所有类目
	function items($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		$sql = 'SELECT bc.cat_id, c.cat_name, bc.displayorder FROM `product_brand_category` bc LEFT JOIN `product_category` c ON bc.cat_id = c.cat_id WHERE bc.brand_id = '.$brand_id.' ORDER BY displayorder ASC, id ASC';
		return $this -> db -> rows($sql);	
	}
	
	//查询品牌都有哪些二级类目
	function query_catid($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		$sql = 'SELECT p.cat_id FROM `product_info` p LEFT JOIN `product_category` c ON p.cat_id = c.cat_id WHERE p.brand_id = '.$brand_id.' AND p.is_delete = 0 AND c.status = 1 AND c.is_delete = 0 GROUP BY p.cat_id';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$arr = array();
			foreach ($rows as $row){
				$arr[] = $row['cat_id'];	
			}
		} else {
			return false;	
		}
		
		$ids = implode(',', $arr);
		
		$sql = 'SELECT parent_id FROM `product_category` WHERE cat_id IN ('.$ids.') AND layer = 3 AND status = 1 AND is_delete = 0 GROUP BY parent_id';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$cat_id = array();
			foreach ($rows as $row){
				$cat_id[] = $row['parent_id'];	
			}
			return $cat_id;
		} else {
			return false;	
		}
	}
	
	//更新
	function update($brand_id, $cat_ids){
		if (!isint($brand_id) || $brand_id <= 0 || empty($cat_ids) || !is_array($cat_ids)){return false;}	
		
		$data = array();
		foreach ($cat_ids as $cat_id){
			$data[] = array(
				'brand_id' => $brand_id,
				'cat_id' => $cat_id,
				'displayorder' => 0
			);	
		}
		if (!empty($data)){
			$this -> delete($brand_id);
			return $this -> db -> insert_multi('product_brand_category', $data);
		}
		return false;
	}
	
	//清理
	function delete($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}	
		return $this -> db -> delete('product_brand_category', array('brand_id' => $brand_id));
	}

	//更新排序
	function update_displayorder($brand_id, $cat_id, $displayorder){
		if (!isint($brand_id) || $brand_id <= 0){return false;}	
		if (!isint($cat_id) || $cat_id <= 0){return false;}	
		$displayorder = intval($displayorder);
		
		return $this -> db -> update('product_brand_category', array(
			'displayorder' => $displayorder
		), array(
			'brand_id' => $brand_id,
			'cat_id' => $cat_id
		));
	}
}