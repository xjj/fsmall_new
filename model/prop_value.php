<?php
namespace model;

/**
 +------------------------------
 *	淘宝销售属性值类
 +------------------------------
 */
class Prop_Value extends Front {
	
	//获取id
	function fetch($value, $value_kr){
		if (empty($value) || empty($value_kr)){return false;}
		
		$prop_value_id = $this -> get($value, $value_kr);
		if ($prop_value_id){
			return $prop_value_id;
		} else {
			return $this -> add($value, $value_kr);
		}
	}
	
	//查询
	function get($value, $value_kr){
		if (empty($value) || empty($value_kr)){return false;}
		
		$sql = 'SELECT prop_value_id FROM `product_prop_value` WHERE prop_value = \''.encode($value).'\' AND prop_value_kr = \''.encode($value_kr).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['prop_value_id']; 
		}
		return false;
	}
	
	//添加
	function add($value, $value_kr){
		if (empty($value) || empty($value_kr)){return false;}
		
		return $this -> db -> insert('product_prop_value', array(
			'prop_value' => $value,
			'prop_value_kr' => $value_kr
		));
	}
	
	//查询
	function info($prop_value_id){
		if (!isint($prop_value_id) || $prop_value_id <= 0){return false;}
		$sql = 'SELECT * FROM `product_prop_value` WHERE prop_value_id = '.$prop_value_id.'';
		return $this -> db -> row($sql);
	}
}

