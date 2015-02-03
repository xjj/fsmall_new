<?php
namespace model;

/**
 +----------------------------------
 *	订单消息
 +----------------------------------
 */

class Order_Message extends Front {
	
	//添加记录
	function add($data){
		$order_id = intval($data['order_id']);
		$uid = intval($data['uid']);
		$adm_uid = intval($data['adm_uid']);
		
		if ($order_id == 0){return false;}
		
		if ($uid == 0 && $adm_uid == 0){return false;}
		
		$content = preg_replace('/\s+/', ' ', $content);
		$content = trim($data['content']);
		
		if (empty($content)){return false;}
		
		$f = $this -> db -> insert('order_message', array(
			'order_id' => $order_id,
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'content' => $content,
			'add_time' => time()
		));
		
		//更新最后留言时间
		if ($f){
			$order = new order();
			$order -> update($order_id, array('message_time' => time()));
		}
		
		return $f; 
	}
	
	//留言记录
	function items($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$sql = 'SELECT om.*, u.uname, admu.uname as adm_uname FROM `order_message` om LEFT JOIN `user_info` u ON om.uid = u.uid LEFT JOIN `system_user_info` as admu ON om.adm_uid = admu.uid WHERE om.order_id = '.$order_id.' ORDER BY id ASC';	
		return $this -> db -> rows($sql);
	}
}