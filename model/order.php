<?php
namespace model;

/**
 +--------------------------------
 *	订单类
 +--------------------------------
 */
class Order extends Front {
	
	//订单状态
	private $order_status = array(
		-1  => '已取消',
		0	=> '待付款',
		1	=> '已付款',		//系统
		2 	=> '已确认',		//管理员 -- 确认已付款的金额
		3	=> '已订货',		//管理员 -- 检查商品是否断货后 -- 确认订货按钮
		4	=> '部分发货',	//韩方 	-- 发货 -- 第一件时 -- 自动更新订单状态
		5	=> '全发货',		//韩方 	-- 发货 -- 最后一件时 -- 自动更新订单状态
		6	=> '全断货',		//韩方	-- 设置商品断货 -- 如果全部商品都断货则状态改为全断货[supplier]
	);
	
	//获取订单信息
	function info($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `order_info` WHERE order_id = '.$order_id.'';
		return $this -> db -> row($sql);
	}
	
	//获取订单信息
	function get_info_by_sn($order_sn){
		if (!isint($order_sn) || $order_sn <= 0){return false;}
		
		$sql = 'SELECT * FROM `order_info` WHERE order_sn = '.$order_sn.'';
		return $this -> db -> row($sql);
	}
	
	//获取订单状态
	function fetch_order_status($order_status){
		return $this -> order_status[$order_status];
	}
	
	//获取所有订单状态
	function fetch_all_status(){
		return $this -> order_status;
	}
	
	//添加订单
	function add($data){
		$uid = $data['uid'];
		$grade_id = intval($data['grade_id']); 
		$consignee = trim($data['consignee']);
		$mobile = trim($data['mobile']);
		$province_id = intval($data['province_id']);
		$city_id = intval($data['city_id']);
		$county_id = intval($data['county_id']);
		$zipcode = trim($data['zipcode']);
		$bigname = trim($data['bigname']);
		$address = trim($data['address']);
		$message = trim($data['message']);
		$shipping_id = intval($data['shipping_id']);
		$pay_id = intval($data['pay_id']);
		$cartIdArr = $data['cartIdArr'];
		
		if (!is_array($cartIdArr) || empty($cartIdArr)){
			return json_encode(array('error' => -1, 'message' => '购物车信息错误！'));	
		}
		
		//获取地区信息
		$region = new region();
		$province_name = $region -> get_name_by_id($province_id, 1);
		$city_name = $region -> get_name_by_id($city_id, 2);
		$county_name = $region -> get_name_by_id($county_id, 3);
		if (empty($province_name) || empty($city_name) || empty($county_name)){
			return array('error' => -1, 'message' => '配送地区错误！');
		}
		
		//获取配送方式
		$shipping = new Shipping();
		$shipping_data = $shipping -> info($shipping_id);
		if ($shipping_data){
			$shipping_code = $shipping_data['shipping_code'];
			$shipping_name = $shipping_data['shipping_name'];
		} else {
			return array('error' => -1, 'message' => '配送方式错误！');
		}
		
		//获取支付方式
		$payment = new payment();
		$payment_data = $payment -> info($pay_id);
		if ($payment_data){
			$pay_code = $payment_data['pay_code'];
			$pay_name = $payment_data['pay_name'];
		} else {
			return array('error' => -1, 'message' => '支付方式错误！');
		}
		
		//查询购物车商品信息 -- 其中的prop_value和prop_value_kr 是反序列后的数组
		$total_weight = $total_price = $total_number = 0;
		$cart = new cart();
		$cart_items = $cart -> items($uid, $grade_id, $cartIdArr);
		if ($cart_items){
			foreach ($cart_items as $item){
				$total_price  += $item['number'] * $item['price'];
				$total_weight += $item['weight'] * $item['number'];
				$total_number += $item['number'];
			}	
		} else {
			return array('error' => -1, 'message' => '购物车为空！');
		}
		
		//查询配送费用
		$shipping_fee = $shipping -> shipping_fee(array(
			'shipping_id' => $shipping_id,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'total_weight' => $total_weight,
			'total_price' => $total_price,
		));
		
		if ($shipping_fee === false){
			return array('error' => -1, 'message' => '配送费用计算错误！');
		}
		
		//写入数据库的数据
		$data = array(
			'uid' => $uid,
			'consignee' => $consignee,
			'mobile' => $mobile,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'province_name' => $province_name,
			'city_name' => $city_name,
			'county_name' => $county_name,
			'zipcode' => $zipcode,
			'bigname' => $bigname,
			'address' => $address,
			'message' => $message,
			'shipping_code' => $shipping_code,
			'shipping_name' => $shipping_name,
			'shipping_fee' => $shipping_fee,
			'pay_code' => $pay_code,
			'pay_name' => $pay_name,
			'order_amount' => $total_price + $shipping_fee,
			'product_amount' => $total_price,
			'add_time' => time(),
		);
		
		//生成唯一的订单号
		$order_sn = $this -> make_order_sn();
		$data['order_sn'] = $order_sn;
		
		//写入订单信息表
		$order_id = $this -> db -> insert('order_info', $data);
		if ($order_id){
			$data_order_product = array();
			foreach ($cart_items as $item){
				$data_order_product[] = array(
					'uid' => $uid,
					'order_id' => $order_id,
					'order_sn' => $order_sn,
					'prd_id' => $item['prd_id'],
					'sku_id' => $item['sku_id'],
					'pic_thumb' => $item['pic_thumb'],
					'pic_small' => $item['pic_small'],
					'product_name' => $item['product_name'],
					'product_name_kr' => $item['product_name_kr'],
					'product_sn' => $item['product_sn'],
					'prop_value' => serialize($item['prop_value']),
					'prop_value_kr' => serialize($item['prop_value_kr']),
					'price' => $item['price'],
					'price_kr' => $item['price_kr'],
					'is_spot' => $item['is_spot'],
					'number' => $item['number'],
					'add_time' => time(),
				);
			}
			
			//将商品写入到订单商品表
			$order_product = new order_product();
			$num = $order_product -> add_multi($data_order_product);
			if ($num == count($cart_items)){
				//清空购物车
				$cart -> clear($uid);
				return array('error' => 0, 'order_id' => $order_id, 'order_sn' => $order_sn);
			} else {
				//写入失败 -- 清除写入的数据
				$this -> db -> delete('order_product', array('order_id' => $order_id));
				$this -> db -> delete('order_info', array('order_id' => $order_id));
				return array('error' => -1, 'message' => '写入订单商品错误！');
			}
		} else {
			return array('error' => -1, 'message' => '写入订单信息错误！');
		}
	}
	
	//生成一个订单号
	function make_order_SN(){
		do {
			$num = rand(1, 999999);
			$sn = date('Ymd') . str_pad($num, 6, '0', STR_PAD_LEFT);
			$if = $this -> isExist_order_SN($sn);
		} while ($if);
		
		return $sn; 
	}
	
	//判断订单号是否存在
	function isExist_order_SN($order_sn){
		$sql = 'SELECT COUNT(*) AS num FROM `order_info` WHERE order_sn = '.$order_sn.'';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//更新订单信息
	function update($order_id, $data){
		if (!isint($order_id) || $order_id <= 0){return false;}
		return $this -> db -> update('order_info', $data, array('order_id' => $order_id)); 
	}
	
	
	//计算订单价格
	//未支付的订单可以重新计算金额
	//订单的商品为空直接取消订单
	function Count_order_amount($order_id, $order_data = ''){
		if (!empty($order_data) && is_array($order_data)){} else {
			$order_data = $this -> info($order_id);
		}
		
		//不是未付款的状态不需要执行此方法
		if (intval($order_data['order_status']) !== 0){
			return false;
		}
		
		$order_PRD = new order_product();
		
		//重新计算订单金额
		$total_weight = $total_price = $total_number = 0;
		$order_PRD_items = $order_PRD -> status_items($order_id, 0); //未付款状态的订单商品
		if ($order_PRD_items){
			foreach ($order_PRD_items as $item){
				$total_price  += $item['price']  * $item['number'];
				$total_weight += $item['weight'] * $item['number'];
				$total_number += $item['number'];
			}
		}
		
		//商品为空，直接取消订单
		if ($order_PRD_items == false || $total_price == 0){
			$this -> cancle_nopay($order_id);
			return false;
		}
		
		//查询配送ID
		$shipping = new shipping();
		$shipping_data = $shipping -> get_info_by_code($order_data['shipping_code']);
		if ($shipping_data){
			$shipping_id = $shipping_data['shipping_id'];
		} else {
			return false;
		}
		
		//查询配送费用
		if ($total_weight == 0){
			$shipping_fee = 0;
		} else {
			$shipping_fee = $shipping -> shipping_fee(array(
				'shipping_id' 	=> $shipping_id,
				'province_id' 	=> $order_data['province_id'],
				'city_id' 		=> $order_data['city_id'],
				'county_id' 	=> $order_data['county_id'],
				'total_weight' 	=> $total_weight,
				'total_price'	=> $total_price,
			));
			
			if ($shipping_fee === false){
				return false;
			}
		}
		
		//更新订单金额
		return array(
			'shipping_fee' => $shipping_fee,
			'product_amount' => $total_price,
			'order_amount' => $total_price + $shipping_fee,
		);
	}
	
	
	//购物车写入订单
	function cart_to_order($data){
		
		$uid = $data['uid'];
		$grade_id = intval($data['grade_id']);
		
		$addr_id = intval($data['addr_id']);
		$province_id = intval($data['province_id']);
		$city_id = intval($data['city_id']);
		$county_id = intval($data['county_id']);
		$shipping_id = intval($data['shipping_id']);
		$pay_id = intval($data['pay_id']);
		
		$consignee = trim($data['consignee']);
		$mobile = trim($data['mobile']);
		$zipcode = trim($data['zipcode']);
		$address = trim($data['address']);
		$message = trim($data['message']);
		
		if ($addr_id == 0){
			//判断填写的收件人信息是否正确
			if (empty($consignee) || empty($mobile) || $province_id <= 0 || $city_id <= 0 || $county_id <= 0 || empty($zipcode) || !isint($zipcode) || empty($address)){
				return array('error' => -1, 'message' => '配送地址错误！');
			}
		} else {	
			//查询收货地址信息
			$addr = new user_address();
			$addr_data = $addr -> info($addr_id, $uid);
			if ($addr_data){} else {
				return array('error' => -1, 'message' => '配送地址错误！');
			}
			
			$province_id = $addr_data['province_id'];
			$city_id = $addr_data['city_id'];
			$county_id = $addr_data['county_id'];
			$consignee = $addr_data['consignee'];
			$mobile = $addr_data['mobile'];
			$zipcode = $addr_data['zipcode'];
			$address = $addr_data['address'];
		}
		
		//配送方式
		if ($shipping_id <= 0){
			return array('error' => -1, 'message' => '配送方式错误！');
		}
		
		//支付方式
		if ($pay_id <= 0){
			return array('error' => -1, 'message' => '支付方式错误！');
		}
		
		//获取大字
		$region = new region();
		$bigname = $region -> fetch_bigname($county_id);
		
		//写入订单
		$ret = $this -> add(array(
			'uid' => $uid,
			'grade_id' => $grade_id,
			'consignee' => $consignee,
			'mobile' => $mobile,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'zipcode' => $zipcode,
			'bigname' => $bigname,
			'address' => $address,
			'message' => $message,
			'shipping_id' => $shipping_id,
			'pay_id' => $pay_id,
		));
		if ($ret['error'] == 0){
			$order_id = $order_data['order_id'];
			$order_sn = $order_data['order_sn'];
			
			//订单记录
			$order_log = new order_log();
			$order_log -> add(array(
				'order_id' => $order_id,
				'uid' => $uid,
				'operate' => 'CREATE',
				'content' => '生成订单，订单编号：'.$order_sn
			));
		}
		
		return $ret;
	}
	
	//订单返还金额
	function refund($order_id, $money){
		return $this -> db -> update_counter('order_info', array(
			'refund_amount' => $money
		), array(
			'order_id' => $order_id
		));
	}
	
	//订单查询
	function search_admin($params, $page, $pagesize){
		$k = trim($params['k']);
		$order_sn = trim($params['order_sn']);
		$order_status = trim($params['order_status']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		
		if ($order_status === ''){
			$order_status = 'all';
		}
		
		//获取所有的订单状态列表
		$order_status_items = $this -> fetch_all_status();
		
		//设计查询代码
		$where = '';
		if (isint($order_sn) && strlen($order_sn) == 14){
			$where .= ' AND o.order_sn = \''.encode($order_sn).'\'';
		}
		if (!empty($k)){
			$where .= ' AND (u.uname like \'%'.encode($k).'%\' OR o.consignee like \'%'.encode($k).'%\')';
		}
		if ($order_status != 'all' && in_array($order_status, array_keys($order_status_items))){
			$where .= ' AND o.order_status = '.$order_status.'';
		}
		if (isDate($start_time)){
			$where .= ' AND o.add_time > '.strtotime($start_time).'';
		}
		if (isDate($end_time)){
			$where .= ' AND o.add_time < '.strtotime($end_time).'';
		}
		
		$sql  = ' SELECT o.order_id, o.order_sn, o.order_status, o.uid, o.add_time, o.consignee, o.province_name, o.city_name, o.county_name, o.address, o.mobile, o.message_time, o.shipping_fee, o.order_amount, o.product_amount, o.pay_amount, o.refund_amount, u.uname FROM `order_info` o ';
		$sql .= ' LEFT JOIN `user_info` u ON o.uid = u.uid ';
		$sql .= ' WHERE 1=1 '.$where.' ';
		$sql .= ' ORDER BY o.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$order_items = array();
			foreach ($rows as $row){
				$order_id = $row['order_id'];
				$row['order_amount'] = format_money($row['order_amount']);
				$row['product_amount'] = format_money($row['product_amount']);
				$row['pay_amount'] = format_money($row['pay_amount']);
				$row['shipping_fee'] = format_money($row['shipping_fee']);
				$row['refund_amount'] = format_money($row['refund_amount']);
				$row['order_status_text'] = $this -> fetch_order_status($row['order_status']);
				$order_items[] = $row;
			}
		} else {
			$order_items = false;
		}
		
		$sql = 'SELECT count(*) as num FROM `order_info` o LEFT JOIN `user_info` u ON o.uid = u.uid  WHERE 1=1 '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);
	}
	
	
	//取消订单 -- 仅仅未付款的订单才可以取消
	function cancle($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		return $this -> db -> update('order_info', array(
			'order_status' => -1,
			'cancle_time' => time(),
		), array(
			'order_status' => 0,
			'order_id' => $order_id
		));
	}
	
	//删除订单 -- 仅仅取消的订单才可以删除
	function delete($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		return $this -> db -> update('order_info', array(
			'is_delete' => 1,
			'delete_time' => time(),
		), array(
			'order_status' => -1,
			'order_id' => $order_id
		));
	}
	
	//订单确认
	function confirm($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		return $this -> db -> update('order_info', array(
			'order_status' => 2,
			'confirm_time' => time(),
		), array(
			'order_status' => 1,
			'order_id' => $order_id
		));
	}
	
	//更改订货
	function ordered($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		return $this -> db -> update('order_info', array(
			'order_status' => 3,
			'order_time' => time(),
		), array(
			'order_status' => 2,
			'order_id' => $order_id
		));	
	}
	
	//取消订货操作 -- 订货失败取消
	function ordered_cancle($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		return $this -> db -> update('order_info', array(
			'order_status' => 2,
			'order_time' => 0,
		), array(
			'order_status' => 3,
			'order_id' => $order_id
		));	
	}
	
	//订单订货 -- 流程比较复杂
	function orderToKR($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}	
		
		$f = $this -> ordered($order_id);
		if ($f){
			$order_product = new order_product();
			
			//订货商品写入条码表
			$f2 = $order_product -> orderToBC($order_id);
			if ($f2){
				//订单商品的订货操作
				$f3 = $order_product -> orderToKR($order_id);
				if ($f3){
					
					//将现货直接写入国内到货表
					$fx1 = $order_product -> spotToRecv($order_id);
					
					//将非现货写入到韩国发货表
					$fx2 = $order_product -> orderToKRSend($order_id); 
					
					if ($fx1 && $fx2){
						return true;	
					}
				} 
			}
		}
		//失败取消订单
		$this -> ordered_cancle($order_id);
		return false;	
	}
	
	//设置订单全断货 -- 没有有效的商品时
	function soldout($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		return $this -> db -> update('order_info', array(
			'order_status' => 6,
			'soldout_time' => time()
		), array(
			'order_id' => $order_id
		));
	}
	
	
	//商品发货后检查并更新订单状态 -- 韩方发货后调用 -- 判断是否全发货
	function all_send_update($order_id){
		//获取订单发货统计信息
		$barcode = new order_product_barcode();
		$counter = $barcode -> counter($order_id);
		if ($counter){
			if ($counter['no_send'] == 0){
				if ($counter['kr_send'] + $counter['cn_recv'] + $counter['cn_send'] + $counter['user_recv'] == 0){
					//全断货
					return $this -> update($order_id, array('order_status' => 6, 'soldout_time' => time()));
				} else {
					//全发货	
					return $this -> update($order_id, array('order_status' => 5, 'send_time' => time()));
				}
			} else {
				//部分发货
				return $this -> update($order_id, array('order_status' => 4, 'send_time' => time()));
			}		
		} else {
			return false;	
		}
	}
	
	
	//用户所有订单
	function user_order_all($params, $page, $pagesize){
		$uid = intval($params['uid']);
		
		$k = trim($params['k']);
		$status = trim($params['status']);
		$status = strtolower($status);
		
		$where = 'WHERE op.uid = '.$uid.' AND o.is_delete = 0 ';
		if (!empty($k)){
			$where .= ' AND (op.order_sn LIKE \'%'.encode($k).'%\' OR op.product_sn LIKE \'%'.encode($k).'%\' OR o.consignee LIKE \'%'.encode($k).'%\')';
		}
		
		//查询订单
		$sql  = ' SELECT op.*, ';
		$sql .= ' o.order_status, o.consignee, o.add_time as order_add_time, o.pay_time, ';
		$sql .= ' o.order_amount, o.shipping_fee, o.refund_amount ';
		$sql .= ' FROM `order_product` op ';
		$sql .= ' LEFT JOIN `order_info` o ON op.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' ORDER BY op.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$result = $this -> db -> rows($sql);
		$order_items = $this -> user_order_detail($result);
		
		//查询订单商品总数
		$sql  = ' SELECT op.op_id FROM `order_product` op';
		$sql .= ' LEFT JOIN `order_info` o ON op.order_id = o.order_id ';
		$sql .= ' '.$where;
		
		$sql = 'SELECT COUNT(*) AS num FROM ('.$sql.') X';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);
	} 
	
	//用户未付款订单
	function user_order_nopay($params, $page, $pagesize){
		$uid = intval($params['uid']);
		$k = trim($params['k']);
		$status = trim($params['status']);
		$status = strtolower($status);
		
		$where = 'WHERE op.uid = '.$uid.' AND o.is_delete = 0 AND o.order_status = 0 AND op.product_status = 0';
		if (!empty($k)){
			$where .= ' AND (op.order_sn LIKE \'%'.encode($k).'%\' OR op.product_sn LIKE \'%'.encode($k).'%\' OR o.consignee LIKE \'%'.encode($k).'%\')';
		}
		
		//查询订单
		$sql  = ' SELECT op.*, ';
		$sql .= ' o.order_status, o.consignee, o.add_time as order_add_time, o.pay_time, ';
		$sql .= ' o.order_amount, o.shipping_fee, o.refund_amount ';
		$sql .= ' FROM `order_product` op';
		$sql .= ' LEFT JOIN `order_info` o ON op.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' ORDER BY op.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$result = $this -> db -> rows($sql);
		$order_items = $this -> user_order_detail($result);
		
		//查询订单商品总数
		$sql  = ' SELECT op.op_id FROM `order_product` op';
		$sql .= ' LEFT JOIN `order_info` o ON op.order_id = o.order_id ';
		$sql .= ' '.$where;
		
		$sql = 'SELECT COUNT(*) AS num FROM ('.$sql.') X';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);	
	}
	
	//待发货订单
	function user_order_nosend($params, $page, $pagesize){
		$uid = intval($params['uid']);
		$k = trim($params['k']);
		$status = trim($params['status']);
		$status = strtolower($status);
		
		$where = 'WHERE op.uid = '.$uid.' AND bc.status = 0 ';
		if (!empty($k)){
			$where .= ' AND (op.order_sn LIKE \'%'.encode($k).'%\' OR op.product_sn LIKE \'%'.encode($k).'%\' OR o.consignee LIKE \'%'.encode($k).'%\')';
		}
		
		//查询订单
		$sql  = ' SELECT op.*, COUNT(bc.op_id) as order_number, ';
		$sql .= ' o.order_status, o.consignee, o.add_time as order_add_time, o.pay_time, ';
		$sql .= ' o.order_amount, o.shipping_fee, o.refund_amount ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		$sql .= ' ORDER BY bc.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$result = $this -> db -> rows($sql);
		$order_items = $this -> user_order_detail($result);
		
		//查询订单商品总数
		$sql  = ' SELECT bc.op_id ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		
		$sql = 'SELECT COUNT(*) AS num FROM ('.$sql.') X';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);
	}
	
	//已发货订单
	function user_order_send($params, $page, $pagesize){
		$uid = intval($params['uid']);
		$k = trim($params['k']);
		$status = trim($params['status']);
		$status = strtolower($status);
		
		$where = 'WHERE op.uid = '.$uid.' AND bc.status BETWEEN 1 AND 3 ';
		if (!empty($k)){
			$where .= ' AND (op.order_sn LIKE \'%'.encode($k).'%\' OR op.product_sn LIKE \'%'.encode($k).'%\' OR o.consignee LIKE \'%'.encode($k).'%\')';
		}
		
		//查询订单
		$sql  = ' SELECT op.*, COUNT(bc.op_id) as order_number, ';
		$sql .= ' o.order_status, o.consignee, o.add_time as order_add_time, o.pay_time, ';
		$sql .= ' o.order_amount, o.shipping_fee, o.refund_amount ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		$sql .= ' ORDER BY op.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$result = $this -> db -> rows($sql);
		$order_items = $this -> user_order_detail($result);
		
		//查询订单商品总数
		$sql  = ' SELECT bc.op_id ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		
		$sql = 'SELECT COUNT(*) AS num FROM ('.$sql.') X';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);		
	}
	
	
	//已签收订单
	function user_order_receive($params, $page, $pagesize){
		$uid = intval($params['uid']);
		$k = trim($params['k']);
		$status = trim($params['status']);
		$status = strtolower($status);
		
		$where = 'WHERE op.uid = '.$uid.' AND bc.status = 4 ';
		if (!empty($k)){
			$where .= ' AND (op.order_sn LIKE \'%'.encode($k).'%\' OR op.product_sn LIKE \'%'.encode($k).'%\' OR o.consignee LIKE \'%'.encode($k).'%\')';
		}
		
		//查询订单
		$sql  = ' SELECT op.*, COUNT(bc.op_id) as order_number, ';
		$sql .= ' o.order_status, o.consignee, o.add_time as order_add_time, o.pay_time, ';
		$sql .= ' o.order_amount, o.shipping_fee, o.refund_amount ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		$sql .= ' ORDER BY op.order_id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$result = $this -> db -> rows($sql);
		$order_items = $this -> user_order_detail($result);
		
		//查询订单商品总数
		$sql  = ' SELECT bc.op_id ';
		$sql .= ' FROM `order_product_barcode` bc ';
		$sql .= ' LEFT JOIN `order_product` op ON bc.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `order_info` o ON bc.order_id = o.order_id ';
		$sql .= ' '.$where;
		$sql .= ' GROUP BY bc.order_id, bc.op_id';
		
		$sql = 'SELECT COUNT(*) AS num FROM ('.$sql.') X';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $order_items, 'total' => $total);	
	}
	
	//获取用户订单信息
	function user_order_detail($result){
		if ($result){} else {return false;}
		
		$order_items = array();	
		$order_product = new order_product();
		
		//订单商品信息
		foreach ($result as $row){
			$order_id = $row['order_id'];
			if (!isset($order_item[$order_id])){
				$order_status_text = $this -> fetch_order_status($row['order_status']);
				$order_items[$order_id]['info'] = array(
					'order_id' => $order_id,
					'order_sn' => $row['order_sn'],
					'order_status' => $row['order_status'],
					'order_status_text' => $order_status_text,
					'add_time' => $row['order_add_time'],
					'consignee' => $row['consignee'],
					'pay_time' => $row['pay_time'],
					'order_amount' => format_money($row['order_amount']),
					'refund_amount' => $row['refund_amount'],
				);
			}
		}
		
		//获取订单商品信息
		foreach ($result as $row){
			$order_id = $row['order_id'];
			
			$price = $row['price'];
			$number = $row['number'];
			$total_price = $price*$number;
			
			//判断订单中的商品是否可以取消 -- (未付款) 或 (已订货 10天未发货的)
			$time = time() - ($row['order_time'] + 10 * 24 * 3600);
			if (($row['order_status'] == 0 && $row['product_status'] == 0) || ($row['order_status'] == 3 && $time > 0 && $row['product_status'] == 1)){
				$cancle_abled = 1; //可以取消
			} else {
				$cancle_abled = 0;	//不可以
			}
			
			//判断商品是否可以退换货 -- (货物已收货 并且 不是不可退换货的商品)
			//国内货时间10天内
			$time = time() - 10*24*3600;
			if (($row['product_status'] == 6 || $row['product_status'] == 7)  && $row['sendtime'] > $time && $row['is_no_refund'] == 0){
				$refund_abled = 1;	//可申请退换
			} else {
				$refund_abled = 0;	//不可申请
			}
			
			$order_items[$order_id]['items'][] = array(
				'op_id' => $row['op_id'],
				'order_id' => $row['order_id'],
				'order_sn' => $row['order_sn'],
				'product_name' => $row['product_name'],
				'product_sn' => $row['product_sn'],
				'prd_id' => $row['prd_id'],
				'sku_id' => $row['sku_id'],
				'product_status' => $row['product_status'],
				'product_status_text' => $order_product -> fetch_product_status($row['product_status']),
				'number' => $row['number'],
				'price' => format_money($price),
				'total_price' => format_money($total_price),
				'order_amount' => format_money($row['order_amount']),
				'pic_thumb' => $row['pic_thumb'],
				'pic_small' => $row['pic_small'],
				'prop_value' => unserialize($row['prop_value']),
				'cancle_abled' => $cancle_abled,
				'refund_abled' => $refund_abled,
				'refund_status' => $row['refund_status']
			);
		}
		
		return $order_items;
	}
}