<?php
namespace model;

/**
 +------------------------------
 *	订单商品配送条码类
 +------------------------------
 */

class Order_Product_Barcode extends Front {
	
	//根据条码信息
	function info($bc){
		if (!isint($bc)){return false;}
		$sql = 'SELECT * FROM `order_product_barcode` WHERE barcode = '.$bc.'';
		return $this -> db -> row($sql);
	}
	
	//添加 -- 订货时的操作
	function add($data){
		if (!is_array($data)){return false;}

		$insert_data = array();
		
		$op_id = intval($data['op_id']);
		$order_id = intval($data['order_id']);
		$number = intval($data['number']);
		if ($op_id <= 0 || $order_id <= 0 || $number <= 0){
			return false;
		}
		
		for ($i = 0; $i < $number; $i++){
			//写入数据库
			$insert_data[] = array(
				'order_id' => $order_id,
				'op_id' => $op_id,
				'add_time' => time(),
			);
		}
		
		if (empty($insert_data)){
			return false; 
		} else {
			$x = $this -> db -> insert_multi('order_product_barcode', $insert_data);
			if ($x == $number){
				return true;
			} else {
				return false;
			}
		}
	}
	
	//清除-- 写入时出错时清除写入的记录 -- 其他情况不要执行此方法
	function clear($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		return $this -> db -> delete('order_product_barcode', array('order_id' => $order_id));
	}
	
	//通过条码获取商品信息
	//获取商品信息通过条码
	function PRDInfo($barcode) {
		if (!isint($barcode) || $barcode <= 0){
			return false;	
		}
		
		$sql  = ' SELECT bc.order_id, op.prd_id, p.brand_id, bc.op_id, bc.barcode, op.order_sn, op.product_name_kr, op.product_sn, op.prop_value_kr, op.price_kr ';
		$sql .= ' FROM `order_product_barcode` bc LEFT JOIN `order_product` op ON bc.op_id = op.op_id '; 
		$sql .= ' LEFT JOIN `product_info` p ON op.prd_id = p.prd_id ';
		$sql .= ' WHERE bc.barcode = \''.$barcode.'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			$row['prop_value_kr'] = unserialize($row['prop_value_kr']);
		}
		return $row;
	}
	
	//查询多条的商品信息
	function PRDinfo_multi($barcodeArr){
		if (!is_array($barcodeArr) || empty($barcodeArr)){
			return false;	
		}	
		
		$sql  = ' SELECT bc.order_id, op.prd_id, p.brand_id, bc.op_id, bc.barcode, op.order_sn, op.product_name_kr, op.product_sn, op.prop_value_kr, op.price_kr ';
		$sql .= ' FROM `order_product_barcode` bc LEFT JOIN `order_product` op ON bc.op_id = op.op_id '; 
		$sql .= ' LEFT JOIN `product_info` p ON op.prd_id = p.prd_id ';
		$sql .= ' WHERE bc.barcode IN ('.implode(',', $barcode).')';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			foreach ($rows as $k => $row){
				$rows[$k]['prop_value_kr'] = unserialize($row['prop_value_kr']);
			}
		}
		return $rows;
	}
}