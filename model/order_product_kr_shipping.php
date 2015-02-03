<?php
namespace model;

/**
 +--------------------------------
 *	韩方物流信息类
 *	status		0=订货 1=发货 2=韩取消 3=客户取消
 +--------------------------------
 */
class Order_Product_KR_Shipping extends Front {
	
	//批量添加订货商品
	function add_multi($params){
		if (!is_array($params)){return false;}
		
		$data = $bcArr = array();
		foreach ($params as $item){
			if (
				!isint($item['order_id']) || $item['order_id'] <= 0 || 
				!isint($item['op_id']) || $item['op_id'] <= 0 || 
				!isint($item['barcode']) || $item['barcode'] <= 0 || 
				!isint($item['brand_id']) || $item['barcode'] <= 0
			){
				continue;	
			}
			$data[] = array(
				'order_id' => $item['order_id'],
				'op_id' => $item['op_id'],
				'barcode' => $item['barcode'],
				'brand_id' => $item['brand_id'],
				'adm_uid' => intval($item['adm_uid']),
				'order_time' => time(),
			);	
		}
		
		if (!empty($data)){
			//批量写入
			return $this -> db -> insert_multi('order_product_kr_shipping', $data);	
		} 
		return false; 		
	}
	
	//获取一条信息
	function info($barcode){
		if (!isint($barcode)){return false;}
		$sql = 'SELECT * FROM `order_product_kr_shipping` WHERE barcode = '.$barcode.'';
		return $this -> db -> row($sql);	
	}
	
	//获取多条信息
	function info_multi($barcodeArr){
		if (!is_array($barcodeArr) || empty($barcodeArr)){return false;}
		$sql = 'SELECT * FROM `order_product_kr_shipping` WHERE barcode IN ('.implode(',', $barcodeArr).')';
		return $this -> db -> rows($sql);	
	}
	
	//发货更新
	function sendUpdate($barcode, $data){
		if (!isint($barcode)){return false;}
		
		//获取商品信息
		$price_kr = $data['price_kr'];
		$discount = $data['discount'];
		$prd_id = $data['prd_id'];
		$order_id = $data['order_id'];
		$op_id = $data['op_id'];
 		
		return $this -> db -> update('order_product_kr_shipping', array(
			'status' => 1,
			'send_time' => time(),
			'prd_id' => $prd_id,
			'price_kr' => $price_kr,
			'discount' => $discount,
		), array(
			'barcode' => $barcode,
			'order_id' => $order_id,
			'op_id' => $op_id,
			'status' => 0
		));
	}
	
	//发货更新 -- 批量
	function sendUpdate_multi($params){
		if (!is_array($params) || empty($params)){return false;}
		
		$number = 0;
		foreach ($params as $data){
			$barcode = $data['barcode'];
			
			$f = $this -> sendUpdate($barcode, array(
				'price_kr' => $data['price_kr'],
				'discount' => $data['discount'],
				'prd_id' => $data['prd_id'],
				'order_id' => $data['order_id'],
				'op_id' => $data['op_id'],
			));
			
			if ($f){
				$number += 1;	
			}
		}
		return $number; 
	}
	
	//取消订单商品
	function cancle($barcode){
		if (!isint($barcode)){return false;}
		
		return $this -> db -> update('order_product_kr_shipping', array(
			'status' => 2,
			'kcancle_time' => time()
		), array(
			'barcode' => $barcode,
			'status' => 0
		));	
	}
	
	//撤销取消的订单商品
	function cancleBack($barcode){
		if (!isint($barcode)){return false;}
		
		return $this -> db -> update('order_product_kr_shipping', array(
			'status' => 0,
			'kcancle_time' => 0
		), array(
			'barcode' => $barcode,
			'status' => 2
		));		
	}
	
	//撤销发货的商品
	function cancleSend($barcode){
		if (!isint($barcode)){return false;}
		
		return $this -> db -> update('order_product_kr_shipping', array(
			'status' => 0,
			'send_time' => 0,
		), array(
			'barcode' => $barcode,
			'status' => 1
		));	
	}
	
	//更新商品退货信息
	function updateRefund($barcode){
		if (!isint($barcode)){return false;}
		return $this -> db -> update('order_product_kr_shipping', array(
			'is_refund' => 1,
			'refund_time' => time(),
		), array(
			'barcode' => $barcode,
			'status' => 1
		));	
	}
}
