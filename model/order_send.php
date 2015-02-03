<?php
namespace model;

/**
 +-----------------------------------
 *	订单到货发货
 +-----------------------------------
 */
class Order_Send extends Front {
	
	//到货订单查询 -- 以订单为单位
	function search($params, $page, $pagesize){
		$order_sn = trim($params['order_sn']);
		$order_sn = trim($order_sn, ',');
		$order_sn = preg_replace('/[，,]\s*/', ',', $order_sn);
		
		$barcode  = trim($params['barcode']);
		
		$uname = trim($params['uname']); //发货人名或收货人名
		$shipping_code = trim($params['shipping_code']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		
		
		$order = new order();
		
		$where = ' WHERE sp.send_time = 0 AND sp.recv_time > 0 ';
		
		//查询订单号
		if (!empty($order_sn)){
			$where .= ' AND ( ';
			$order_sns = explode(',', $order_sn);
			$or = '';
			foreach ($order_sns as $item){
				if (empty($item) || !isint($item)){continue;}
				$where .= $or . ' o.order_sn = \''.encode($item).'\' ';
				$or = ' OR ';
			}
			$where .= ' ) ';
		}
		
		//查询条码
		if (!empty($barcode)){
			$where .= ' AND sp.barcode = \''.encode($barcode).'\' ';
		}
		
		//查询发货人
		if (!empty($uname)){
			$where .= ' AND (u.uname like \'%'.encode($uname).'%\' OR o.consignee like \'%'.encode($consignee).'%\')';
		}
		
		//订单的快递方式
		if (!empty($shipping_code)){
			$where .= ' AND o.shipping_code = \''.encode($shipping_code).'\' ';
		}
		
		//到货时间
		if (isDate($start_time)){
			$where .= ' AND sp.recv_time > '.strtotime($start_time).'';
		}
		if (isDate($end_time)){
			$where .= ' AND sp.recv_time < '.strtotime($end_time).'';
		}
		
		//从到货表中查询出到货的订单信息
		$sql  = ' SELECT o.*, max(sp.recv_time) as recv_time, u.uname ';
		$sql .= ' FROM `order_product_cn_shipping` sp ';
		$sql .= ' LEFT JOIN `order_product` op ON sp.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= ' LEFT JOIN `user_info` u ON op.uid = u.uid ';
		$sql .= ' '.$where.' ';
		$sql .= ' GROUP BY sp.order_id ';
		$sql .= ' ORDER BY recv_time DESC, sp.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.', '.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$order_items = array();
			foreach ($rows as $row){
				$row['order_status_text'] = $order -> fetch_order_status($row['order_status']);
				
				//查询订单的国内到货商品
				$row['items'] = $this -> cn_receive_items($row['order_id']);
				$row['total_number']   = $this -> order_product_number($row['order_id']);
				$row['cn_send_number'] = $this -> cn_send_number($row['order_id']);
				$row['cn_recv_number'] = count($row['items']);
				$order_items[] = $row;
			}
		} else {
			$order_items = false;
		}
		
		//查询总条数
		$sql  = ' SELECT COUNT(*) AS num ';
		$sql .= ' FROM `order_product_cn_shipping` sp ';
		$sql .= ' LEFT JOIN `order_product` op ON sp.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= ' LEFT JOIN `user_info` u ON op.uid = u.uid ';
		$sql .= ' '.$where.' ';
		$sql .= ' GROUP BY sp.order_id ';
		
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $order_items,
			'total' => $total
		);
	}
	
	//获取国内到货的商品
	private function cn_receive_items($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		//查到货商品
		$sql = 'SELECT op.*, sp.barcode, sp.recv_time FROM `order_product_cn_shipping` sp LEFT JOIN `order_product` op ON sp.op_id = op.op_id WHERE sp.order_id = '.$order_id.' AND sp.send_time = 0';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$row['price'] = format_money($row['price']);
				$row['prop_value'] = unserialize($row['prop_value']);
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	
	//查询已发货的商品
	private function cn_sent_items($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		//查到货商品
		$sql = 'SELECT op.*, sp.barcode, sp.recv_time, sp.send_time, sp.shipping_code, sp.shipping_name, sp.shipping_number FROM `order_product_cn_shipping` sp LEFT JOIN `order_product` op ON sp.op_id = op.op_id WHERE sp.order_id = '.$order_id.' AND sp.send_time > 0 AND sp.shipping_code != \'\' AND sp.shipping_number != \'\'';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$row['price'] = format_money($row['price']);
				$row['prop_value'] = unserialize($row['prop_value']);
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	
	//查询国内已发货的商品数
	function cn_send_number($order_id){
		$sql = 'SELECT COUNT(*) as num FROM `order_product_cn_shipping` WHERE order_id = '.$order_id.' AND recv_time > 0 AND send_time > 0';
		$row = $this -> db -> row($sql);
		return $row['num'];
	}
	
	//查询国内已到货商品数
	function cn_recv_number($order_id){
		$sql = 'SELECT COUNT(*) as num FROM `order_product_cn_shipping` WHERE order_id = '.$order_id.' AND recv_time > 0 AND send_time = 0';
		$row = $this -> db -> row($sql);
		return $row['num'];		
	}
	
	//查询订单商品总数
	function order_product_number($order_id){
		$sql = 'SELECT SUM(number) AS num FROM `order_product` WHERE order_id = '.$order_id.' AND product_status IN (1,4,5,6,7)';
		$row = $this -> db -> row($sql);
		return $row['num'];	
	}
	
	//已发货订单商品查询
	function search_sent($params, $page, $pagesize){
		$order_sn = trim($params['order_sn']);
		$order_sn = trim($order_sn, ',');
		$order_sn = preg_replace('/[，,]\s*/', ',', $order_sn);
		
		$barcode  = trim($params['barcode']);
		
		$uname = trim($params['uname']); //发货人名或收货人名
		$shipping_code = trim($params['shipping_code']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		
		
		$order = new order();
		
		$where = ' WHERE sp.send_time > 0 AND sp.recv_time > 0 ';
		
		//查询订单号
		if (!empty($order_sn)){
			$where .= ' AND ( ';
			$order_sns = explode(',', $order_sn);
			$or = '';
			foreach ($order_sns as $item){
				if (empty($item) || !isint($item)){continue;}
				$where .= $or . ' o.order_sn = \''.encode($item).'\' ';
				$or = ' OR ';
			}
			$where .= ' ) ';
		}
		
		//查询条码
		if (!empty($barcode)){
			$where .= ' AND sp.barcode = \''.encode($barcode).'\' ';
		}
		
		//查询发货人
		if (!empty($uname)){
			$where .= ' AND (u.uname like \'%'.encode($uname).'%\' OR o.consignee like \'%'.encode($uname).'%\')';
		}
		
		//订单的快递方式
		if (!empty($shipping_code)){
			$where .= ' AND o.shipping_code = \''.encode($shipping_code).'\' ';
		}
		
		//到货时间
		if (isDate($start_time)){
			$where .= ' AND sp.recv_time > '.strtotime($start_time).'';
		}
		if (isDate($end_time)){
			$where .= ' AND sp.recv_time < '.strtotime($end_time).'';
		}
		
		//从到货表中查询出到货的订单信息
		$sql  = ' SELECT o.*, max(sp.recv_time) as recv_time, u.uname ';
		$sql .= ' FROM `order_product_cn_shipping` sp ';
		$sql .= ' LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= ' LEFT JOIN `user_info` u ON o.uid = u.uid ';
		$sql .= ' '.$where.' ';
		$sql .= ' GROUP BY sp.order_id ';
		$sql .= ' ORDER BY recv_time DESC, sp.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.', '.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$order_items = array();
			foreach ($rows as $row){
				$row['order_status_text'] = $order -> fetch_order_status($row['order_status']);
				
				//查询订单的国内到货商品
				$row['items'] = $this -> cn_sent_items($row['order_id']);
				$row['total_number']   = $this -> order_product_number($row['order_id']);
				$row['cn_send_number'] = count($row['items']);
				$row['cn_recv_number'] = $this -> cn_recv_number($row['order_id']);
				$order_items[] = $row;
			}
		} else {
			$order_items = false;
		}
		
		//查询总条数
		$sql  = ' SELECT COUNT(*) AS num ';
		$sql .= ' FROM `order_product_cn_shipping` sp ';
		$sql .= ' LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= ' LEFT JOIN `user_info` u ON o.uid = u.uid ';
		$sql .= ' '.$where.' ';
		$sql .= ' GROUP BY sp.order_id ';
		
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $order_items,
			'total' => $total
		);
	}
	
	//发货操作
	//国内发货 -- 国内到货的商品 + 现货发货
	function cn_send_multi($barcode, $spno, $adm_uid){
		if (empty($barcode)){return false;}
		if (empty($spno)){return false;}
		
		$idArr = explode(',', $barcode);
		$ids = array();
		foreach ($idArr as $id){
			if (isint($id) && $id > 0){
				$ids[] = $id;
			}
		}
		$ids = array_unique($ids);
		
		//查询商品数据
		$sql  = 'SELECT sp.op_id, sp.barcode, o.order_id, o.shipping_code, o.shipping_name, o.consignee ';
		$sql .= 'FROM `order_product_cn_shipping` sp ';
		$sql .= 'LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= 'WHERE sp.barcode IN ('.implode(',', $ids).') AND sp.send_time = 0 ';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){} else {
			return false;
		}
		
		$sp_data = $sp_code = $consignee = array();
		foreach ($rows as $row){
			$sp_code[] = trim($row['shipping_code']);
			$consignee[] = trim($row['consignee']);
			$sp_data[] = array(
				'op_id' => $row['op_id'], 
				'order_id' => $row['order_id'],
				'barcode' => $row['barcode'], 
				'shipping_code' => $row['shipping_code'], 
				'shipping_name' => $row['shipping_name'],
				'shipping_number' => $spno,
				'adm_uid' => $adm_uid
			);
		}
		
		
		//判断配送方式是不是相同的 -- 收货人是不是相同的
		$sp_code = array_unique($sp_code);
		$consignee = array_unique($consignee);
		if (count($sp_code) > 1 || count($consignee) > 1){
			return false;
		}
		
		//循环每一个商品--发货
		$num = 0;
		foreach ($sp_data as $data){
			$f = $this -> cn_send_product($data);
			if ($f){
				$num += 1;	
			}
		}
		
		return $num;
	}
	
	//国内发货 -- 单商品发货
	function cn_send($barcode, $spno, $spcode, $adm_uid){
		if (empty($barcode)){return false;}
		if (empty($spno)){return false;}
		if (empty($spcode)){return false;}
		
		$sql  = 'SELECT sp.op_id, sp.barcode, o.order_id ';
		$sql .= 'FROM `order_product_cn_shipping` sp ';
		$sql .= 'LEFT JOIN `order_info` o ON sp.order_id = o.order_id ';
		$sql .= 'WHERE sp.barcode = \''.$barcode.'\' AND sp.send_time = 0 ';
		
		$row = $this -> db -> row($sql);
		if ($row){} else {
			return false;
		}
		
		//查询配送方式
		$shipping = new shipping();
		$shipping_data = $shipping -> get_info_by_code($spcode);
		if ($shipping_data){
			$spname = $shipping_data['shipping_name'];
		} else {
			return false; 	
		}
		
	  	$data = array(
			'op_id' => $row['op_id'], 
			'order_id' => $row['order_id'],
			'barcode' => $row['barcode'], 
			'shipping_code' => $spcode, 
			'shipping_name' => $spname,
			'shipping_number' => $spno,
			'adm_uid' => $adm_uid
		);
		
		return $this -> cn_send_product($data);	
	}
	
	//国内商品发货
	private function cn_send_product($data){
		$op_id = $data['op_id'];
		$barcode = $data['barcode'];
		$order_id = $data['order_id'];
		$shipping_code = $data['shipping_code'];
		$shipping_name = $data['shipping_name'];
		$shipping_number = $data['shipping_number'];
		$adm_uid = intval($data['adm_uid']);
		
		//设置商品发货
		$f = $this -> db -> update('order_product_cn_shipping', array(
			'shipping_code' => $shipping_code,
			'shipping_name' => $shipping_name,
			'shipping_number' => $shipping_number,
			'send_time' => time(),
			'adm_uid' => $adm_uid
		), array(
			'barcode' => $barcode,
			'op_id' => $op_id,
			'order_id' => $order_id,
			'send_time' => 0
		));
		
		if ($f){
			//更新发货新到订单商品表
			$order_product = new order_product();
			$order_product -> CNSendUpdate(array(
				'op_id' => $op_id,
				'order_id' => $order_id,
			));	
		}
		return $f;
	}
	
	//国内到货 -- bc = 条码
	function cn_receive($barcode, $adm_uid){
		if (empty($barcode)){return false;}
		
		//查询韩发货表信息
		$kr_shipping = new order_product_kr_shipping();
		$barcode_data = $kr_shipping -> info($barcode);
		if ($barcode_data){
			$op_id = $barcode_data['op_id'];
			$order_id = $barcode_data['order_id'];	
		} else {
			return false;
		}
		
		//写入到国内到货表
		$cn_shipping = new order_product_cn_shipping();
		$f = $cn_shipping -> add(array(
			'barcode' => $barcode,
			'op_id' => $op_id,
			'order_id' => $order_id, 
			'adm_uid' => $adm_uid,
		));
		if ($f){
			//更新订单商品表信息
			$order_product = new order_product();
			$order_product -> CNRecvUpdate(array(
				'op_id' => $op_id,
				'order_id' => $order_id,
			));	
		}
		if ($f){
			return array(
				'op_id' => $op_id,
				'order_id' => $order_id,
				'barcode' => $barcode 
			);
		} else {
			return false;	
		}
	}
	
	//客户确认收货
	function user_receive($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		//查询订单商品
		$data = $this -> info($op_id);
		if ($data){} else {
			return false; 
		}
		
		if ($data['order_id'] != $order_id){
			return false;
		}
		
		$product_status = $data['product_status'];
		if ($product_status != 6){
			return false;
		}
		
		$f = $this -> update($op_id, array('product_status' => 7, 'user_receive_time' => time()));
		if ($f){
			//物流记录到货
			$tpl = new order_product_logistics();
			$tpl -> add(array(
				'order_id' => $order_id,
				'op_id' => $op_id,
				'content' => '确认收货'
			));
		}
		return $f;
	}
	
	
}