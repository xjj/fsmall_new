<?php
namespace model;

/**
 +--------------------------------------
 *	商品配送记录类
 +--------------------------------------
 *	包括整个商品的流程记录
 +--------------------------------------
 */
class Order_Product_Shipping_Log extends Front {
	
	
	//添加
	function add($data){
		$parent_id = intval($data['parent_id']);
		$order_id = intval($data['order_id']);
		$op_id = intval($data['op_id']);
		$content = trim($data['content']);
		$shipping_name = trim($data['shipping_name']);
		$shipping_number = trim($data['shipping_number']);
		
		if ($parent_id <= 0){$parent_id = 0;}
		
		return $this -> db -> insert('order_product_shipping_log', array(
			'parent_id' => $parent_id,
			'order_id' => $order_id,
			'op_id' => $op_id,
			'content' => $content,
			'shipping_name' => $shipping_name,
			'shipping_number' => $shipping_number,
			'add_time' => time()
		));
	}
	
	//批量写入 - 后台操作订货的时候
	function add_multi($op_ids, $data){
		if (empty($op_ids) || !is_array($op_ids)){return false;}
		if (empty($data) || !is_array($data)){return false;}
		
		$op_ids = array_unique($op_ids);
		if (empty($op_ids)){return false;}
		
		$params = array();
		foreach ($op_ids as $op_id){
			$params[] = array(
				'parent_id' => 0,
				'order_id' => intval($data['order_id']),
				'op_id' => $op_id,
				'content' => trim($data['content']),
				'shipping_name' => trim($data['shipping_name']),
				'shipping_number' => trim($data['shipping_number']),
				'add_time' => time()
			);
		}
		
		return $this -> db -> insert_multi('order_product_shipping_log', $params);
	}
	
	//获取商品的物流信息
	function data($op_id){
		if (!isint($op_id) && $op_id <= 0){return false;}
		
		$sql = 'SELECT parent_id, content, shipping_name, shipping_number, add_time FROM `order_product_shipping_log` WHERE op_id = '.$op_id.' ORDER BY id ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $key => $row){
				if ($key == 0 && $row['parent_id'] > 0){
					$dt = $this -> data($row['parent_id']);
					if ($dt){
						$data = $dt;
					}
				} else {
					$data[] = array(
						'content' => $row['content'],
						'shipping_name' => $row['shipping_name'],
						'shipping_number' => $row['shipping_number'],
						'add_time' => date('m-d H:i', $row['add_time'])
					);
				}
			}
			if (!empty($data)){
				return $data;
			}
		}
		return false;
	}
}