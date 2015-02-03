<?php
namespace model;

/**
 +-----------------------------------
 *	国内到货类
 +-----------------------------------
 */
class Order_Product_CN_Shipping extends Front {
	
	//添加 -- 由韩发货表而来
	function add($params){
		if (!is_array($params)){return false;}
		
		$f = $this -> info($params['barcode']);
		if ($f){
			return false;	
		}
		
		return $this -> db -> insert('order_product_cn_shipping', array(
			'order_id' => $params['order_id'],
			'op_id' => $params['op_id'],
			'barcode' => $params['barcode'],
			'adm_uid' => $params['adm_uid'],
			'recv_time' => time()
		));	
	}
	
	//批量添加
	function add_multi($params){
		if (!is_array($params)){return false;}
		
		$data = $bcArr = array();
		foreach ($params as $item){
			if (!isint($item['order_id']) || !isint($item['op_id']) || !isint($item['barcode'])){
				continue;	
			}
			$data[] = array(
				'order_id' => $item['order_id'],
				'op_id' => $item['op_id'],
				'barcode' => $item['barcode'],
				'adm_uid' => intval($item['adm_uid']),
				'recv_time' => time(),
			);	
			$bcArr[] = $item['barcode'];
		}
		
		if (!empty($bcArr)){
			//检查是否存在
			$sql = 'SELECT COUNT(*) AS num FROM `order_product_cn_shipping` WHERE barcode IN ('.implode(',', $bcArr).')';
			$row = $this -> db -> row($sql);
			if ($row['num'] == 0){
				//批量写入
				return $this -> db -> insert_multi('order_product_cn_shipping', $data);		
			}
		} 
		return false; 	
	}
	
	//获取一条信息
	function info($barcode){
		if (!isint($barcode)){return false;}
		$sql = 'SELECT * FROM `order_product_cn_shipping` WHERE barcode = '.$barcode.'';
		return $this -> db -> row($sql);	
	}
	
	
	//清除写入的数据 --> 批量写入出错时执行
	function clear($bcArr){
		if (!is_array($bcArr)){return false;}
		$bcStr = implode(',', $bcArr);
		if (empty($bcStr)){return false;}
		return $this -> db -> delete('order_product_cn_shipping', 'barcode IN ('.$bcStr.')');
	}
}