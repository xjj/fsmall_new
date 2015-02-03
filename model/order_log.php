<?php
namespace model;

/**
 +-----------------------------------
 *	订单日志记录
 +-----------------------------------
 *	仅仅记录订单的操作
 +-----------------------------------
 */
class Order_Log extends Front {
	
	private $operate = array(
		'CREATE' => '生成订单',
		'PAY' => '支付',
		'CONFIRM' => '确认',
		'ORDER' => '订货',
		'SOLDOUT' => '断货',
		'CANCLE' => '取消',
		'KRSEND' => '韩方发货',
		'RECEIVE' => '国内到货',
		'CNSEND' => '国内发货',
	);
	
	//添加记录
	function add($data){
		$operate = trim($data['operate']);
		$operate = strtoupper($operate);
		
		//对 韩国发货 国内到货 国内发货的记录 另行处理
		if ($operate == 'KRSEND' || $operate == 'RECEIVE' || $operate == 'CNSEND'){
			$this -> add2($data);
			exit();
		}
		
		$order_id = intval($data['order_id']);
		$sku_id = intval($data['sku_id']);
		$content = trim($data['content']);
		$number = intval($data['number']);
		
		$uid  = intval($data['uid']);
		$adm_uid = intval($data['adm_uid']);
		$sp_uid = intval($data['sp_uid']);
		
		
		return $this -> db -> insert('order_log', array(
			'order_id' => $order_id,
			'sku_id' => $sku_id,
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'sp_uid' => $sp_uid,
			'content' => $content,
			'add_time' => time(),
			'number' => $number,
			'status' => 1,
		));
	}
	
	//查询订单所有日志记录
	function items($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		$sql = 'SELECT * FROM `order_log` WHERE order_id = '.$order_id.' '.$where.' ORDER BY id ASC';
		return $this -> db -> rows($sql); 
	}
	
	//添加记录 -- 发货和到货记录
	function add2($data){
		$order_id = intval($data['order_id']);
		$sku_id = intval($data['sku_id']);
		$content = trim($data['content']);
		$operate = trim($data['operate']);
		$number = intval($data['number']);
		$operate = strtoupper($operate);
		
		$uid  = intval($data['uid']);
		$adm_uid = intval($data['adm_uid']);
		$sp_uid = intval($data['sp_uid']);
		
		$data = $this -> info($order_id, $operate);
		if ($data){
			$number += $data['number'];
			return $this -> db -> update('order_logs', array(
				'number' => $number,
				'update_time' => time(),
			), array(
				'order_id' => $order_id,
				'operate' => $operate
			));
		} else {
			return $this -> db -> insert('order_log', array(
				'order_id' => $order_id,
				'sku_id' => $sku_id,
				'uid' => $uid,
				'adm_uid' => $adm_uid,
				'sp_uid' => $sp_uid,
				'content' => $content,
				'add_time' => time(),
				'number' => $number,
				'status' => 1,
			));
		}
	}
	
	//查询记录
	function info($order_id, $operate){
		$sql = 'SELECT * FROM `order_log` WHERE order_id = '.$order_id.' AND operate = \''.$operate.'\'';
		return $this -> db -> row($sql);
	}
}