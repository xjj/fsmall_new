<?php
namespace model;

/**
 +------------------------------------
 *	商品销售属性类
 +------------------------------------
 */
class Product_Prop extends Front {
	
	//获取商品的销售属性
	function items($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		$sql = 'SELECT * FROM `product_info_prop` WHERE prd_id = '.$prd_id.' AND is_delete = 0 ORDER BY prop_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//添加一条销售属性
	function add($data){
		//验证数组信息
		if (!is_array($data) || empty($data)){return false;}
		
		return $this -> db -> insert('product_info_prop', array(
			'prd_id' => $data['prd_id'],
			'prop_value_id1' => $data['prop_value_id1'],	//对应销售属性名ID
			'prop_value_id2' => $data['prop_value_id2'],	//对应销售属性值ID
			'value' => $data['value'],						//销售属性值-中文
			'value_kr' => $data['value_kr'],				//销售属性值-韩文
			'add_time' => time()
		));
	}
	
	//更新销售属性信息
	function update($prop_id, $data){
		return $this -> db -> update('product_info_prop', $data, array('prop_id'=>$prop_id));
	}
	
	//删除某商品的所有销售属性
	function delete($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> db -> update('product_info_prop', array('is_delete' => 1), array('prd_id' => $prd_id));
	}
	
	/**
	 +-----------------------------------
	 *	更新属性信息并返回 prop_id
	 *	用于 添加或更新SKU 时更新商品销售属性
	 +-----------------------------------
	 */
	function fetch($data){
		$prd_id = $data['prd_id'];
		$prop_value_id1 = $data['prop_value_id1'];
		$prop_value_id2 = $data['prop_value_id2'];
		$value = trim($data['value']);
		$value_kr = trim($data['value_kr']);

		if (empty($value) || empty($value_kr)){return false;}
		
		$row = $this -> isExist($prd_id, $prop_value_id1, $prop_value_id2);
		if ($row){
			$prop_id = $row['prop_id'];
			if ($row['is_delete'] == 1){
				$this -> update($prop_id, array('is_delete' => 0));
			}
			return $prop_id; 
		} else {
			return $this -> add($data);	
		}
	}
	
	
	/**
	 +-------------------------------------
	 *	判断记录是否存在
	 +-------------------------------------
	 */
	function isExist($prd_id, $prop_value_id1, $prop_value_id2){
		$sql = 'SELECT * FROM `product_info_prop` WHERE prd_id = '.$prd_id.' AND prop_value_id1 = '.$prop_value_id1.' AND prop_value_id2 = '.$prop_value_id2.'';
		return $this -> db -> row($sql);
	}
	/**
	 +----------------------------------
	 *	获取商品的分组销售属性
	 *	用于页面输出商品的所有属性
	 +----------------------------------
	 */
	function group_items($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		$sql = 'SELECT t.*, n.prop_value FROM `product_info_prop` t LEFT JOIN `product_prop_value` n ON t.prop_value_id1 = n.prop_value_id WHERE t.prd_id = '.$prd_id.' AND t.is_delete = 0 order by t.prop_id ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$prop_value_id1 = $row['prop_value_id1'];
				$data[$prop_value_id1]['value'] = $row['prop_value'];
				$data[$prop_value_id1]['items'][] = $row;
			}
			return $data; 
		}
		return false; 
	}
}