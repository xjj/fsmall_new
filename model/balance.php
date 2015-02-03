<?php
namespace model;

/**
 +---------------------------
 *	用户余额类
 +---------------------------
 */
class Balance extends Front {
	
	//更新余额
	//$data = array(uid, adm_uid, order_sn, money, barcode, content, act_type)
	function update($uid, $money, $data){
		if (!isint($uid) || $uid <= 0){return false;}
		if (!is_numeric($money)){return false;}
		
		$f = $this -> db -> update_counter('user_info', array('balance' => $money), array('uid' => $uid)); 
		if ($f){
			//记录返还操作
			$money = new user_money_log();
			$money -> add($data);
		}
		return $f;
	}
	
	//增加
	function add($uid, $money, $data){
		if (!isint($uid) || $uid <= 0){return false;}
		if (!is_numeric($money)){return false;}
		
		$money = abs($money);
		$data['act_type'] = 'add';
		return $this -> update($uid, $money, $data);
	}
	
	//减
	function minus($uid, $money, $data){
		if (!isint($uid) || $uid <= 0){return false;}
		if (!is_numeric($money)){return false;}
		
		$money = -abs($money);
		$data['act_type'] = 'minus';
		return $this -> update($uid, $money, $data);
	}
	
	//获取用户的余额值
	function fetch_user_balance($uid){
		$user = new user();
		$user_data = $user -> info($uid, array('balance'));
		if ($user_data){
			return $user_data['balance'];
		} else {
			return false;
		}
	}
	
	//function 
	function get($uid){
		return $this -> fetch_user_balance($uid); 	
	}
	
	
	//余额支付
	function pay($data){
		
		$uid = $data['uid'];
		$order_id = $data['order_id'];
		$order_sn = $data['order_sn'];
		$order_amount = floatval($data['order_amount']);
		
		$f = $this -> update($uid, -$order_amount);
		if ($f > 0){
			//支付成功后续处理
			$pay = new pay();
			$pay -> success(array(
				'pay_code' => 'balance',
				'pay_name' => '余额支付',
				'uid' => $uid,
				'order_id' => $order_id,
				'order_sn' => $order_sn,
				'order_amount' => $order_amount
			));

			return true; 
		} else {
			return false;
		}
	}
}