<?php
namespace model;

/**
 +--------------------------------
 *	订单支付的最后处理流程
 +--------------------------------
 */

class Pay extends Front {
	
	//支付流程的最后一步 -- 更新与记录支付信息
	function success($data){
		
		$order_id = $data['order_id'];
		$order_sn = $data['order_sn'];
		$order_amount = $data['order_amount'];
		$pay_code = $data['pay_code'];
		$pay_name = $data['pay_name'];
		$uid = $data['uid'];
		
		$pay_log = new pay_log();
		
		//写入日志文件
		$pay_log -> log_to_file(array(
			'pay_code' => $pay_code,
			'order_sn' => $order_sn,
			'order_amount' => $order_amount
		));
		
		//记录支付日志
		$pay_log_id = $pay_log -> add(array(
			'uid' => $uid,
			'order_id' => $order_id,
			'pay_amount' => $order_amount,
			'ip' => getip(),
			'pay_code' => $pay_code,
			'status' => 1
		));
		
		
		//记录金额操作
		$money_log = new user_money_Log();
		$money_log -> add(array(
			'uid' => $uid, 
			'money' => $order_amount, 
			'order_id' => $order_id,
			'content' => '订单支付'
		));
		
		//更新订单状态
		$order = new order();
		$order -> update($order_id, array(
			'order_status' => 1,
			'pay_status' => 1,
			'pay_time' => time(),
			'pay_amount' => $order_amount,
			'pay_log_id' => $pay_log_id,
		));
	}
}